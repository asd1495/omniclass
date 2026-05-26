<?php

declare(strict_types=1);

namespace App\Contexts\School\Application;

use App\Contexts\School\Domain\Model\Subject;
use App\Contexts\School\Domain\Repository\SubjectRepository;

class ListSubjects
{
    public function __construct(
        private SubjectRepository $repository
    ) {}

    /** @return Subject[] */
    public function execute(int $userId): array
    {
        return $this->repository->findAllByUserId($userId);
    }
}
