<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\OptionsGeneral;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminConfigTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_admin_config_page_requires_authentication()
    {
        $response = $this->get('/admin/config');
        
        $response->assertRedirect('/login');
    }

    public function test_admin_config_page_is_accessible_for_authenticated_user()
    {
        $response = $this->actingAs($this->user)->get('/admin/config');

        $response->assertStatus(200);
        $response->assertViewIs('admin.config');
    }

    public function test_admin_general_settings_can_be_updated()
    {
        $generalOptions = OptionsGeneral::create([
            'mods_enabled' => false,
            'file_verification' => false,
            'embedded_java' => false,
            'game_folder_name' => 'old_folder',
            'min_ram' => 2048,
            'max_ram' => 4096,
        ]);

        $response = $this->actingAs($this->user)->post('/admin/general/update', [
            'mods_enabled' => true,
            'file_verification' => true,
            'embedded_java' => true,
            'game_folder_name' => 'new_game_folder',
            'min_ram' => 4096,
            'max_ram' => 8192,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('options_general', [
            'mods_enabled' => true,
            'file_verification' => true,
            'embedded_java' => true,
            'game_folder_name' => 'new_game_folder',
            'min_ram' => 4096,
            'max_ram' => 8192,
        ]);
    }

    public function test_admin_general_page_displays_current_settings()
    {
        OptionsGeneral::create([
            'mods_enabled' => true,
            'file_verification' => false,
            'game_folder_name' => 'minecraft',
            'min_ram' => 2048,
            'max_ram' => 4096,
        ]);

        $response = $this->actingAs($this->user)->get('/admin/general');

        $response->assertStatus(200);
        $response->assertSee('minecraft');
        $response->assertSee('2048');
        $response->assertSee('4096');
    }

    public function test_admin_general_update_validates_ram_values()
    {
        $response = $this->actingAs($this->user)->post('/admin/general/update', [
            'min_ram' => -1000,
            'max_ram' => 'invalid',
            'game_folder_name' => '',
        ]);

        $response->assertSessionHasErrors(['min_ram', 'max_ram', 'game_folder_name']);
    }

    public function test_admin_general_update_ensures_min_ram_less_than_max_ram()
    {
        $response = $this->actingAs($this->user)->post('/admin/general/update', [
            'min_ram' => 8192,
            'max_ram' => 4096,
            'game_folder_name' => 'test',
        ]);

        $response->assertSessionHasErrors(['min_ram']);
    }
}