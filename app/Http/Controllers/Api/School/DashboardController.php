<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\School;

use App\Contexts\School\Application\GetAttendanceStats;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function stats(GetAttendanceStats $getAttendanceStats): JsonResponse
    {
        $rate = $getAttendanceStats->execute((int) Auth::id());

        return response()->json([
            'attendance_rate' => $rate . '%',
        ]);
    }
}
