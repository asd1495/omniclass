<?php

namespace App\Http\Resources;

use App\Contexts\School\Domain\Model\Subject;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Subject $subject */
        $subject = $this->resource;

        return [
            'id' => $subject->getId(),
            'name' => $subject->getName(),
        ];
    }
}
