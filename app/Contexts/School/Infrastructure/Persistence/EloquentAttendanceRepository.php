<?php

declare(strict_types=1);

namespace App\Contexts\School\Infrastructure\Persistence;

use App\Contexts\School\Domain\Enum\AttendanceStatus;
use App\Contexts\School\Domain\Model\Attendance as DomainAttendance;
use App\Contexts\School\Domain\Repository\AttendanceRepository;
use App\Models\Attendance as EloquentAttendance;
use DateTimeImmutable;

class EloquentAttendanceRepository implements AttendanceRepository
{
    public function save(DomainAttendance $attendance): void
    {
        EloquentAttendance::updateOrCreate(
            [
                'student_id' => $attendance->getStudentId(),
                'date' => $attendance->getDate(),
                'user_id' => $attendance->getUserId(),
            ],
            [
                'status' => $attendance->getStatus()->value,
            ]
        );
    }

    public function findByStudentAndDate(int $studentId, DateTimeImmutable $date): ?DomainAttendance
    {
        $eloquent = EloquentAttendance::where('student_id', $studentId)
            ->where('date', $date->format('Y-m-d'))
            ->first();

        return $eloquent ? $this->toDomain($eloquent) : null;
    }

    public function findAllByUserId(int $userId): array
    {
        $records = EloquentAttendance::where('user_id', $userId)->get();

        return $records->map(fn (EloquentAttendance $a) => $this->toDomain($a))->toArray();
    }

    private function toDomain(EloquentAttendance $eloquent): DomainAttendance
    {
        return new DomainAttendance(
            $eloquent->id,
            $eloquent->student_id,
            $eloquent->date,
            AttendanceStatus::from($eloquent->status),
            $eloquent->user_id
        );
    }
}
