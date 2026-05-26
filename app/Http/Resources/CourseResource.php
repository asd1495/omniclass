<?php

namespace App\Http\Resources;

use App\Contexts\School\Domain\Model\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Course $course */
        $course = $this->resource;

        return [
            'id' => $course->getId(),
            'name' => $course->getName(),
        ];
    }
}
