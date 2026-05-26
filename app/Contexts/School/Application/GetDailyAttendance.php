<?php

declare(strict_types=1);

namespace App\Contexts\School\Application;

use App\Contexts\School\Domain\Model\Attendance;
use App\Contexts\School\Domain\Repository\AttendanceRepository;
use DateTimeImmutable;

class GetDailyAttendance
{
    public function __construct(
        private AttendanceRepository $repository
    ) {}

    /** @return Attendance[] */
    public function execute(string $date, int $userId): array
    {
        return $this->repository->findByDate(new DateTimeImmutable($date), $userId);
    }
}
