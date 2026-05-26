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

    public function execute(string $name, int $userId): void
    {
        $subject = new Subject(null, $name, $userId);
        $this->repository->save($subject);
    }
}
