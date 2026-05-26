<?php

namespace Tests\Feature\Api\School;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterStudentTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_register_a_student(): void
    {
        $user = User::factory()->create(['id' => 1]);
        $token = $user->createToken('test')->plainTextToken;

        $payload = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $response = $this->postJson('/api/students', $payload, [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertStatus(201)

            ->assertJson(['message' => 'Student registered successfully']);

        $this->assertDatabaseHas('students', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }

    public function test_it_validates_required_fields(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->postJson('/api/students', [], [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email']);
    }

    public function test_it_validates_unique_email(): void
    {
        $user = User::factory()->create(['id' => 1]);
        $token = $user->createToken('test')->plainTextToken;

        $headers = ['Authorization' => 'Bearer '.$token];

        // First registration
        $this->postJson('/api/students', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ], $headers);

        // Second registration with same email
        $response = $this->postJson('/api/students', [
            'name' => 'Jane Doe',
            'email' => 'john@example.com',
        ], $headers);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
