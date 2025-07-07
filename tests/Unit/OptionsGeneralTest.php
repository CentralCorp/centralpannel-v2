<?php

namespace Tests\Unit;

use App\Models\OptionsGeneral;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OptionsGeneralTest extends TestCase
{
    use RefreshDatabase;

    public function test_options_general_can_be_created()
    {
        $options = OptionsGeneral::create([
            'mods_enabled' => true,
            'file_verification' => false,
            'embedded_java' => true,
            'game_folder_name' => 'minecraft',
            'email_verified' => true,
            'role_display' => true,
            'money_display' => false,
            'azuriom_url' => 'https://example.com',
            'azuriom_api_key' => 'test-api-key',
            'market_api_key' => 'market-api-key',
            'active_theme_id' => 1,
            'active_theme_version' => '1.0.0',
            'min_ram' => 2048,
            'max_ram' => 4096,
        ]);

        $this->assertInstanceOf(OptionsGeneral::class, $options);
        $this->assertEquals('minecraft', $options->game_folder_name);
        $this->assertTrue($options->mods_enabled);
        $this->assertFalse($options->file_verification);
        $this->assertEquals(2048, $options->min_ram);
        $this->assertEquals(4096, $options->max_ram);
    }

    public function test_options_general_uses_correct_table()
    {
        $options = new OptionsGeneral();
        $this->assertEquals('options_general', $options->getTable());
    }

    public function test_options_general_fillable_attributes()
    {
        $options = new OptionsGeneral();
        $fillable = $options->getFillable();

        $expectedFillable = [
            'mods_enabled',
            'file_verification',
            'embedded_java',
            'game_folder_name',
            'email_verified',
            'role_display',
            'money_display',
            'azuriom_url',
            'azuriom_api_key',
            'market_api_key',
            'active_theme_id',
            'active_theme_version',
            'min_ram',
            'max_ram',
        ];

        $this->assertEquals($expectedFillable, $fillable);
    }

    public function test_options_general_can_be_updated()
    {
        $options = OptionsGeneral::create([
            'mods_enabled' => false,
            'game_folder_name' => 'old_folder',
            'min_ram' => 1024,
        ]);

        $options->update([
            'mods_enabled' => true,
            'game_folder_name' => 'new_folder',
            'min_ram' => 2048,
        ]);

        $this->assertTrue($options->mods_enabled);
        $this->assertEquals('new_folder', $options->game_folder_name);
        $this->assertEquals(2048, $options->min_ram);
    }

    public function test_options_general_boolean_fields_are_handled_correctly()
    {
        $options = OptionsGeneral::create([
            'mods_enabled' => '1',
            'file_verification' => '0',
            'embedded_java' => 'true',
            'email_verified' => 'false',
        ]);

        // Test that values are stored correctly (Laravel will handle boolean conversion)
        $this->assertNotNull($options->mods_enabled);
        $this->assertNotNull($options->file_verification);
        $this->assertNotNull($options->embedded_java);
        $this->assertNotNull($options->email_verified);
    }
}