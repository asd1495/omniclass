<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\School;

use App\Contexts\School\Application\RecordAttendance;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\School\RecordAttendanceRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function __construct(
        private RecordAttendance $recordAttendance
    ) {}

    public function store(RecordAttendanceRequest $request): JsonResponse
    {
        try {
            $this->recordAttendance->execute(
                (int) $request->validated('student_id'),
                $request->validated('date'),
                $request->validated('status'),
                (int) Auth::id()
            );

            return response()->json([
                'message' => 'Attendance recorded successfully',
            ], 201);
        } catch (\Exception $e) {
            $code = match ($e->getMessage()) {
                'Student not found' => 404,
                'Unauthorized access to student' => 403,
                default => 400
            };

            return response()->json(['message' => $e->getMessage()], $code);
        }
    }
}
