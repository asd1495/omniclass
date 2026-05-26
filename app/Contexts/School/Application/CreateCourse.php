<?php

declare(strict_types=1);

namespace App\Contexts\School\Application;

use App\Contexts\School\Domain\Model\Course;
use App\Contexts\School\Domain\Repository\CourseRepository;

class CreateCourse
{
    public function __construct(
        private CourseRepository $repository
    ) {}

    public function execute(string $name, int $userId): void
    {
        $course = new Course(null, $name, $userId);
        $this->repository->save($course);
    }
}
