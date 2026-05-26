<?php

declare(strict_types=1);

namespace App\Contexts\School\Application;

use App\Contexts\School\Domain\Model\Course;
use App\Contexts\School\Domain\Repository\CourseRepository;

class ListCourses
{
    public function __construct(
        private CourseRepository $repository
    ) {}

    /** @return Course[] */
    public function execute(int $userId): array
    {
        return $this->repository->findAllByUserId($userId);
    }
}
