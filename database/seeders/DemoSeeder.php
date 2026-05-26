<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create or Find Guest User (ID 1)
        $user = User::updateOrCreate(
            ['email' => 'guest@example.com'],
            [
                'id' => 1,
                'name' => 'Guest Teacher',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Add Students
        $students = [
            'Alice Johnson', 'Bob Smith', 'Charlie Brown',
            'Diana Prince', 'Edward Norton', 'Fiona Gallagher',
        ];

        foreach ($students as $name) {
            Student::updateOrCreate(
                ['email' => strtolower(str_replace(' ', '.', $name)).'@demo.com'],
                ['name' => $name, 'user_id' => $user->id]
            );
        }

        // 3. Add Courses
        $courses = ['1st Grade A', '2nd Grade B', '3rd Grade C'];
        foreach ($courses as $name) {
            Course::updateOrCreate(
                ['name' => $name, 'user_id' => $user->id]
            );
        }

        // 4. Add Subjects
        $subjects = ['Mathematics', 'Science', 'History', 'Art'];
        foreach ($subjects as $name) {
            Subject::updateOrCreate(
                ['name' => $name, 'user_id' => $user->id]
            );
        }
    }
}
