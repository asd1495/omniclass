<?php

declare(strict_types=1);

namespace App\Contexts\School\Application;

use App\Contexts\School\Domain\Model\Subject;
use App\Contexts\School\Domain\Repository\SubjectRepository;

class CreateSubject
{
    public function __construct(
        private SubjectRepository $repository
    ) {}

    public function execute(string $name, int $userId, ?int $courseId = null): void
    {
        $subject = new Subject(null, $name, $userId, $courseId);
        $this->repository->save($subject);
    }
}
