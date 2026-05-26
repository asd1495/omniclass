<?php

declare(strict_types=1);

namespace App\Contexts\School\Application;

use App\Contexts\School\Domain\Repository\StudentRepository;
use Exception;

class DeleteStudent
{
    public function __construct(
        private StudentRepository $repository
    ) {}

    public function execute(int $id, int $userId): void
    {
        $student = $this->repository->findById($id);

        if (! $student) {
            throw new Exception('Student not found');
        }

        if ($student->getUserId() !== $userId) {
            throw new Exception('Unauthorized');
        }

        $this->repository->delete($id);
    }
}
