<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\School;

use App\Contexts\School\Application\GetDashboardSummary;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function stats(GetDashboardSummary $summary): JsonResponse
    {
        return response()->json($summary->execute((int) Auth::id()));
    }
}
