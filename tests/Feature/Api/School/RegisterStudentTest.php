<?php

namespace Tests\Feature\Api\School;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterStudentTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_register_a_student(): void
    {
        $payload = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $response = $this->postJson('/api/students', $payload);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Student registered successfully']);

        $this->assertDatabaseHas('students', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }

    public function test_it_validates_required_fields(): void
    {
        $response = $this->postJson('/api/students', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email']);
    }

    public function test_it_validates_unique_email(): void
    {
        // First registration
        $this->postJson('/api/students', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        // Second registration with same email
        $response = $this->postJson('/api/students', [
            'name' => 'Jane Doe',
            'email' => 'john@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
