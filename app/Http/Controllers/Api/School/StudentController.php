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
        $userId = Auth::id();

        $this->registerStudentUseCase->execute(
            $request->validated('name'),
            $request->validated('email'),
            (int) $userId
        );

        return response()->json([
            'message' => 'Student registered successfully',
        ], 201);
    }
}
