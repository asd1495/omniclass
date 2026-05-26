<?php

declare(strict_types=1);

namespace App\Contexts\School\Infrastructure\Persistence;

use App\Contexts\School\Domain\Model\Teacher as DomainTeacher;
use App\Contexts\School\Domain\Repository\TeacherRepository;
use App\Models\Teacher as EloquentTeacher;

class EloquentTeacherRepository implements TeacherRepository
{
    public function findById(int $id): ?DomainTeacher
    {
        $eloquent = EloquentTeacher::find($id);

        return $eloquent ? $this->toDomain($eloquent) : null;
    }

    public function findByEmail(string $email): ?DomainTeacher
    {
        $eloquent = EloquentTeacher::where('email', $email)->first();

        return $eloquent ? $this->toDomain($eloquent) : null;
    }

    public function save(DomainTeacher $teacher): void
    {
        EloquentTeacher::updateOrCreate(
            ['id' => $teacher->getId()],
            [
                'name' => $teacher->getName(),
                'email' => $teacher->getEmail(),
                'user_id' => $teacher->getUserId(),
            ]
        );
    }

    public function delete(int $id): void
    {
        EloquentTeacher::destroy($id);
    }

    private function toDomain(EloquentTeacher $eloquent): DomainTeacher
    {
        return new DomainTeacher(
            $eloquent->id,
            $eloquent->name,
            $eloquent->email,
            $eloquent->user_id
        );
    }
}
