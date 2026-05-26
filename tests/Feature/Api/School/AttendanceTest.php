<?php

namespace Tests\Feature\Api\School;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_record_attendance(): void
    {
        $user = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->postJson('/api/attendance', [
            'student_id' => $student->id,
            'date' => '2026-05-26',
            'status' => 'present',
        ], [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('attendances', [
            'student_id' => $student->id,
            'date' => '2026-05-26 00:00:00',
            'status' => 'present',
            'user_id' => $user->id,
        ]);
    }

    public function test_it_cannot_record_attendance_for_others_student(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $user2->id]);
        $token = $user1->createToken('test')->plainTextToken;

        $response = $this->postJson('/api/attendance', [
            'student_id' => $student->id,
            'date' => '2026-05-26',
            'status' => 'present',
        ], [
            'Authorization' => 'Bearer '.$token,
        ]);

        // Returns 404 because student is invisible to user1
        $response->assertStatus(404);
    }

    public function test_it_updates_attendance_if_already_exists(): void
    {
        $user = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('test')->plainTextToken;

        // Record first time
        $this->postJson('/api/attendance', [
            'student_id' => $student->id,
            'date' => '2026-05-26',
            'status' => 'present',
        ], ['Authorization' => 'Bearer '.$token]);

        // Record second time (update)
        $response = $this->postJson('/api/attendance', [
            'student_id' => $student->id,
            'date' => '2026-05-26',
            'status' => 'late',
        ], ['Authorization' => 'Bearer '.$token]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('attendances', [
            'student_id' => $student->id,
            'date' => '2026-05-26 00:00:00',
            'status' => 'late',
        ]);

        $this->assertDatabaseCount('attendances', 1);
    }
}
