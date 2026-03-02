<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Group;
use App\Models\Settlement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Platform stats
        $totalUsers = User::count();
        $totalGroups = Group::count();
        $activeGroups = Group::whereHas('activeMembers')->count();
        $totalExpenses = Expense::sum('amount');
        $totalSettlements = Settlement::sum('amount');
        $bannedUsersCount = User::where('is_banned', true)->count();

        // Recent signups
        $recentUsers = User::latest()->take(10)->get();

        // All users for management table
        $users = User::withCount(['groups', 'active_groups', 'expenses'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin', compact(
            'user', 'totalUsers', 'totalGroups', 'activeGroups',
            'totalExpenses', 'totalSettlements', 'bannedUsersCount',
            'recentUsers', 'users'
        ));
    }

    public function ban(User $targetUser)
    {
        $admin = Auth::user();

        // Cannot ban yourself
        if ($admin->id === $targetUser->id) {
            return back()->withErrors(['admin' => 'Vous ne pouvez pas vous bannir vous-même.']);
        }

        // Cannot ban another admin
        if ($targetUser->is_admin) {
            return back()->withErrors(['admin' => 'Vous ne pouvez pas bannir un administrateur.']);
        }

        $targetUser->update(['is_banned' => true]);

        return back()->with('success', "L'utilisateur {$targetUser->first_name} {$targetUser->last_name} a été banni.");
    }

    public function unban(User $targetUser)
    {
        $targetUser->update(['is_banned' => false]);

        return back()->with('success', "L'utilisateur {$targetUser->first_name} {$targetUser->last_name} a été débanni.");
    }
}
