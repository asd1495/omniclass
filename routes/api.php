<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\School\AttendanceController;
use App\Http\Controllers\Api\School\CourseController;
use App\Http\Controllers\Api\School\StudentController;
use App\Http\Controllers\Api\School\SubjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('students', StudentController::class);
    Route::get('attendance', [AttendanceController::class, 'index']);
    Route::post('attendance', [AttendanceController::class, 'store']);
    Route::apiResource('courses', CourseController::class);
    Route::apiResource('subjects', SubjectController::class);
});
