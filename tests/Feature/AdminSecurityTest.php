<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\OptionsSecurity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_security_page_requires_authentication()
    {
        $response = $this->get('/admin/security');
        
        $response->assertRedirect('/login');
    }

    public function test_security_page_is_accessible_for_authenticated_user()
    {
        $response = $this->actingAs($this->user)->get('/admin/security');

        $response->assertStatus(200);
        $response->assertViewIs('admin.security');
    }

    public function test_security_settings_can_be_updated()
    {
        $response = $this->actingAs($this->user)->post('/admin/security/update', [
            'enable_whitelist' => true,
            'auto_update' => false,
            'backup_enabled' => true,
            'max_login_attempts' => 5,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('options_security', [
            'enable_whitelist' => true,
            'auto_update' => false,
            'backup_enabled' => true,
            'max_login_attempts' => 5,
        ]);
    }

    public function test_security_update_validates_max_login_attempts()
    {
        $response = $this->actingAs($this->user)->post('/admin/security/update', [
            'max_login_attempts' => -1,
        ]);

        $response->assertSessionHasErrors(['max_login_attempts']);
    }

    public function test_security_update_validates_max_login_attempts_upper_limit()
    {
        $response = $this->actingAs($this->user)->post('/admin/security/update', [
            'max_login_attempts' => 1000,
        ]);

        $response->assertSessionHasErrors(['max_login_attempts']);
    }
}
