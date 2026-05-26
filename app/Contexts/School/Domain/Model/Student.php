<?php

declare(strict_types=1);

namespace App\Contexts\School\Domain\Model;

class Student
{
    public function __construct(
        private ?int $id,
        private string $name,
        private string $email,
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getCourseId(): ?int
    {
        return $this->courseId;
    }

    public function updateName(string $name): void
    {
        $this->name = $name;
    }
}
