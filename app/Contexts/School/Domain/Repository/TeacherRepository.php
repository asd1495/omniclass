<?php

declare(strict_types=1);

namespace App\Contexts\School\Domain\Repository;

use App\Contexts\School\Domain\Model\Teacher;

interface TeacherRepository
{
    public function findById(int $id): ?Teacher;

    public function findByEmail(string $email): ?Teacher;

    public function save(Teacher $teacher): void;

    public function delete(int $id): void;
}
