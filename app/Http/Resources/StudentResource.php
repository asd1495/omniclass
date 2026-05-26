<?php

namespace App\Http\Resources;

use App\Contexts\School\Domain\Model\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Student $student */
        $student = $this->resource;

        /** @var \App\Models\Student|null $eloquent */
        $eloquent = $this->resource instanceof \App\Models\Student ? $this->resource : null;

        return [
            'id' => $student->getId(),
            'name' => $student->getName(),
            'email' => $student->getEmail(),
            'course_id' => $student->getCourseId(),
            'course' => ($eloquent && $eloquent->relationLoaded('course') && $eloquent->course instanceof Course) ? [
                'id' => $eloquent->course->id,
                'name' => $eloquent->course->name,
            ] : null,
        ];
    }
}
