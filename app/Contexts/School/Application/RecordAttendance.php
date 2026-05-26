<?php

declare(strict_types=1);

namespace App\Contexts\School\Application;

use App\Contexts\School\Domain\Enum\AttendanceStatus;
use App\Contexts\School\Domain\Model\Attendance;
use App\Contexts\School\Domain\Repository\AttendanceRepository;
use App\Contexts\School\Domain\Repository\StudentRepository;
use DateTimeImmutable;
use Exception;

class RecordAttendance
{
    public function __construct(
        private AttendanceRepository $attendanceRepository,
        private StudentRepository $studentRepository
    ) {}

    public function execute(int $studentId, string $date, string $status, int $userId): void
    {
        $student = $this->studentRepository->findById($studentId);

        if (! $student) {
            throw new Exception('Student not found');
        }

        if ($student->getUserId() !== $userId) {
            throw new Exception('Unauthorized access to student');
        }

        $attendance = new Attendance(
            null,
            $studentId,
            new DateTimeImmutable($date),
            AttendanceStatus::from($status),
            userId: $userId
        );

        $this->attendanceRepository->save($attendance);
    }
}
