<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlobalScopeIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_students_are_isolated_by_user_id(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create student for user 1
        Student::factory()->create(['user_id' => $user1->id, 'name' => 'User 1 Student']);

        // Create student for user 2
        Student::factory()->create(['user_id' => $user2->id, 'name' => 'User 2 Student']);

        // Act as user 1
        $this->actingAs($user1);
        $this->assertCount(1, Student::all());
        $this->assertEquals('User 1 Student', Student::first()->name);

        // Act as user 2
        $this->actingAs($user2);
        $this->assertCount(1, Student::all());
        $this->assertEquals('User 2 Student', Student::first()->name);
    }

    public function test_teachers_are_isolated_by_user_id(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Teacher::factory()->create(['user_id' => $user1->id, 'name' => 'Teacher 1']);
        Teacher::factory()->create(['user_id' => $user2->id, 'name' => 'Teacher 2']);

        $this->actingAs($user1);
        $this->assertCount(1, Teacher::all());

        $this->actingAs($user2);
        $this->assertCount(1, Teacher::all());
    }
}
