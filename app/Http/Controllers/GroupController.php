<?php

namespace App\Http\Controllers;

use App\Mail\GroupInvitationMail;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    public function index () {
        $user = Auth::user();
        $groups = $user->groups;
        return view("groups", compact("user", "groups"));
    }

    public function store (Request $request) {
        $user = Auth::user();

        // Block normal users who already have an active group
        if (!$user->is_admin && count($user->active_groups)) {
            return redirect("/groups")->withErrors([
                "group" => "Vous êtes déjà membre d'un groupe actif. Vous devez quitter votre groupe actuel avant d'en créer un nouveau."
            ]);
        }

        $validated = $request->validate([
            "name" => "required"
        ], [
            "name.required" => "Le nom du groupe est obligatoire."
        ]);

        $user->groups()->create([
            "admin_id" => $user->id,
            "name" => $validated["name"],
        ]);

        return redirect("/groups")->with("success", "Groupe créé avec succès !");
    }

    public function show (Group $group) {
        $user = Auth::user();
        $categories = $group->categories;
        $activeMembers = $group->activeMembers()->get();
        $pastMembers = $group->pastMembers()->get();
        $allMembers = $group->allMembers()->get();
        
        // Find if the current user is a past member (left the group)
        $currentUserLeftAt = null;
        $currentUserPivot = $group->allMembers()->where('users.id', $user->id)->first();
        if ($currentUserPivot && $currentUserPivot->pivot->left_at) {
            $currentUserLeftAt = $currentUserPivot->pivot->left_at;
        }

        // Filter expenses for past members
        if ($currentUserLeftAt) {
            $expenses = $group->expenses()->where('created_at', '<=', $currentUserLeftAt)->with(['user', 'category'])->latest()->get();
        } else {
            $expenses = $group->expenses()->with(['user', 'category'])->latest()->get();
        }

        // Calculate who owes whom
        // Step 1: For each user (active AND past), compute net balance
        // net = total_paid - total_share + settlements_paid - settlements_received
        $balances = [];

        // Pre-fetch all settlements for this group to optimize
        $settlements = $group->settlements()->get();

        foreach ($allMembers as $member) {
            $totalPaid = $group->expenses()->where('user_id', $member->id)->sum('amount');
            $totalShare = $group->expenseShares()->where('user_id', $member->id)->sum('amount');
            
            $settlementsPaid = $settlements->where('from_user_id', $member->id)->sum('amount');
            $settlementsReceived = $settlements->where('to_user_id', $member->id)->sum('amount');
            
            $net = $totalPaid - $totalShare + $settlementsPaid - $settlementsReceived;
            
            $balances[$member->id] = [
                'user' => $member,
                'total_paid' => $totalPaid,
                'total_share' => $totalShare,
                'net' => $net, // positive = is owed money, negative = owes money
            ];
        }

        // Step 2: Build debts (who owes whom) using a greedy algorithm
        $creditors = [];
        $debtors = [];
        foreach ($balances as $userId => $data) {
            if ($data['net'] > 0.01) {
                $creditors[] = ['user' => $data['user'], 'amount' => $data['net']];
            } elseif ($data['net'] < -0.01) {
                $debtors[] = ['user' => $data['user'], 'amount' => abs($data['net'])];
            }
        }

        $adjustedDebts = [];
        $ci = 0;
        $di = 0;
        while ($ci < count($creditors) && $di < count($debtors)) {
            $transfer = min($creditors[$ci]['amount'], $debtors[$di]['amount']);
            if ($transfer > 0.01) {
                $adjustedDebts[] = [
                    'from' => $debtors[$di]['user'],
                    'to' => $creditors[$ci]['user'],
                    'amount' => round($transfer, 2),
                ];
            }
            $creditors[$ci]['amount'] -= $transfer;
            $debtors[$di]['amount'] -= $transfer;
            if ($creditors[$ci]['amount'] < 0.01) $ci++;
            if ($debtors[$di]['amount'] < 0.01) $di++;
        }

        return view("group", compact(
            "user", "group", "categories", "activeMembers", "pastMembers",
            "expenses", "balances", "adjustedDebts", "currentUserLeftAt"
        ));
    }

    public function leave(Group $group) {
        $user = Auth::user();

        // Verify user is currently an active member
        $isMember = $group->activeMembers()->where('users.id', $user->id)->exists();
        if (!$isMember) {
            return redirect()->back()->withErrors(['group' => "Vous n'êtes pas membre actif de ce groupe."]);
        }

        // Cannot leave if admin without transferring ownership (assuming simple structure here, admin just leaves)
        if ($group->admin_id === $user->id) {
            return redirect()->back()->withErrors(['group' => "L'administrateur ne peut pas quitter le groupe. Veuillez le supprimer ou transférer la propriété."]);
        }

        // Compute net balance 
        $totalPaid = $group->expenses()->where('user_id', $user->id)->sum('amount');
        $totalShare = $group->expenseShares()->where('user_id', $user->id)->sum('amount');
        $settlementsPaid = $group->settlements()->where('from_user_id', $user->id)->sum('amount');
        $settlementsReceived = $group->settlements()->where('to_user_id', $user->id)->sum('amount');
        $net = $totalPaid - $totalShare + $settlementsPaid - $settlementsReceived;

        // Update reputation
        if ($net < -0.01) {
            // User owes money
            $user->decrement('reputation');
        } else {
            // User is clean
            $user->increment('reputation');
        }

        // Mark as left
        $group->members()->updateExistingPivot($user->id, ['left_at' => now()]);

        return redirect("/dashboard")->with("success", "Vous avez quitté le groupe '{$group->name}' !");
    }

    public function fire(Request $request, Group $group, \App\Models\User $member) {
        $user = Auth::user();

        // Only admin can fire
        if ($group->admin_id !== $user->id) {
            return redirect()->back()->withErrors(['group' => "Seul l'administrateur peut exclure un membre."]);
        }

        // Cannot fire oneself
        if ($member->id === $user->id) {
            return redirect()->back()->withErrors(['group' => "Vous ne pouvez pas vous exclure vous-même."]);
        }

        // Verify target is an active member
        $isMember = $group->activeMembers()->where('users.id', $member->id)->exists();
        if (!$isMember) {
            return redirect()->back()->withErrors(['group' => "Cet utilisateur n'est pas un membre actif du groupe."]);
        }

        // Calculate member's net balance before firing
        $totalPaid = $group->expenses()->where('user_id', $member->id)->sum('amount');
        $totalShare = $group->expenseShares()->where('user_id', $member->id)->sum('amount');
        $settlementsPaid = $group->settlements()->where('from_user_id', $member->id)->sum('amount');
        $settlementsReceived = $group->settlements()->where('to_user_id', $member->id)->sum('amount');
        $net = $totalPaid - $totalShare + $settlementsPaid - $settlementsReceived;

        // If member owes money, ADMIN absorbs the debt by creating a settlement from fired member to admin
        if ($net < -0.01) {
            $group->settlements()->create([
                'from_user_id' => $member->id,
                'to_user_id' => $user->id,
                'amount' => abs($net),
            ]);
            // Fired member leaves with negative reputation
            $member->decrement('reputation');
        } else {
            // Clean slate
            $member->increment('reputation');
        }

        // Mark as left
        $group->members()->updateExistingPivot($member->id, ['left_at' => now()]);

        return redirect("/groups/{$group->id}")->with("success", "Le membre {$member->first_name} a été exclu du groupe.");
    }

    public function invite (Request $request, Group $group) {
        $validated = $request->validate([
            "email" => "required|email"
        ], [
            "email.required" => "L'adresse email est obligatoire.",
            "email.email" => "Veuillez entrer une adresse email valide.",
        ]);

        $token = Str::random(40);
        $group->tokens()->create([
            "token" => $token,
            "expire_at" => now()->addHour()
        ]);

        $inviteUrl = url("/groups/{$group->id}/join/{$token}");
        $inviterName = Auth::user()->first_name . ' ' . Auth::user()->last_name;

        Mail::to($validated["email"])->send(new GroupInvitationMail(
            groupName: $group->name,
            inviteUrl: $inviteUrl,
            inviterName: $inviterName
        ));

        return redirect()->back()->with("success", "Invitation envoyée par email à {$validated['email']} !");
    }

    public function join_page (Request $request, Group $group, string $token) {
        $user = Auth::user();

        $group_token = $group->tokens()
              ->where('token', $token)
              ->where ('expire_at', ">=", now())
              ->first();

        if (!$group_token) return view("404");

        // Check if user is already an active member
        $alreadyMember = $group->activeMembers()->where('users.id', $user->id)->exists();

        return view("join", compact("user", "group", "token", "alreadyMember"));
    }

    public function join (Request $request, Group $group) {
        $token = $request->token;

        $group_token = $group->tokens()
              ->where('token', $token)
              ->where ('expire_at', ">=", now())
              ->first();

        if (!$group_token) {
            return redirect("/groups")->withErrors([
                "join" => "Le lien d'invitation est invalide ou a expiré."
            ]);
        }

        $user = Auth::user();

        // Check if the user is already an active member of this group
        $alreadyMember = $group->activeMembers()->where('users.id', $user->id)->exists();
        if ($alreadyMember) {
            return redirect("/groups")->withErrors([
                "join" => "Vous êtes déjà membre de ce groupe."
            ]);
        }

        // Check if normal user already has an active group
        if (!$user->is_admin && count($user->active_groups)) {
            return redirect("/groups")->withErrors([
                "join" => "Vous êtes déjà membre d'un groupe actif. Vous devez quitter votre groupe actuel avant d'en rejoindre un nouveau."
            ]);
        }

        // Attach user to group and destroy the token
        $user->groups()->attach($group);
        $group_token->delete();

        return redirect("/groups")->with("success", "Vous avez rejoint le groupe \"{$group->name}\" avec succès !");
    }
}
