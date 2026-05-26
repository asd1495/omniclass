<?php

namespace Tests\Feature\Api\School;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_only_own_students(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Student::factory()->create(['user_id' => $user1->id, 'name' => 'My Student']);
        Student::factory()->create(['user_id' => $user2->id, 'name' => 'Other Student']);

        $token = $user1->createToken('test')->plainTextToken;

        $response = $this->getJson('/api/students', [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'My Student');
    }

    public function test_it_can_update_a_student(): void
    {
        $user = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->putJson("/api/students/{$student->id}", [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ], [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('students', ['id' => $student->id, 'name' => 'Updated Name']);
    }

    public function test_it_cannot_update_others_student(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $user2->id]);
        $token = $user1->createToken('test')->plainTextToken;

        $response = $this->putJson("/api/students/{$student->id}", [
            'name' => 'Hacker Name',
            'email' => 'hacker@example.com',
        ], [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertStatus(404);
    }

    public function test_it_can_delete_a_student(): void
    {
        $user = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->deleteJson("/api/students/{$student->id}", [], [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }
}
