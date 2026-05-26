<?php

declare(strict_types=1);

namespace App\Contexts\School\Application;

use App\Contexts\School\Domain\Model\Course;
use App\Contexts\School\Domain\Repository\CourseRepository;
use Exception;

class GetCourse
{
    public function __construct(
        private CourseRepository $repository
    ) {}

    public function execute(int $id, int $userId): Course
    {
        $course = $this->repository->findById($id);

        if (! $course || $course->getUserId() !== $userId) {
            throw new Exception('Course not found');
        }

        return $course;
    }
}
