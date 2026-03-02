<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Expense;
use App\Models\Group;
use App\Models\Settlement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupLeaveAndReputationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_leaves_clean_gets_positive_reputation()
    {
        $admin = User::factory()->create(['is_admin' => true, 'reputation' => 0]);
        $member = User::factory()->create(['reputation' => 0]);

        $group = Group::create(['name' => 'Clean Group', 'admin_id' => $admin->id]);
        $group->members()->attach([$admin->id, $member->id]);

        // Assert member reputation is 0 initially
        $this->assertEquals(0, $member->fresh()->reputation);

        // Member leaves the group
        $response = $this->actingAs($member)->post("/groups/{$group->id}/leave");
        
        $response->assertRedirect("/dashboard");
        
        // Assert reputation increased because net balance is 0
        $this->assertEquals(1, $member->fresh()->reputation);
        
        // Assert left_at is updated
        $this->assertNotNull($group->allMembers()->where('users.id', $member->id)->first()->pivot->left_at);
    }

    public function test_user_leaves_with_debt_gets_negative_reputation()
    {
        $admin = User::factory()->create(['is_admin' => true, 'reputation' => 0]);
        $member = User::factory()->create(['reputation' => 0]);

        $group = Group::create(['name' => 'Debt Group', 'admin_id' => $admin->id]);
        $group->members()->attach([$admin->id, $member->id]);
        
        // Admin pays for an expense
        $expense = Expense::create([
            'title' => 'Test', 
            'amount' => 100, 
            'user_id' => $admin->id, 
            'group_id' => $group->id
        ]);
        
        // Each owes 50
        $expense->shares()->create(['user_id' => $admin->id, 'group_id' => $group->id, 'amount' => 50]);
        $expense->shares()->create(['user_id' => $member->id, 'group_id' => $group->id, 'amount' => 50]);

        // Member leaves without paying debt
        $response = $this->actingAs($member)->post("/groups/{$group->id}/leave");
        
        $response->assertRedirect("/dashboard");
        
        // Assert reputation decreased because net balance is -50
        $this->assertEquals(-1, $member->fresh()->reputation);
    }
    
    public function test_admin_fires_member_and_absorbs_debt()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $member1 = User::factory()->create(); // Debtor
        $member2 = User::factory()->create(); // Creditor

        $group = Group::create(['name' => 'Fire Group', 'admin_id' => $admin->id]);
        $group->members()->attach([$admin->id, $member1->id, $member2->id]);
        
        // Member 2 pays for an expense of 90 (30 each)
        $expense = Expense::create([
            'title' => 'Dinner', 
            'amount' => 90, 
            'user_id' => $member2->id, 
            'group_id' => $group->id
        ]);
        
        $expense->shares()->create(['user_id' => $admin->id, 'group_id' => $group->id, 'amount' => 30]);
        $expense->shares()->create(['user_id' => $member1->id, 'group_id' => $group->id, 'amount' => 30]);
        $expense->shares()->create(['user_id' => $member2->id, 'group_id' => $group->id, 'amount' => 30]);

        // Member 1 owes 30 to Member 2.
        
        // Admin fires Member 1
        $response = $this->actingAs($admin)->post("/groups/{$group->id}/members/{$member1->id}/fire");
        
        $response->assertRedirect("/groups/{$group->id}");
        
        // Assert member 1 was fired and lost reputation
        $this->assertNotNull($group->allMembers()->where('users.id', $member1->id)->first()->pivot->left_at);
        $this->assertEquals(-1, $member1->fresh()->reputation);
        
        // Assert a settlement was automatically created from member 1 to admin for 30
        $this->assertDatabaseHas('settlements', [
            'from_user_id' => $member1->id,
            'to_user_id' => $admin->id,
            'amount' => 30,
            'group_id' => $group->id
        ]);
    }

    public function test_balances_net_to_zero_after_settlements()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $member = User::factory()->create();

        $group = Group::create(['name' => 'Balance Group', 'admin_id' => $admin->id]);
        $group->members()->attach([$admin->id, $member->id]);
        
        // Admin pays 100
        $expense = Expense::create(['title' => 'A', 'amount' => 100, 'user_id' => $admin->id, 'group_id' => $group->id]);
        $expense->shares()->create(['user_id' => $admin->id, 'group_id' => $group->id, 'amount' => 50]);
        $expense->shares()->create(['user_id' => $member->id, 'group_id' => $group->id, 'amount' => 50]);

        // Member pays 50 back via settlement
        Settlement::create([
            'from_user_id' => $member->id,
            'to_user_id' => $admin->id,
            'amount' => 50,
            'group_id' => $group->id
        ]);

        // Net balance for member should be 0: totalPaid(0) - totalShare(50) + settlementsPaid(50) - settlementsReceived(0) = 0
        // Net balance for admin should be 0: totalPaid(100) - totalShare(50) + settlementsPaid(0) - settlementsReceived(50) = 0
        
        $response = $this->actingAs($admin)->get("/groups/{$group->id}");
        $response->assertStatus(200);
        
        $viewData = $response->original->getData();
        $balances = $viewData['balances'];
        
        $this->assertEquals(0, $balances[$admin->id]['net']);
        $this->assertEquals(0, $balances[$member->id]['net']);
    }
}
