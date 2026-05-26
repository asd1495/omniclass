<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Contexts\Identity\Application\RegisterUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(
        private RegisterUser $registerUserUseCase
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->registerUserUseCase->execute($request->validated());

        /** @var User $eloquentUser */
        $eloquentUser = User::find($user->getId());
        $token = $eloquentUser->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (! Auth::attempt($request->validated())) {
            return response()->json([
                'message' => 'Invalid login details',
            ], 401);
        }

        /** @var User $user */
        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function loginAsGuest(): JsonResponse
    {
        /** @var User $user */
        $user = User::where('email', 'guest@example.com')->firstOrFail();
        $token = $user->createToken('demo_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}
