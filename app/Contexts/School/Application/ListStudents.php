<?php

declare(strict_types=1);

namespace App\Contexts\School\Application;

use App\Contexts\School\Domain\Model\Student;
use App\Contexts\School\Domain\Repository\StudentRepository;

class ListStudents
{
    public function __construct(
        private StudentRepository $repository
    ) {}

    /** @return Student[] */
    public function execute(int $userId, ?int $courseId = null): array
    {
        if ($courseId) {
            return $this->repository->findByCourseId($courseId, $userId);
        }

        return $this->repository->findAllByUserId($userId);
    }
}
