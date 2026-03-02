<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Expense;
use App\Models\Group;
use App\Models\Settlement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseAndSettlementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_expense_and_shares_are_calculated()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $member1 = User::factory()->create();
        $member2 = User::factory()->create();

        $group = Group::create(['name' => 'Test Group', 'admin_id' => $admin->id]);
        $group->members()->attach([$admin->id, $member1->id, $member2->id]);

        $category = Category::create(['name' => 'Food', 'group_id' => $group->id]);

        $response = $this->actingAs($admin)->post("/groups/{$group->id}/expenses", [
            'title' => 'Groceries',
            'amount' => 90,
            'category_id' => $category->id
        ]);

        $response->assertRedirect("/groups/{$group->id}");
        $this->assertDatabaseHas('expenses', [
            'title' => 'Groceries',
            'amount' => 90,
            'user_id' => $admin->id,
            'group_id' => $group->id
        ]);

        // Verify shares were created (90 / 3 active members = 30 each)
        $this->assertDatabaseCount('group_expense_shares', 3);
        $this->assertDatabaseHas('group_expense_shares', [
            'user_id' => $admin->id,
            'amount' => 30
        ]);
        $this->assertDatabaseHas('group_expense_shares', [
            'user_id' => $member1->id,
            'amount' => 30
        ]);
        $this->assertDatabaseHas('group_expense_shares', [
            'user_id' => $member2->id,
            'amount' => 30
        ]);
    }

    public function test_user_can_delete_expense_and_shares_are_deleted()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $group = Group::create(['name' => 'Test Group', 'admin_id' => $admin->id]);
        $group->members()->attach($admin->id);

        $expense = Expense::create([
            'title' => 'Test',
            'amount' => 100,
            'user_id' => $admin->id,
            'group_id' => $group->id
        ]);
        $expense->shares()->create([
            'user_id' => $admin->id,
            'group_id' => $group->id,
            'amount' => 100
        ]);

        $this->assertDatabaseCount('expenses', 1);
        $this->assertDatabaseCount('group_expense_shares', 1);

        $response = $this->actingAs($admin)->delete("/groups/{$group->id}/expenses/{$expense->id}");

        $response->assertRedirect("/groups/{$group->id}");
        $this->assertDatabaseCount('expenses', 0);
        $this->assertDatabaseCount('group_expense_shares', 0);
    }

    public function test_user_can_create_settlement()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $member = User::factory()->create();

        $group = Group::create(['name' => 'Test Group', 'admin_id' => $admin->id]);
        $group->members()->attach([$admin->id, $member->id]);

        $response = $this->actingAs($member)->post("/groups/{$group->id}/settlements", [
            'to_user_id' => $admin->id,
            'amount' => 50
        ]);

        $response->assertRedirect("/groups/{$group->id}");
        $this->assertDatabaseHas('settlements', [
            'from_user_id' => $member->id,
            'to_user_id' => $admin->id,
            'group_id' => $group->id,
            'amount' => 50
        ]);
    }
}
