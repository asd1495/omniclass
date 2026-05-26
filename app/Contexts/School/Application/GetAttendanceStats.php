<?php

declare(strict_types=1);

namespace App\Contexts\School\Application;

use App\Contexts\School\Domain\Enum\AttendanceStatus;
use App\Contexts\School\Domain\Repository\AttendanceRepository;

class GetAttendanceStats
{
    public function __construct(
        private AttendanceRepository $repository
    ) {}

    public function execute(int $userId): float
    {
        $records = $this->repository->findAllByUserId($userId);

        if (empty($records)) {
            return 0.0;
        }

        $total = count($records);
        $present = count(array_filter($records, fn ($r) => $r->getStatus() === AttendanceStatus::PRESENT ||
            $r->getStatus() === AttendanceStatus::LATE
        ));

        return round(($present / $total) * 100, 1);
    }
}
