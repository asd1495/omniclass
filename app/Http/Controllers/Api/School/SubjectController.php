<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\School;

use App\Contexts\School\Application\CreateSubject;
use App\Contexts\School\Application\GetSubject;
use App\Contexts\School\Application\ListSubjects;
use App\Contexts\School\Application\UpdateSubject;
use App\Contexts\School\Domain\Repository\SubjectRepository;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubjectResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function index(ListSubjects $listSubjects): AnonymousResourceCollection
    {
        $subjects = $listSubjects->execute((int) Auth::id());

        return SubjectResource::collection($subjects);
    }

    public function show(int $id, GetSubject $getSubject): SubjectResource|JsonResponse
    {
        try {
            $subject = $getSubject->execute($id, (int) Auth::id());

            return new SubjectResource($subject);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request, CreateSubject $createSubject): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'course_id' => 'nullable|integer|exists:courses,id',
        ]);

        $createSubject->execute(
            (string) $request->name,
            (int) Auth::id(),
            $request->course_id ? (int) $request->course_id : null
        );

        return response()->json(['message' => 'Subject created successfully'], 201);
    }

    public function update(Request $request, int $id, UpdateSubject $updateSubject): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'course_id' => 'nullable|integer|exists:courses,id',
        ]);

        try {
            $updateSubject->execute(
                $id,
                (string) $request->name,
                (int) Auth::id(),
                $request->course_id ? (int) $request->course_id : null
            );

            return response()->json(['message' => 'Subject updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function destroy(int $id, SubjectRepository $repository): JsonResponse
    {
        $subject = $repository->findById($id);

        if (! $subject || $subject->getUserId() !== (int) Auth::id()) {
            return response()->json(['message' => 'Subject not found'], 404);
        }

        $repository->delete($id);

        return response()->json(['message' => 'Subject deleted successfully']);
    }
}
