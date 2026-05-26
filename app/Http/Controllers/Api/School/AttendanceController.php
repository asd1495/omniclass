<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\School;

use App\Contexts\School\Application\GetAttendanceHistory;
use App\Contexts\School\Application\GetDailyAttendance;
use App\Contexts\School\Application\RecordAttendance;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\School\RecordAttendanceRequest;
use App\Http\Resources\AttendanceResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function __construct(
        private RecordAttendance $recordAttendance,
        private GetDailyAttendance $getDailyAttendance,
        private GetAttendanceHistory $getAttendanceHistory
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $date = $request->query('date', now()->format('Y-m-d'));
        $records = $this->getDailyAttendance->execute((string) $date, (int) Auth::id());

        return AttendanceResource::collection($records);
    }

    public function history(): JsonResponse
    {
        $history = $this->getAttendanceHistory->execute((int) Auth::id());

        return response()->json(['data' => $history]);
    }

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
