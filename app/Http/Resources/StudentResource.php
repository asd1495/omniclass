<?php

namespace App\Http\Resources;

use App\Contexts\School\Domain\Model\Student;
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

        return [
            'id' => $student->getId(),
            'name' => $student->getName(),
            'email' => $student->getEmail(),
            'course_id' => $student->getCourseId(),
            'course' => $this->whenLoaded('course', fn() => [
                'id' => $this->resource->course->id,
                'name' => $this->resource->course->name,
            ]),
        ];
    }
}
