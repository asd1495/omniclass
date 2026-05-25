<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\School;

use App\Contexts\School\Application\RegisterStudent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\School\RegisterStudentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function __construct(
        private RegisterStudent $registerStudentUseCase
    ) {}

    public function store(RegisterStudentRequest $request): JsonResponse
    {
        // Note: For now, we'll use a hardcoded user_id until we implement Auth.
        // Once Auth is ready, we'll use Auth::id().
        $userId = Auth::id() ?? 1;

        $this->registerStudentUseCase->execute(
            $request->validated('name'),
            $request->validated('email'),
            $userId
        );

        return response()->json([
            'message' => 'Student registered successfully',
        ], 201);
    }
}
