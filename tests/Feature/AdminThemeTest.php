<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AdminThemeTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Storage::fake('public');
    }

    public function test_theme_page_requires_authentication()
    {
        $response = $this->get('/admin/theme');
        
        $response->assertRedirect('/login');
    }

    public function test_theme_page_is_accessible_for_authenticated_user()
    {
        $response = $this->actingAs($this->user)->get('/admin/theme');

        $response->assertStatus(200);
        $response->assertViewIs('admin.theme.index');
    }

    public function test_theme_download_requires_authentication()
    {
        $response = $this->post('/admin/theme/download/1');
        
        $response->assertRedirect('/login');
    }

    public function test_theme_download_with_valid_id()
    {
        $response = $this->actingAs($this->user)->post('/admin/theme/download/1');

        // This might redirect or return a success response depending on implementation
        $this->assertTrue(in_array($response->status(), [200, 302]));
    }

    public function test_theme_purchase_page_is_accessible()
    {
        $response = $this->actingAs($this->user)->get('/admin/theme/purchase/1');

        $response->assertStatus(200);
        $response->assertViewIs('admin.theme.purchase');
    }

    public function test_theme_purchase_requires_authentication()
    {
        $response = $this->get('/admin/theme/purchase/1');
        
        $response->assertRedirect('/login');
    }

    public function test_theme_page_displays_available_themes()
    {
        $response = $this->actingAs($this->user)->get('/admin/theme');

        $response->assertStatus(200);
        $response->assertSee('Thèmes disponibles');
    }

    public function test_bg_admin_page_requires_authentication()
    {
        $response = $this->get('/admin/bg');
        
        $response->assertRedirect('/login');
    }

    public function test_bg_admin_page_is_accessible_for_authenticated_user()
    {
        $response = $this->actingAs($this->user)->get('/admin/bg');

        $response->assertStatus(200);
        $response->assertViewIs('admin.bg');
    }

    public function test_bg_can_be_updated()
    {
        $response = $this->actingAs($this->user)->post('/admin/bg/update', [
            'background_type' => 'image',
            'background_color' => '#ffffff',
            'background_image' => 'default-bg.jpg',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('options_bg', [
            'background_type' => 'image',
            'background_color' => '#ffffff',
            'background_image' => 'default-bg.jpg',
        ]);
    }

    public function test_bg_update_validates_background_type()
    {
        $response = $this->actingAs($this->user)->post('/admin/bg/update', [
            'background_type' => 'invalid_type',
        ]);

        $response->assertSessionHasErrors(['background_type']);
    }

    public function test_bg_update_validates_color_format()
    {
        $response = $this->actingAs($this->user)->post('/admin/bg/update', [
            'background_type' => 'color',
            'background_color' => 'invalid-color',
        ]);

        $response->assertSessionHasErrors(['background_color']);
    }
}
