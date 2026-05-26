<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\School;

use App\Contexts\School\Application\CreateCourse;
use App\Contexts\School\Application\GetCourse;
use App\Contexts\School\Application\ListCourses;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index(ListCourses $listCourses): AnonymousResourceCollection
    {
        $courses = $listCourses->execute((int) Auth::id());

        return CourseResource::collection($courses);
    }

    public function show(int $id, GetCourse $getCourse): CourseResource|JsonResponse
    {
        try {
            $course = $getCourse->execute($id, (int) Auth::id());

            return new CourseResource($course);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request, CreateCourse $createCourse): JsonResponse
    {
        $request->validate(['name' => 'required|string|max:255']);

        $createCourse->execute($request->name, (int) Auth::id());

        return response()->json(['message' => 'Course created successfully'], 201);
    }
}
