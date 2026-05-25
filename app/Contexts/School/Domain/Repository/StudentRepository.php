<?php

declare(strict_types=1);

namespace App\Contexts\School\Domain\Repository;

use App\Contexts\School\Domain\Model\Student;

interface StudentRepository
{
    public function findById(int $id): ?Student;
    public function save(Student $student): void;
    public function delete(int $id): void;
    /** @return Student[] */
    public function findAllByUserId(int $userId): array;
}
