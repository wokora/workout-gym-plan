<?php

namespace App\Http\Controllers\Disease;

use App\Http\Controllers\Controller;
use App\Http\Resources\TreatmentResource;
use App\Models\Disease\Treatment;
use App\Models\File\File;
use App\Models\File\FileType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TreatmentController extends Controller
{
    /**
     * Display a listing of treatments.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Treatment::with(['treatment_type', 'evidence_level', 'images', 'diseases']);

        if ($request->filled('q')) {
            $search = trim($request->get('q'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('standard_protocol', 'like', "%{$search}%");
            });
        }

        if ($request->filled('treatment_type_id')) {
            $query->where('treatment_type_id', $request->get('treatment_type_id'));
        }

        $perPage = (int) $request->input('per_page', 15);
        $treatments = $query->orderBy('name')->paginate($perPage);

        return response()->json([
            'records' => TreatmentResource::collection($treatments),
            'pagination' => [
                'current_page' => $treatments->currentPage(),
                'last_page'    => $treatments->lastPage(),
                'per_page'     => $treatments->perPage(),
                'total'        => $treatments->total(),
            ]
        ]);
    }

    /**
     * Display the specified treatment.
     */
    public function show($id): JsonResponse
    {
        $treatment = Treatment::where('slug', $id)
            ->orWhere('id', $id)
            ->with(['treatment_type', 'evidence_level', 'diseases', 'images'])
            ->firstOrFail();

        return response()->json([
            'treatment' => new TreatmentResource($treatment),
        ]);
    }

    /**
     * Store a newly created treatment.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'treatment_type_id' => 'required|exists:treatment_type,id',
            'evidence_level_id' => 'nullable|exists:evidence_level,id',
            'description' => 'nullable|string',
            'mechanism_of_action' => 'nullable|string',
            'standard_protocol' => 'nullable|string',
            'side_effects' => 'nullable|string',
            'cautions_warnings' => 'nullable|string',
            'image_id' => 'nullable|integer',
            'image' => 'nullable|image|max:8192',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $treatment = Treatment::create($validated);

        if ($request->filled('image_id')) {
            $mediaFile = File::find($request->input('image_id'));
            if ($mediaFile) {
                $mediaFile->fileable()->associate($treatment);
                $mediaFile->save();
            }
        } elseif ($request->hasFile('image')) {
            $imageType = FileType::where('name', 'Image')->first();
            if ($imageType) {
                File::upload($treatment, $request->file('image'), $imageType);
            }
        }

        $treatment->load(['treatment_type', 'evidence_level', 'images']);

        return response()->json([
            'message' => 'Treatment created successfully.',
            'treatment' => new TreatmentResource($treatment),
        ], 201);
    }

    /**
     * Update the specified treatment.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $treatment = Treatment::where('slug', $id)->orWhere('id', $id)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'treatment_type_id' => 'required|exists:treatment_type,id',
            'evidence_level_id' => 'nullable|exists:evidence_level,id',
            'description' => 'nullable|string',
            'mechanism_of_action' => 'nullable|string',
            'standard_protocol' => 'nullable|string',
            'side_effects' => 'nullable|string',
            'cautions_warnings' => 'nullable|string',
            'image_id' => 'nullable|integer',
            'image' => 'nullable|image|max:8192',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $treatment->update($validated);

        if ($request->filled('image_id')) {
            $mediaFile = File::find($request->input('image_id'));
            if ($mediaFile) {
                $mediaFile->fileable()->associate($treatment);
                $mediaFile->save();
            }
        } elseif ($request->hasFile('image')) {
            $imageType = FileType::where('name', 'Image')->first();
            if ($imageType) {
                File::upload($treatment, $request->file('image'), $imageType);
            }
        }

        $treatment->load(['treatment_type', 'evidence_level', 'images']);

        return response()->json([
            'message' => 'Treatment updated successfully.',
            'treatment' => new TreatmentResource($treatment),
        ]);
    }

    /**
     * Remove the specified treatment.
     */
    public function destroy($id): JsonResponse
    {
        $treatment = Treatment::where('slug', $id)->orWhere('id', $id)->firstOrFail();
        $treatment->delete();

        return response()->json([
            'message' => 'Treatment deleted successfully.',
        ]);
    }

    /**
     * Upload image for treatment.
     */
    public function uploadImage(Request $request, $id): JsonResponse
    {
        $treatment = Treatment::where('slug', $id)->orWhere('id', $id)->firstOrFail();

        $request->validate([
            'image' => 'required|image|max:8192',
        ]);

        $imageType = FileType::where('name', 'Image')->first();
        if ($imageType) {
            File::upload($treatment, $request->file('image'), $imageType);
        }

        return response()->json([
            'message' => 'Image uploaded successfully.',
        ]);
    }
}
