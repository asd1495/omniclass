<?php

declare(strict_types=1);

namespace App\Contexts\School\Domain\Repository;

use App\Contexts\School\Domain\Model\Attendance;
use DateTimeImmutable;

interface AttendanceRepository
{
    public function save(Attendance $attendance): void;

    public function findByStudentAndDate(int $studentId, DateTimeImmutable $date): ?Attendance;

    /** @return Attendance[] */
    public function findAllByUserId(int $userId): array;
}
