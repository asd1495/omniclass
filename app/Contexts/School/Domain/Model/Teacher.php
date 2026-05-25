<?php

declare(strict_types=1);

namespace App\Contexts\School\Domain\Model;

class Teacher
{
    public function __construct(
        private ?int $id,
        private string $name,
        private string $email,
        private int $userId
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
}
