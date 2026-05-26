<?php

declare(strict_types=1);

namespace App\Contexts\School\Application;

use App\Contexts\School\Domain\Model\Subject;
use App\Contexts\School\Domain\Repository\SubjectRepository;
use Exception;

class GetSubject
{
    public function __construct(
        private SubjectRepository $repository
    ) {}

    public function execute(int $id, int $userId): Subject
    {
        $subject = $this->repository->findById($id);

        if (! $subject || $subject->getUserId() !== $userId) {
            throw new Exception('Subject not found');
        }

        return $subject;
    }
}
