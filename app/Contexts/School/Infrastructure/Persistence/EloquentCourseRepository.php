<?php

declare(strict_types=1);

namespace App\Contexts\School\Infrastructure\Persistence;

use App\Contexts\School\Domain\Model\Course as DomainCourse;
use App\Contexts\School\Domain\Repository\CourseRepository;
use App\Models\Course as EloquentCourse;

class EloquentCourseRepository implements CourseRepository
{
    public function findById(int $id): ?DomainCourse
    {
        $eloquent = EloquentCourse::find($id);

        return $eloquent ? $this->toDomain($eloquent) : null;
    }

    public function save(DomainCourse $course): void
    {
        EloquentCourse::updateOrCreate(
            ['id' => $course->getId()],
            [
                'name' => $course->getName(),
                'user_id' => $course->getUserId(),
            ]
        );
    }

    public function delete(int $id): void
    {
        EloquentCourse::destroy($id);
    }

    public function findAllByUserId(int $userId): array
    {
        return EloquentCourse::where('user_id', $userId)
            ->get()
            ->map(fn (EloquentCourse $c) => $this->toDomain($c))
            ->toArray();
    }

    private function toDomain(EloquentCourse $eloquent): DomainCourse
    {
        return new DomainCourse(
            $eloquent->id,
            $eloquent->name,
            $eloquent->user_id
        );
    }
}
