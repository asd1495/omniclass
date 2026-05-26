<?php

namespace App\Http\Resources;

use App\Contexts\School\Domain\Model\Subject;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Subject $subject */
        $subject = $this->resource;

        /** @var \App\Models\Subject|null $eloquent */
        $eloquent = $this->resource instanceof \App\Models\Subject ? $this->resource : null;

        return [
            'id' => $subject->getId(),
            'name' => $subject->getName(),
            'course_id' => $subject->getCourseId(),
            'course' => ($eloquent && $eloquent->relationLoaded('course') && $eloquent->course instanceof Course) ? [
                'id' => $eloquent->course->id,
                'name' => $eloquent->course->name,
            ] : null,
        ];
    }
}
