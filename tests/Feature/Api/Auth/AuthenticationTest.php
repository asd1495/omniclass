<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_register_a_user(): void
    {
        $payload = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure(['access_token', 'token_type']);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_it_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token', 'token_type']);
    }

    public function test_it_cannot_login_with_wrong_password(): void
    {
        User::factory()->create(['email' => 'test@example.com']);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(401);
    }

    public function test_it_can_access_protected_user_info(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->getJson('/api/user', [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertStatus(200)
            ->assertJson(['email' => $user->email]);
    }
}
