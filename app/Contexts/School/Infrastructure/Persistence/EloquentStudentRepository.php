<?php

declare(strict_types=1);

namespace App\Contexts\School\Infrastructure\Persistence;

use App\Contexts\School\Domain\Model\Student as DomainStudent;
use App\Contexts\School\Domain\Repository\StudentRepository;
use App\Models\Student as EloquentStudent;

class EloquentStudentRepository implements StudentRepository
{
    public function findById(int $id): ?DomainStudent
    {
        $eloquentStudent = EloquentStudent::find($id);

        if (! $eloquentStudent) {
            return null;
        }

        return $this->toDomain($eloquentStudent);
    }

    public function save(DomainStudent $student): void
    {
        $eloquentStudent = EloquentStudent::updateOrCreate(
            ['id' => $student->getId()],
            [
                'name' => $student->getName(),
                'email' => $student->getEmail(),
                'user_id' => $student->getUserId(),
                'course_id' => $student->getCourseId(),
            ]
        );
    }

    public function delete(int $id): void
    {
        EloquentStudent::destroy($id);
    }

    public function findAllByUserId(int $userId): array
    {
        // Note: The UserIsolated scope handles the filtering automatically if Auth::check() is true,
        // but we explicitly pass it here for clarity or in case of background jobs.
        $students = EloquentStudent::with('course')->where('user_id', $userId)->get();

        return $students->map(fn (EloquentStudent $s) => $this->toDomain($s))->toArray();
    }

    public function findByCourseId(int $courseId, int $userId): array
    {
        $students = EloquentStudent::with('course')
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->get();

        return $students->map(fn (EloquentStudent $s) => $this->toDomain($s))->toArray();
    }

    private function toDomain(EloquentStudent $eloquentStudent): DomainStudent
    {
        return new DomainStudent(
            $eloquentStudent->id,
            $eloquentStudent->name,
            $eloquentStudent->email,
            $eloquentStudent->user_id,
            $eloquentStudent->course_id
        );
    }
}
