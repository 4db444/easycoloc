<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Group;
use App\Models\GroupExpenseShare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function store(Request $request, Group $group)
    {
        $user = Auth::user();

        // Verify user is an active member of this group
        $isMember = $group->activeMembers()->where('users.id', $user->id)->exists();
        if (!$isMember) {
            return redirect()->back()->withErrors(['expense' => "Vous n'êtes pas membre actif de ce groupe."]);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'nullable|exists:categories,id',
        ], [
            'title.required' => 'Le titre est obligatoire.',
            'amount.required' => 'Le montant est obligatoire.',
            'amount.numeric' => 'Le montant doit être un nombre.',
            'amount.min' => 'Le montant doit être supérieur à 0.',
        ]);

        // If category_id is provided, verify it belongs to this group
        if (!empty($validated['category_id'])) {
            $categoryBelongsToGroup = $group->categories()->where('id', $validated['category_id'])->exists();
            if (!$categoryBelongsToGroup) {
                return redirect()->back()->withErrors(['expense' => 'Cette catégorie ne fait pas partie de ce groupe.']);
            }
        }

        // Create the expense
        $expense = $group->expenses()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'amount' => $validated['amount'],
            'user_id' => $user->id,
            'category_id' => $validated['category_id'] ?? null,
        ]);

        // Split expense among active members
        $activeMembers = $group->activeMembers()->get();
        $memberCount = $activeMembers->count();

        if ($memberCount > 0) {
            $shareAmount = round($validated['amount'] / $memberCount, 2);

            foreach ($activeMembers as $member) {
                GroupExpenseShare::create([
                    'user_id' => $member->id,
                    'expense_id' => $expense->id,
                    'group_id' => $group->id,
                    'amount' => $shareAmount,
                ]);
            }
        }

        return redirect("/groups/{$group->id}")->with('success', 'Dépense ajoutée avec succès !');
    }

    public function destroy(Group $group, Expense $expense)
    {
        $user = Auth::user();

        // Only the expense creator or the group admin can delete
        if ($user->id !== $expense->user_id && $user->id !== $group->admin_id) {
            return redirect()->back()->withErrors(['expense' => "Vous n'avez pas la permission de supprimer cette dépense."]);
        }

        // Shares will be cascade-deleted automatically
        $expense->delete();

        return redirect("/groups/{$group->id}")->with('success', 'Dépense supprimée avec succès !');
    }
}
