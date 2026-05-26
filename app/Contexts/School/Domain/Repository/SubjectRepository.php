<?php

declare(strict_types=1);

namespace App\Contexts\School\Domain\Repository;

use App\Contexts\School\Domain\Model\Subject;

interface SubjectRepository
{
    public function findById(int $id): ?Subject;

    public function save(Subject $subject): void;

    public function delete(int $id): void;

    /** @return Subject[] */
    public function findAllByUserId(int $userId): array;
}
