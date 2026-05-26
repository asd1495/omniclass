<?php

declare(strict_types=1);

namespace App\Contexts\School\Application;

use App\Contexts\School\Domain\Model\Student;
use App\Contexts\School\Domain\Repository\StudentRepository;

class RegisterStudent
{
    public function __construct(
        private StudentRepository $repository
    ) {}

    public function execute(string $name, string $email, int $userId, ?int $courseId = null): void
    {
        // Here we could add business validation (e.g. email already registered)
        // using the repository or a Domain Service.

        $student = new Student(
            null,
            $name,
            $email,
            $userId,
            $courseId
        );

        $this->repository->save($student);
    }
}
