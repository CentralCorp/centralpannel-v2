<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\OptionsWhitelist;
use App\Models\OptionsWhitelistRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminWhitelistTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_whitelist_page_requires_authentication()
    {
        $response = $this->get('/admin/whitelist');
        
        $response->assertRedirect('/login');
    }

    public function test_whitelist_page_is_accessible_for_authenticated_user()
    {
        $response = $this->actingAs($this->user)->get('/admin/whitelist');

        $response->assertStatus(200);
        $response->assertViewIs('admin.whitelist');
    }

    public function test_user_can_be_added_to_whitelist()
    {
        $response = $this->actingAs($this->user)->post('/admin/whitelist', [
            'type' => 'user',
            'username' => 'TestPlayer',
            'uuid' => '12345678-1234-1234-1234-123456789abc',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('options_whitelist', [
            'username' => 'TestPlayer',
            'uuid' => '12345678-1234-1234-1234-123456789abc',
        ]);
    }

    public function test_role_can_be_added_to_whitelist()
    {
        $response = $this->actingAs($this->user)->post('/admin/whitelist', [
            'type' => 'role',
            'role_name' => 'VIP',
            'role_id' => 123,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('options_whitelist_role', [
            'role_name' => 'VIP',
            'role_id' => 123,
        ]);
    }

    public function test_whitelist_user_validates_required_fields()
    {
        $response = $this->actingAs($this->user)->post('/admin/whitelist', [
            'type' => 'user',
            'username' => '',
            'uuid' => '',
        ]);

        $response->assertSessionHasErrors(['username', 'uuid']);
    }

    public function test_whitelist_role_validates_required_fields()
    {
        $response = $this->actingAs($this->user)->post('/admin/whitelist', [
            'type' => 'role',
            'role_name' => '',
            'role_id' => '',
        ]);

        $response->assertSessionHasErrors(['role_name', 'role_id']);
    }

    public function test_user_can_be_removed_from_whitelist()
    {
        $whitelistUser = OptionsWhitelist::create([
            'username' => 'TestPlayer',
            'uuid' => '12345678-1234-1234-1234-123456789abc',
        ]);

        $response = $this->actingAs($this->user)->delete("/admin/whitelist/user/{$whitelistUser->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('options_whitelist', [
            'id' => $whitelistUser->id,
        ]);
    }

    public function test_role_can_be_removed_from_whitelist()
    {
        $whitelistRole = OptionsWhitelistRole::create([
            'role_name' => 'VIP',
            'role_id' => 123,
        ]);

        $response = $this->actingAs($this->user)->delete("/admin/whitelist/role/{$whitelistRole->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('options_whitelist_role', [
            'id' => $whitelistRole->id,
        ]);
    }

    public function test_cannot_remove_non_existent_whitelist_user()
    {
        $response = $this->actingAs($this->user)->delete('/admin/whitelist/user/999');

        $response->assertStatus(404);
    }

    public function test_cannot_remove_non_existent_whitelist_role()
    {
        $response = $this->actingAs($this->user)->delete('/admin/whitelist/role/999');

        $response->assertStatus(404);
    }
}
