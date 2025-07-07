<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\OptionsGeneral;
use App\Models\OptionsServer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_admin_dashboard_requires_authentication()
    {
        $response = $this->get('/admin');
        
        $response->assertRedirect('/login');
    }

    public function test_admin_dashboard_is_accessible_for_authenticated_user()
    {
        $response = $this->actingAs($this->user)->get('/admin');

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    public function test_admin_dashboard_displays_system_information()
    {
        // Create some test data
        OptionsGeneral::create([
            'mods_enabled' => true,
            'game_folder_name' => 'minecraft',
            'min_ram' => 2048,
            'max_ram' => 4096,
        ]);

        OptionsServer::create([
            'server_name' => 'Test Server',
            'server_ip' => '127.0.0.1',
            'server_port' => 25565,
        ]);

        $response = $this->actingAs($this->user)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Test Server');
        $response->assertSee('minecraft');
    }

    public function test_admin_dashboard_shows_navigation_links()
    {
        $response = $this->actingAs($this->user)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Configuration');
        $response->assertSee('Général');
        $response->assertSee('Sécurité');
        $response->assertSee('Serveur');
        $response->assertSee('Whitelist');
        $response->assertSee('Utilisateurs');
    }

    public function test_admin_dashboard_displays_user_info()
    {
        $response = $this->actingAs($this->user)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee($this->user->name);
        $response->assertSee($this->user->email);
    }

    public function test_admin_dashboard_handles_missing_configurations()
    {
        // Don't create any configuration data
        $response = $this->actingAs($this->user)->get('/admin');

        // Should still render successfully
        $response->assertStatus(200);
    }

    public function test_admin_config_redirect_works()
    {
        $response = $this->actingAs($this->user)->get('/admin/config');

        $response->assertStatus(200);
        $response->assertViewIs('admin.config');
    }

    public function test_file_manager_is_accessible()
    {
        $response = $this->actingAs($this->user)->get('/file-manager');

        $response->assertStatus(200);
        $response->assertViewIs('admin.file-manager');
    }

    public function test_admin_routes_are_protected_by_auth_middleware()
    {
        $protectedRoutes = [
            '/admin/general',
            '/admin/security',
            '/admin/server',
            '/admin/whitelist',
            '/admin/mods',
            '/admin/users',
            '/admin/rpc',
            '/admin/ui',
            '/admin/loader',
            '/admin/ignore',
            '/admin/bg',
            '/admin/theme',
        ];

        foreach ($protectedRoutes as $route) {
            $response = $this->get($route);
            $response->assertRedirect('/login');
        }
    }
}
