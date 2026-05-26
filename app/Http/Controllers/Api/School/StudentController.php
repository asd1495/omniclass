<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\School;

use App\Contexts\School\Application\DeleteStudent;
use App\Contexts\School\Application\ListStudents;
use App\Contexts\School\Application\RegisterStudent;
use App\Contexts\School\Application\UpdateStudent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\School\RegisterStudentRequest;
use App\Http\Resources\StudentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function __construct(
        private ListStudents $listStudents,
        private RegisterStudent $registerStudent,
        private UpdateStudent $updateStudent,
        private DeleteStudent $deleteStudent
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $courseId = $request->query('course_id') ? (int) $request->query('course_id') : null;
        $students = $this->listStudents->execute((int) Auth::id(), $courseId);

        return StudentResource::collection($students);
    }

    public function store(RegisterStudentRequest $request): JsonResponse
    {
        $this->registerStudent->execute(
            $request->validated('name'),
            $request->validated('email'),
            (int) Auth::id(),
            $request->validated('course_id') ? (int) $request->validated('course_id') : null
        );

        return response()->json([
            'message' => 'Student registered successfully',
        ], 201);
    }

    public function update(RegisterStudentRequest $request, int $id): JsonResponse
    {
        try {
            $this->updateStudent->execute(
                $id,
                $request->validated('name'),
                $request->validated('email'),
                (int) Auth::id(),
                $request->validated('course_id') ? (int) $request->validated('course_id') : null
            );

            return response()->json([
                'message' => 'Student updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getMessage() === 'Unauthorized' ? 403 : 404);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->deleteStudent->execute($id, (int) Auth::id());

            return response()->json([
                'message' => 'Student deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getMessage() === 'Unauthorized' ? 403 : 404);
        }
    }
}
