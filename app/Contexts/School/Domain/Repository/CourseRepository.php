<?php

declare(strict_types=1);

namespace App\Contexts\School\Domain\Repository;

use App\Contexts\School\Domain\Model\Course;

interface CourseRepository
{
    public function findById(int $id): ?Course;

    public function save(Course $course): void;

    public function delete(int $id): void;

    /** @return Course[] */
    public function findAllByUserId(int $userId): array;
}
