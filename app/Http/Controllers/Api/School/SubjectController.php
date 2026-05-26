<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\School;

use App\Contexts\School\Application\CreateSubject;
use App\Contexts\School\Application\ListSubjects;
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

    public function store(Request $request, CreateSubject $createSubject): JsonResponse
    {
        $request->validate(['name' => 'required|string|max:255']);

        $createSubject->execute($request->name, (int) Auth::id());

        return response()->json(['message' => 'Subject created successfully'], 201);
    }
}
