<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Domain\Model;

class User
{
    public function __construct(
        private ?int $id,
        private string $name,
        private string $email,
        private ?string $password = null
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

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
