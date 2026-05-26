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
    public function execute(int $userId): array
    {
        return $this->repository->findAllByUserId($userId);
    }
}
