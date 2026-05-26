<?php

declare(strict_types=1);

namespace App\Contexts\School\Domain\Model;

class Subject
{
    public function __construct(
        private ?int $id,
        private string $name,
        private int $userId,
        private ?int $courseId = null
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getCourseId(): ?int
    {
        return $this->courseId;
    }
}
