<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Group;
use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index () {
        $user = Auth::user();

        // User's active groups with member counts
        $activeGroups = $user->active_groups()->withCount(['activeMembers'])->get();

        // Total expenses the user has paid across all groups
        $totalExpensesPaid = $user->expenses()->sum('amount');

        // Total expenses shared with the user
        $totalExpensesShared = \App\Models\GroupExpenseShare::where('user_id', $user->id)->sum('amount');

        // Outstanding debts across all active groups
        $debts = [];
        $credits = [];
        foreach ($activeGroups as $group) {
            $totalPaid = $group->expenses()->where('user_id', $user->id)->sum('amount');
            $totalShare = $group->expenseShares()->where('user_id', $user->id)->sum('amount');
            $settlementsPaid = $group->settlements()->where('from_user_id', $user->id)->sum('amount');
            $settlementsReceived = $group->settlements()->where('to_user_id', $user->id)->sum('amount');
            $net = $totalPaid - $totalShare + $settlementsPaid - $settlementsReceived;

            if ($net < -0.01) {
                $debts[] = ['group' => $group, 'amount' => abs($net)];
            } elseif ($net > 0.01) {
                $credits[] = ['group' => $group, 'amount' => $net];
            }
        }

        $totalDebt = collect($debts)->sum('amount');
        $totalCredit = collect($credits)->sum('amount');

        // Recent expenses across all user's active groups
        $groupIds = $activeGroups->pluck('id');
        $recentExpenses = Expense::whereIn('group_id', $groupIds)
            ->with(['user', 'group', 'category'])
            ->latest()
            ->take(5)
            ->get();

        // Recent settlements
        $recentSettlements = Settlement::where(function ($q) use ($user) {
            $q->where('from_user_id', $user->id)
              ->orWhere('to_user_id', $user->id);
        })->whereIn('group_id', $groupIds)
          ->with(['fromUser', 'toUser', 'group'])
          ->latest()
          ->take(5)
          ->get();

        return view("dashboard", compact(
            "user", "activeGroups", "totalExpensesPaid", "totalExpensesShared",
            "totalDebt", "totalCredit", "debts", "credits",
            "recentExpenses", "recentSettlements"
        ));
    }
}
