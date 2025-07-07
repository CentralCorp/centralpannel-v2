<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\OptionsGeneral;
use App\Models\OptionsMods;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_api_options_endpoint_returns_data()
    {
        // Create some test data
        OptionsGeneral::create([
            'mods_enabled' => true,
            'file_verification' => false,
            'game_folder_name' => 'minecraft',
            'min_ram' => 2048,
            'max_ram' => 4096,
        ]);

        $response = $this->get('/utils/api');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'mods_enabled',
            'file_verification', 
            'game_folder_name',
            'min_ram',
            'max_ram',
        ]);
    }

    public function test_api_mods_endpoint_returns_mods_data()
    {
        $response = $this->get('/utils/mods');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'name',
                'version',
                'enabled',
            ]
        ]);
    }

    public function test_api_files_endpoint_returns_files_data()
    {
        $response = $this->get('/data');

        $response->assertStatus(200);
        // Test that it returns some kind of file structure
        $this->assertTrue(is_array($response->json()) || is_object($response->json()));
    }

    public function test_api_options_contains_expected_boolean_values()
    {
        OptionsGeneral::create([
            'mods_enabled' => true,
            'file_verification' => false,
        ]);

        $response = $this->get('/utils/api');

        $response->assertStatus(200);
        $data = $response->json();
        
        $this->assertTrue($data['mods_enabled']);
        $this->assertFalse($data['file_verification']);
    }

    public function test_api_handles_missing_options_gracefully()
    {
        // Don't create any options data
        $response = $this->get('/utils/api');

        // Should still return a successful response
        $response->assertStatus(200);
    }

    public function test_api_options_returns_correct_data_types()
    {
        OptionsGeneral::create([
            'mods_enabled' => true,
            'min_ram' => 2048,
            'max_ram' => 4096,
            'game_folder_name' => 'minecraft',
        ]);

        $response = $this->get('/utils/api');
        $data = $response->json();

        // Check data types
        $this->assertIsBool($data['mods_enabled']);
        $this->assertIsInt($data['min_ram']);
        $this->assertIsInt($data['max_ram']);
        $this->assertIsString($data['game_folder_name']);
    }
}
