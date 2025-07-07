<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\OptionsServer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminServerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_server_page_requires_authentication()
    {
        $response = $this->get('/admin/server');
        
        $response->assertRedirect('/login');
    }

    public function test_server_page_is_accessible_for_authenticated_user()
    {
        $response = $this->actingAs($this->user)->get('/admin/server');

        $response->assertStatus(200);
        $response->assertViewIs('admin.server');
    }

    public function test_server_settings_can_be_updated()
    {
        $response = $this->actingAs($this->user)->post('/admin/server/update', [
            'server_name' => 'My Minecraft Server',
            'server_ip' => '192.168.1.100',
            'server_port' => 25565,
            'server_description' => 'A great Minecraft server',
            'max_players' => 50,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('options_server', [
            'server_name' => 'My Minecraft Server',
            'server_ip' => '192.168.1.100',
            'server_port' => 25565,
            'server_description' => 'A great Minecraft server',
            'max_players' => 50,
        ]);
    }

    public function test_server_update_validates_required_fields()
    {
        $response = $this->actingAs($this->user)->post('/admin/server/update', [
            'server_name' => '',
            'server_ip' => '',
            'server_port' => '',
        ]);

        $response->assertSessionHasErrors(['server_name', 'server_ip', 'server_port']);
    }

    public function test_server_update_validates_port_range()
    {
        $response = $this->actingAs($this->user)->post('/admin/server/update', [
            'server_name' => 'Test Server',
            'server_ip' => '127.0.0.1',
            'server_port' => 99999,
        ]);

        $response->assertSessionHasErrors(['server_port']);
    }

    public function test_server_update_validates_ip_format()
    {
        $response = $this->actingAs($this->user)->post('/admin/server/update', [
            'server_name' => 'Test Server',
            'server_ip' => 'invalid-ip',
            'server_port' => 25565,
        ]);

        $response->assertSessionHasErrors(['server_ip']);
    }

    public function test_server_can_be_set_as_default()
    {
        $server = OptionsServer::create([
            'server_name' => 'Test Server',
            'server_ip' => '127.0.0.1',
            'server_port' => 25565,
            'is_default' => false,
        ]);

        $response = $this->actingAs($this->user)->post('/admin/server/set-default', [
            'server_id' => $server->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('options_server', [
            'id' => $server->id,
            'is_default' => true,
        ]);
    }
}
