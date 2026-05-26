<?php

declare(strict_types=1);

namespace App\Contexts\School\Application;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Student;
use App\Models\Subject;

class GetDashboardSummary
{
    /**
     * @return array{students: int, courses: int, subjects: int, attendance_rate: string}
     */
    public function execute(int $userId): array
    {
        // Fetch counts using simple count queries (fastest)
        $studentCount = Student::where('user_id', $userId)->count();
        $courseCount = Course::where('user_id', $userId)->count();
        $subjectCount = Subject::where('user_id', $userId)->count();

        // Calculate attendance rate in one SQL query
        /** @var object{total: int, present: int}|null $attendanceStats */
        $attendanceStats = Attendance::where('user_id', $userId)
            ->selectRaw('COUNT(*) as total, SUM(CASE WHEN status IN ("present", "late") THEN 1 ELSE 0 END) as present')
            ->first();

        $rate = 0.0;
        if ($attendanceStats && $attendanceStats->total > 0) {
            $rate = round(($attendanceStats->present / $attendanceStats->total) * 100, 1);
        }

        return [
            'students' => $studentCount,
            'courses' => $courseCount,
            'subjects' => $subjectCount,
            'attendance_rate' => $rate.'%',
        ];
    }
}
