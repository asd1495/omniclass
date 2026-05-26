<?php

declare(strict_types=1);

namespace App\Contexts\School\Infrastructure\Persistence;

use App\Contexts\School\Domain\Model\Subject as DomainSubject;
use App\Contexts\School\Domain\Repository\SubjectRepository;
use App\Models\Subject as EloquentSubject;

class EloquentSubjectRepository implements SubjectRepository
{
    public function findById(int $id): ?DomainSubject
    {
        $eloquent = EloquentSubject::find($id);

        return $eloquent ? $this->toDomain($eloquent) : null;
    }

    public function save(DomainSubject $subject): void
    {
        EloquentSubject::updateOrCreate(
            ['id' => $subject->getId()],
            [
                'name' => $subject->getName(),
                'user_id' => $subject->getUserId(),
                'course_id' => $subject->getCourseId(),
            ]
        );
    }

    public function delete(int $id): void
    {
        EloquentSubject::destroy($id);
    }

    public function findAllByUserId(int $userId): array
    {
        return EloquentSubject::with('course')->where('user_id', $userId)
            ->get()
            ->map(fn (EloquentSubject $s) => $this->toDomain($s))
            ->toArray();
    }

    private function toDomain(EloquentSubject $eloquent): DomainSubject
    {
        return new DomainSubject(
            $eloquent->id,
            $eloquent->name,
            $eloquent->user_id,
            $eloquent->course_id
        );
    }
}
