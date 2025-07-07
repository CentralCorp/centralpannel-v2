<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_user_email_is_unique()
    {
        User::create([
            'name' => 'First User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        User::create([
            'name' => 'Second User',
            'email' => 'test@example.com',
            'password' => Hash::make('password456'),
        ]);
    }

    public function test_user_password_is_hidden()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $userArray = $user->toArray();
        $this->assertArrayNotHasKey('password', $userArray);
    }

    public function test_user_has_correct_fillable_attributes()
    {
        $user = new User();
        $fillable = $user->getFillable();

        $expectedFillable = ['name', 'email', 'password'];
        $this->assertEquals($expectedFillable, $fillable);
    }

    public function test_user_can_be_updated()
    {
        $user = User::create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'password' => Hash::make('oldpassword'),
        ]);

        $user->update([
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);

        $this->assertEquals('New Name', $user->name);
        $this->assertEquals('new@example.com', $user->email);
    }

    public function test_user_factory_works()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(User::class, $user);
        $this->assertNotNull($user->name);
        $this->assertNotNull($user->email);
        $this->assertNotNull($user->password);
    }
}