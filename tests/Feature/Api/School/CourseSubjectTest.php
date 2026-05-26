<?php

namespace Tests\Feature\Api\School;

use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseSubjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_a_course(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->postJson('/api/courses', ['name' => '1st Grade A'], [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('courses', ['name' => '1st Grade A', 'user_id' => $user->id]);
    }

    public function test_it_can_list_courses_with_isolation(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Course::factory()->create(['user_id' => $user1->id, 'name' => 'Course 1']);
        Course::factory()->create(['user_id' => $user2->id, 'name' => 'Course 2']);

        $token = $user1->createToken('test')->plainTextToken;

        $response = $this->getJson('/api/courses', [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Course 1');
    }

    public function test_it_can_create_a_subject(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->postJson('/api/subjects', ['name' => 'Mathematics'], [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('subjects', ['name' => 'Mathematics', 'user_id' => $user->id]);
    }

    public function test_it_can_list_subjects_with_isolation(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Subject::factory()->create(['user_id' => $user1->id, 'name' => 'Math']);
        Subject::factory()->create(['user_id' => $user2->id, 'name' => 'History']);

        $token = $user1->createToken('test')->plainTextToken;

        $response = $this->getJson('/api/subjects', [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Math');
    }
}
