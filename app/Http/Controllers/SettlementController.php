<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettlementController extends Controller
{
    public function store(Request $request, Group $group)
    {
        $user = Auth::user();

        // Verify user is an active member of this group
        $isMember = $group->activeMembers()->where('users.id', $user->id)->exists();
        if (!$isMember) {
            return redirect()->back()->withErrors(['settlement' => "Vous n'êtes pas membre actif de ce groupe."]);
        }

        $validated = $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
        ], [
            'to_user_id.required' => "Le destinataire est obligatoire.",
            'amount.required' => 'Le montant est obligatoire.',
            'amount.numeric' => 'Le montant doit être un nombre.',
            'amount.min' => 'Le montant doit être supérieur à 0.',
        ]);

        // Verify the target user is also an active member
        $targetIsMember = $group->activeMembers()->where('users.id', $validated['to_user_id'])->exists();
        if (!$targetIsMember) {
            return redirect()->back()->withErrors(['settlement' => "Le destinataire n'est pas membre actif de ce groupe."]);
        }

        // Cannot settle with yourself
        if ($user->id == $validated['to_user_id']) {
            return redirect()->back()->withErrors(['settlement' => 'Vous ne pouvez pas régler une dette envers vous-même.']);
        }

        Settlement::create([
            'from_user_id' => $user->id,
            'to_user_id' => $validated['to_user_id'],
            'group_id' => $group->id,
            'amount' => $validated['amount'],
        ]);

        return redirect("/groups/{$group->id}")->with('success', 'Règlement enregistré avec succès !');
    }
}
