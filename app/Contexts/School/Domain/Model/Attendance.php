<?php

declare(strict_types=1);

namespace App\Contexts\School\Domain\Model;

use App\Contexts\School\Domain\Enum\AttendanceStatus;
use DateTimeImmutable;

class Attendance
{
    public function __construct(
        private ?int $id,
        private int $studentId,
        private DateTimeImmutable $date,
        private AttendanceStatus $status,
        private int $userId
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudentId(): int
    {
        return $this->studentId;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getStatus(): AttendanceStatus
    {
        return $this->status;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
