<?php

namespace App\Http\Controllers\Exercise;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExerciseResource;
use App\Models\Exercise\Exercise;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    /**
     * Display a listing of exercises with search and pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Exercise::with('body_section.body_section');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        if ($request->filled('muscle')) {
            $muscle = $request->input('muscle');
            $query->whereHas('body_section.body_section', function ($q) use ($muscle) {
                $q->where('name', $muscle);
            });
        }

        $perPage = (int) $request->input('per_page', 20);
        $exercises = $query->orderBy('name')->paginate($perPage);

        return response()->json([
            'records' => ExerciseResource::collection($exercises),
            'pagination' => [
                'current_page' => $exercises->currentPage(),
                'last_page'    => $exercises->lastPage(),
                'per_page'     => $exercises->perPage(),
                'total'        => $exercises->total(),
            ]
        ]);
    }

    /**
     * Store a newly created exercise in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:exercise,name',
            'description' => 'nullable|string',
            'body_sections' => 'nullable|array',
            'body_sections.*' => 'exists:body_section,id',
        ]);

        $exercise = Exercise::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        if ($request->has('body_sections')) {
            foreach ($request->body_sections as $bodyId) {
                $exercise->body_section()->create([
                    'body_section_id' => $bodyId,
                ]);
            }
        }

        $exercise->load('body_section.body_section');

        return response()->json([
            'message' => 'Exercise created successfully.',
            'exercise' => new ExerciseResource($exercise),
        ], 201);
    }

    /**
     * Display the specified exercise.
     */
    public function show($id): JsonResponse
    {
        $exercise = Exercise::with(['body_section.body_section', 'diseases'])->findOrFail($id);

        return response()->json([
            'exercise' => new ExerciseResource($exercise),
        ]);
    }

    /**
     * Update the specified exercise in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $exercise = Exercise::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:exercise,name,' . $id,
            'description' => 'nullable|string',
            'body_sections' => 'nullable|array',
            'body_sections.*' => 'exists:body_section,id',
        ]);

        $exercise->update($request->only('name', 'description'));

        if ($request->has('body_sections')) {
            $exercise->body_section()->delete();
            foreach ($request->body_sections as $bodyId) {
                $exercise->body_section()->create([
                    'body_section_id' => $bodyId,
                ]);
            }
        }

        $exercise->load('body_section.body_section');

        return response()->json([
            'message' => 'Exercise updated successfully.',
            'exercise' => new ExerciseResource($exercise),
        ]);
    }

    /**
     * Remove the specified exercise from storage.
     */
    public function destroy($id): JsonResponse
    {
        $exercise = Exercise::findOrFail($id);
        $exercise->delete();

        return response()->json([
            'message' => 'Exercise deleted successfully.',
        ]);
    }
}
