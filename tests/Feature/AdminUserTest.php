<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_users_page_requires_authentication()
    {
        $response = $this->get('/admin/users');
        
        $response->assertRedirect('/login');
    }

    public function test_users_page_is_accessible_for_authenticated_user()
    {
        $response = $this->actingAs($this->user)->get('/admin/users');

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
    }

    public function test_user_creation_page_is_accessible()
    {
        $response = $this->actingAs($this->user)->get('/admin/users/create');

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.create');
    }

    public function test_new_user_can_be_created()
    {
        $userData = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->actingAs($this->user)->post('/admin/users/add', $userData);

        $response->assertRedirect('/admin/users');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
        ]);

        $newUser = User::where('email', 'newuser@example.com')->first();
        $this->assertTrue(Hash::check('password123', $newUser->password));
    }

    public function test_user_creation_validates_required_fields()
    {
        $response = $this->actingAs($this->user)->post('/admin/users/add', [
            'name' => '',
            'email' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_user_creation_validates_email_format()
    {
        $response = $this->actingAs($this->user)->post('/admin/users/add', [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_user_creation_validates_unique_email()
    {
        $existingUser = User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->actingAs($this->user)->post('/admin/users/add', [
            'name' => 'Test User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_user_creation_validates_password_confirmation()
    {
        $response = $this->actingAs($this->user)->post('/admin/users/add', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different-password',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_user_can_be_deleted()
    {
        $userToDelete = User::factory()->create();

        $response = $this->actingAs($this->user)->delete("/admin/users/delete/{$userToDelete->id}");

        $response->assertRedirect('/admin/users');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', [
            'id' => $userToDelete->id,
        ]);
    }

    public function test_user_edit_page_is_accessible()
    {
        $userToEdit = User::factory()->create();

        $response = $this->actingAs($this->user)->get("/admin/users/edit/{$userToEdit->id}");

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.edit');
        $response->assertSee($userToEdit->name);
        $response->assertSee($userToEdit->email);
    }

    public function test_user_can_be_updated()
    {
        $userToUpdate = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        $response = $this->actingAs($this->user)->put("/admin/users/update/{$userToUpdate->id}", [
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);

        $response->assertRedirect('/admin/users');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $userToUpdate->id,
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);
    }

    public function test_user_password_can_be_updated()
    {
        $userToUpdate = User::factory()->create();

        $response = $this->actingAs($this->user)->put("/admin/users/update/{$userToUpdate->id}", [
            'name' => $userToUpdate->name,
            'email' => $userToUpdate->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect('/admin/users');
        $response->assertSessionHas('success');

        $updatedUser = User::find($userToUpdate->id);
        $this->assertTrue(Hash::check('newpassword123', $updatedUser->password));
    }

    public function test_cannot_delete_non_existent_user()
    {
        $response = $this->actingAs($this->user)->delete('/admin/users/delete/999');

        $response->assertStatus(404);
    }

    public function test_cannot_edit_non_existent_user()
    {
        $response = $this->actingAs($this->user)->get('/admin/users/edit/999');

        $response->assertStatus(404);
    }
}
