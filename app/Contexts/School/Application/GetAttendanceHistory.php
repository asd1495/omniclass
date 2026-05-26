<?php

declare(strict_types=1);

namespace App\Contexts\School\Application;

use App\Contexts\School\Domain\Repository\AttendanceRepository;

class GetAttendanceHistory
{
    public function __construct(
        private AttendanceRepository $repository
    ) {}

    /**
     * @return array<int, array{date: string, present: int, total: int}>
     */
    public function execute(int $userId): array
    {
        $records = $this->repository->findAllByUserId($userId);
        $history = [];

        foreach ($records as $record) {
            $date = $record->getDate()->format('Y-m-d');
            if (! isset($history[$date])) {
                $history[$date] = [
                    'date' => $date,
                    'present' => 0,
                    'total' => 0,
                ];
            }

            $history[$date]['total']++;
            if ($record->getStatus()->value === 'present' || $record->getStatus()->value === 'late') {
                $history[$date]['present']++;
            }
        }

        krsort($history); // Sort by date descending

        return array_values($history);
    }
}
