<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $householdUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role_type' => 'Admin',
            'status'    => 'active',
        ]);

        $this->householdUser = User::factory()->create([
            'role_type' => 'Household',
            'status'    => 'active',
        ]);
    }

    public function test_admin_can_view_users_index(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->get(route('admin.users.index'));

        $response->assertOk();
        $response->assertSee($this->householdUser->name);
        $response->assertSee($this->householdUser->email);
    }

    public function test_admin_can_suspend_and_reactivate_a_user_account(): void
    {
        // Suspend user
        $response = $this
            ->actingAs($this->admin)
            ->patch(route('admin.users.toggle-status', $this->householdUser));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');
        $this->assertTrue($this->householdUser->fresh()->isSuspended());

        // Reactivate user
        $response2 = $this
            ->actingAs($this->admin)
            ->patch(route('admin.users.toggle-status', $this->householdUser));

        $response2->assertRedirect(route('admin.users.index'));
        $response2->assertSessionHas('success');
        $this->assertTrue($this->householdUser->fresh()->isActive());
    }

    public function test_admin_cannot_suspend_their_own_account(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->patch(route('admin.users.toggle-status', $this->admin));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('error', 'You cannot suspend your own account.');
        $this->assertTrue($this->admin->fresh()->isActive());
    }

    public function test_admin_can_delete_a_user_account(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->delete(route('admin.users.destroy', $this->householdUser));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $this->householdUser->id]);
    }

    public function test_admin_cannot_delete_their_own_account(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->delete(route('admin.users.destroy', $this->admin));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('error', 'You cannot delete your own account.');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    public function test_suspended_user_cannot_login(): void
    {
        $this->householdUser->update(['status' => 'suspended']);

        $response = $this->post('/login', [
            'email' => $this->householdUser->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_suspended_user_active_session_is_terminated_by_middleware(): void
    {
        $this->householdUser->update(['status' => 'suspended']);

        $response = $this
            ->actingAs($this->householdUser)
            ->get(route('household.dashboard'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_non_admin_cannot_access_user_management(): void
    {
        $response = $this
            ->actingAs($this->householdUser)
            ->get(route('admin.users.index'));

        $response->assertStatus(403);
    }
}
