<?php

namespace App\Http\Controllers\Disease;

use App\Http\Controllers\Controller;
use App\Http\Resources\SymptomResource;
use App\Models\Disease\Symptom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SymptomController extends Controller
{
    /**
     * Display a listing of symptoms.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Symptom::with('diseases.treatments.treatment_type');

        if ($request->filled('q')) {
            $search = trim($request->get('q'));
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('body_system', 'like', "%{$search}%");
        }

        if ($request->filled('body_system')) {
            $query->where('body_system', $request->get('body_system'));
        }

        $perPage = (int) $request->input('per_page', 25);
        $symptoms = $query->orderBy('name')->paginate($perPage);

        return response()->json([
            'records' => SymptomResource::collection($symptoms),
            'pagination' => [
                'current_page' => $symptoms->currentPage(),
                'last_page'    => $symptoms->lastPage(),
                'per_page'     => $symptoms->perPage(),
                'total'        => $symptoms->total(),
            ]
        ]);
    }

    /**
     * Display the specified symptom.
     */
    public function show($id): JsonResponse
    {
        $symptom = Symptom::where('slug', $id)
            ->orWhere('id', $id)
            ->with('diseases.treatments.treatment_type')
            ->firstOrFail();

        return response()->json([
            'symptom' => new SymptomResource($symptom),
        ]);
    }

    /**
     * Store a newly created symptom.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:symptom,name',
            'description' => 'nullable|string',
            'body_system' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $symptom = Symptom::create($validated);

        return response()->json([
            'message' => 'Symptom created successfully.',
            'symptom' => new SymptomResource($symptom),
        ], 201);
    }

    /**
     * Update the specified symptom.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $symptom = Symptom::where('slug', $id)->orWhere('id', $id)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:symptom,name,' . $symptom->id,
            'description' => 'nullable|string',
            'body_system' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $symptom->update($validated);

        return response()->json([
            'message' => 'Symptom updated successfully.',
            'symptom' => new SymptomResource($symptom),
        ]);
    }

    /**
     * Remove the specified symptom.
     */
    public function destroy($id): JsonResponse
    {
        $symptom = Symptom::where('slug', $id)->orWhere('id', $id)->firstOrFail();
        $symptom->delete();

        return response()->json([
            'message' => 'Symptom deleted successfully.',
        ]);
    }
}
