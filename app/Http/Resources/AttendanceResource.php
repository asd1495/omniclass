<?php

namespace App\Http\Resources;

use App\Contexts\School\Domain\Model\Attendance;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Attendance $attendance */
        $attendance = $this->resource;

        return [
            'id' => $attendance->getId(),
            'student_id' => $attendance->getStudentId(),
            'date' => $attendance->getDate()->format('Y-m-d'),
            'status' => $attendance->getStatus()->value,
        ];
    }
}
