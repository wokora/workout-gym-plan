<?php

namespace App\Http\Controllers\Disease;

use App\Http\Controllers\Controller;
use App\Http\Resources\DiseaseResource;
use App\Models\Disease\Disease;
use App\Models\Disease\DiseaseRecommendation;
use App\Models\Disease\EvidenceLevel;
use App\Models\Disease\RecommendationType;
use App\Models\Disease\Symptom;
use App\Models\Disease\Treatment;
use App\Models\Disease\TreatmentType;
use App\Models\File\File;
use App\Models\File\FileType;
use App\Models\Food\Food;
use App\Models\Workout\Workout;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DiseaseController extends Controller
{
    /**
     * Provide reference lookups for diseases, symptoms, treatments, recommendation types, foods, and workouts.
     */
    public function lookups(): JsonResponse
    {
        return response()->json([
            'symptoms' => Symptom::orderBy('name')->get(['id', 'name', 'body_system']),
            'treatments' => Treatment::with('treatment_type')->orderBy('name')->get(['id', 'name', 'slug', 'treatment_type_id']),
            'treatment_types' => TreatmentType::withCount('treatments')->orderBy('name')->get(),
            'evidence_levels' => EvidenceLevel::orderBy('name')->get(),
            'recommendation_types' => RecommendationType::orderBy('id')->get(),
            'foods' => Food::orderBy('name')->get(['id', 'name', 'energy_kcal']),
            'workouts' => Workout::orderBy('name')->get(['id', 'name']),
            'body_systems' => Symptom::whereNotNull('body_system')->where('body_system', '!=', '')->distinct()->orderBy('body_system')->pluck('body_system'),
        ]);
    }

    /**
     * Display a listing of diseases with search and pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Disease::with([
            'symptoms',
            'treatments.treatment_type',
            'recommendations.recommendation_type',
            'recommendations.recommendable',
            'images',
        ]);

        if ($request->filled('q')) {
            $search = trim($request->get('q'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('symptoms', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('treatments', function ($tq) use ($search) {
                      $tq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $sort = $request->get('sort', 'name_asc');
        switch ($sort) {
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('name', 'asc');
                break;
        }

        $perPage = (int) $request->input('per_page', 12);
        $diseases = $query->paginate($perPage);

        return response()->json([
            'records' => DiseaseResource::collection($diseases),
            'stats' => [
                'total_diseases' => Disease::count(),
                'total_treatments' => Treatment::count(),
                'total_symptoms' => Symptom::count(),
            ],
            'pagination' => [
                'current_page' => $diseases->currentPage(),
                'last_page'    => $diseases->lastPage(),
                'per_page'     => $diseases->perPage(),
                'total'        => $diseases->total(),
            ]
        ]);
    }

    /**
     * Display the specified disease details.
     */
    public function show($slugOrId): JsonResponse
    {
        $disease = Disease::where('slug', $slugOrId)
            ->orWhere('id', $slugOrId)
            ->with([
                'symptoms',
                'treatments.treatment_type',
                'recommendations.recommendation_type',
                'recommendations.recommendable',
                'images',
            ])
            ->firstOrFail();

        return response()->json([
            'disease' => new DiseaseResource($disease),
        ]);
    }

    /**
     * Store a newly created disease.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'symptoms' => 'nullable|array',
            'treatments' => 'nullable|array',
            'treatments.*.id' => 'required|exists:treatment,id',
            'treatments.*.is_primary_cure' => 'nullable|boolean',
            'treatments.*.instructions' => 'nullable|string|max:1000',
            'recommendations' => 'nullable|array',
            'image_id' => 'nullable|integer',
            'image' => 'nullable|image|max:8192',
        ]);

        $disease = Disease::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        if ($request->filled('symptoms')) {
            $symptomSync = [];
            foreach ($request->input('symptoms') as $s) {
                if (is_array($s) && !empty($s['id'])) {
                    $symptomSync[$s['id']] = [
                        'is_primary' => !empty($s['is_primary']) ? 1 : 0,
                        'frequency' => $s['frequency'] ?? null,
                        'notes' => $s['notes'] ?? null,
                    ];
                } elseif (is_numeric($s)) {
                    $symptomSync[$s] = [];
                }
            }
            $disease->symptoms()->sync($symptomSync);
        }

        if ($request->has('treatments') && is_array($request->input('treatments'))) {
            $treatmentSync = [];
            foreach ($request->input('treatments') as $item) {
                if (!empty($item['id'])) {
                    $treatmentSync[$item['id']] = [
                        'is_primary_cure' => !empty($item['is_primary_cure']) ? 1 : 0,
                        'specific_instructions' => $item['instructions'] ?? null,
                        'line_of_treatment' => !empty($item['is_primary_cure']) ? 'Primary Cure' : 'Helpful Care',
                    ];
                }
            }
            $disease->treatments()->sync($treatmentSync);
        }

        $this->syncRecommendations($disease, $request->input('recommendations', []));

        if ($request->filled('image_id')) {
            $mediaFile = File::find($request->input('image_id'));
            if ($mediaFile) {
                $mediaFile->fileable()->associate($disease);
                $mediaFile->save();
            }
        } elseif ($request->hasFile('image')) {
            $imageType = FileType::where('name', 'Image')->first();
            if ($imageType) {
                File::upload($disease, $request->file('image'), $imageType);
            }
        }

        $disease->load([
            'symptoms',
            'treatments.treatment_type',
            'recommendations.recommendation_type',
            'recommendations.recommendable',
            'images',
        ]);

        return response()->json([
            'message' => 'Condition created successfully.',
            'disease' => new DiseaseResource($disease),
        ], 201);
    }

    /**
     * Update the specified disease.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $disease = Disease::where('slug', $id)->orWhere('id', $id)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'symptoms' => 'nullable|array',
            'treatments' => 'nullable|array',
            'treatments.*.id' => 'required|exists:treatment,id',
            'treatments.*.is_primary_cure' => 'nullable|boolean',
            'treatments.*.instructions' => 'nullable|string|max:1000',
            'recommendations' => 'nullable|array',
            'image_id' => 'nullable|integer',
            'image' => 'nullable|image|max:8192',
        ]);

        $disease->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        if ($request->has('symptoms')) {
            $symptomSync = [];
            foreach ($request->input('symptoms') ?: [] as $s) {
                if (is_array($s) && !empty($s['id'])) {
                    $symptomSync[$s['id']] = [
                        'is_primary' => !empty($s['is_primary']) ? 1 : 0,
                        'frequency' => $s['frequency'] ?? null,
                        'notes' => $s['notes'] ?? null,
                    ];
                } elseif (is_numeric($s)) {
                    $symptomSync[$s] = [];
                }
            }
            $disease->symptoms()->sync($symptomSync);
        }

        if ($request->has('treatments') && is_array($request->input('treatments'))) {
            $treatmentSync = [];
            foreach ($request->input('treatments') as $item) {
                if (!empty($item['id'])) {
                    $treatmentSync[$item['id']] = [
                        'is_primary_cure' => !empty($item['is_primary_cure']) ? 1 : 0,
                        'specific_instructions' => $item['instructions'] ?? null,
                        'line_of_treatment' => !empty($item['is_primary_cure']) ? 'Primary Cure' : 'Helpful Care',
                    ];
                }
            }
            $disease->treatments()->sync($treatmentSync);
        }

        if ($request->has('recommendations')) {
            $this->syncRecommendations($disease, $request->input('recommendations', []));
        }

        if ($request->filled('image_id')) {
            $mediaFile = File::find($request->input('image_id'));
            if ($mediaFile) {
                $mediaFile->fileable()->associate($disease);
                $mediaFile->save();
            }
        } elseif ($request->hasFile('image')) {
            $imageType = FileType::where('name', 'Image')->first();
            if ($imageType) {
                File::upload($disease, $request->file('image'), $imageType);
            }
        }

        $disease->load([
            'symptoms',
            'treatments.treatment_type',
            'recommendations.recommendation_type',
            'recommendations.recommendable',
            'images',
        ]);

        return response()->json([
            'message' => 'Condition updated successfully.',
            'disease' => new DiseaseResource($disease),
        ]);
    }

    /**
     * Remove the specified disease.
     */
    public function destroy($id): JsonResponse
    {
        $disease = Disease::where('slug', $id)->orWhere('id', $id)->firstOrFail();
        $disease->delete();

        return response()->json([
            'message' => 'Condition deleted successfully.',
        ]);
    }

    /**
     * Upload image for disease.
     */
    public function uploadImage(Request $request, $id): JsonResponse
    {
        $disease = Disease::where('slug', $id)->orWhere('id', $id)->firstOrFail();

        $request->validate([
            'image' => 'required|image|max:8192',
        ]);

        $imageType = FileType::where('name', 'Image')->first();
        if ($imageType) {
            File::upload($disease, $request->file('image'), $imageType);
        }

        return response()->json([
            'message' => 'Image uploaded successfully.',
        ]);
    }

    /**
     * Helper to synchronize polymorphic recommendations.
     */
    private function syncRecommendations(Disease $disease, array $recommendations): void
    {
        $disease->recommendations()->delete();

        foreach ($recommendations as $rec) {
            $type = $rec['recommendable_type'] ?? null;
            $id = $rec['recommendable_id'] ?? null;
            $typeId = $rec['recommendation_type_id'] ?? 1;

            if ($type && $id) {
                DiseaseRecommendation::create([
                    'disease_id' => $disease->id,
                    'recommendation_type_id' => $typeId,
                    'recommendable_type' => $type,
                    'recommendable_id' => $id,
                    'rationale' => $rec['rationale'] ?? null,
                    'instructions' => $rec['instructions'] ?? null,
                ]);
            }
        }
    }
}
