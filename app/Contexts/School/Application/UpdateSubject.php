<?php

declare(strict_types=1);

namespace App\Contexts\School\Application;

use App\Contexts\School\Domain\Model\Subject;
use App\Contexts\School\Domain\Repository\SubjectRepository;
use Exception;

class UpdateSubject
{
    public function __construct(
        private SubjectRepository $repository
    ) {}

    public function execute(int $id, string $name, int $userId, ?int $courseId = null): void
    {
        $subject = $this->repository->findById($id);

        if (!$subject || $subject->getUserId() !== $userId) {
            throw new Exception("Subject not found");
        }

        $updatedSubject = new Subject($id, $name, $userId, $courseId);
        $this->repository->save($updatedSubject);
    }
}
