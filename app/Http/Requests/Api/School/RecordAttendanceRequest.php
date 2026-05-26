<?php

namespace App\Http\Requests\Api\School;

use App\Contexts\School\Domain\Enum\AttendanceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RecordAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'date' => ['required', 'date_format:Y-m-d'],
            'status' => ['required', Rule::enum(AttendanceStatus::class)],
        ];
    }
}
