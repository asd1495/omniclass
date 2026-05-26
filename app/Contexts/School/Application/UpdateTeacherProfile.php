<?php

declare(strict_types=1);

namespace App\Contexts\School\Application;

use App\Contexts\School\Domain\Model\Teacher;
use App\Contexts\School\Domain\Repository\TeacherRepository;

class UpdateTeacherProfile
{
    public function __construct(
        private TeacherRepository $repository
    ) {}

    public function execute(int $teacherId, string $name, string $email, int $userId): void
    {
        $teacher = new Teacher(
            $teacherId,
            $name,
            $email,
            $userId
        );

        $this->repository->save($teacher);
    }
}
