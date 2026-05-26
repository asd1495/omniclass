<?php

declare(strict_types=1);

namespace App\Contexts\School\Application;

use App\Contexts\School\Domain\Model\Student;
use App\Contexts\School\Domain\Repository\StudentRepository;
use Exception;

class UpdateStudent
{
    public function __construct(
        private StudentRepository $repository
    ) {}

    public function execute(int $id, string $name, string $email, int $userId, ?int $courseId = null): void
    {
        $student = $this->repository->findById($id);

        if (! $student) {
            throw new Exception('Student not found');
        }

        // Security check: ensure student belongs to the user
        if ($student->getUserId() !== $userId) {
            throw new Exception('Unauthorized');
        }

        $updatedStudent = new Student($id, $name, $email, $userId, $courseId);
        $this->repository->save($updatedStudent);
    }
}
