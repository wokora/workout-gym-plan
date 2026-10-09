<?php

namespace App\Http\Controllers\Disease;

use App\Http\Controllers\Controller;
use App\Models\Disease\Disease;
use App\Models\Disease\Symptom;
use App\Models\Disease\Treatment;
use App\Models\File\File;
use App\Models\File\FileType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DiseaseController extends Controller
{
    /**
     * Display a simple listing of diseases.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Disease::with([
            'symptoms',
            'treatments.treatment_type',
            'foods',
            'exercises',
            'images',
        ]);

        if ($request->filled('q')) {
            $search = trim($request->get('q'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('overview', 'like', "%{$search}%")
                  ->orWhere('causes', 'like', "%{$search}%")
                  ->orWhere('prevention', 'like', "%{$search}%")
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

        $diseases = $query->paginate(12)->withQueryString();

        $totalDiseasesCount = Disease::count();
        $totalTreatmentsCount = Treatment::count();
        $totalSymptomsCount = Symptom::count();

        return view('disease.index', compact(
            'diseases',
            'totalDiseasesCount',
            'totalTreatmentsCount',
            'totalSymptomsCount'
        ));
    }

    /**
     * Display detailed disease profile.
     *
     * @param string|int $slugOrId
     * @return \Illuminate\View\View
     */
    public function show($slugOrId)
    {
        $disease = Disease::where('slug', $slugOrId)
            ->orWhere('id', $slugOrId)
            ->with([
                'symptoms',
                'treatments.treatment_type',
                'foods',
                'exercises',
                'images',
            ])
            ->firstOrFail();

        // Primary cures vs other helpful treatments
        $primaryCures = $disease->treatments->where('pivot.is_primary_cure', true);
        $otherTreatments = $disease->treatments->where('pivot.is_primary_cure', false);

        // Foods
        $helpfulFoods = $disease->foods->filter(fn($f) => in_array($f->pivot->recommendation_type, ['recommended', 'therapeutic']));
        $avoidFoods = $disease->foods->filter(fn($f) => in_array($f->pivot->recommendation_type, ['avoid', 'limit', 'restrict']));

        // Exercises
        $helpfulExercises = $disease->exercises->filter(fn($e) => in_array($e->pivot->safety_type, ['recommended', 'rehabilitative']));
        $avoidExercises = $disease->exercises->filter(fn($e) => in_array($e->pivot->safety_type, ['caution', 'contraindicated', 'avoid']));

        return view('disease.show', compact(
            'disease',
            'primaryCures',
            'otherTreatments',
            'helpfulFoods',
            'avoidFoods',
            'helpfulExercises',
            'avoidExercises'
        ));
    }

    /**
     * Show form to add a new disease.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $symptoms = Symptom::orderBy('name', 'asc')->get();
        $treatments = Treatment::with('treatment_type')->orderBy('name', 'asc')->get();

        return view('disease.create', compact('symptoms', 'treatments'));
    }

    /**
     * Store a newly created disease.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if ($request->has('treatments') && is_array($request->input('treatments'))) {
            $filteredTreatments = array_values(array_filter($request->input('treatments'), function ($item) {
                return is_array($item) && !empty($item['id']);
            }));
            $request->merge(['treatments' => $filteredTreatments]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'overview' => 'nullable|string',
            'causes' => 'nullable|string',
            'prevention' => 'nullable|string',
            'symptoms' => 'nullable|array',
            'symptoms.*' => 'exists:symptom,id',
            'treatments' => 'nullable|array',
            'treatments.*.id' => 'required|exists:treatment,id',
            'treatments.*.is_primary_cure' => 'nullable|boolean',
            'treatments.*.instructions' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:8192',
        ]);

        $disease = Disease::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'overview' => $validated['overview'] ?? null,
            'causes' => $validated['causes'] ?? null,
            'prevention' => $validated['prevention'] ?? null,
        ]);

        // Attach symptoms
        if ($request->filled('symptoms')) {
            $disease->symptoms()->sync($request->input('symptoms'));
        }

        // Attach treatments
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

        // Upload image if provided
        if ($request->hasFile('image')) {
            $imageType = FileType::where('name', 'Image')->first();
            if ($imageType) {
                File::upload($disease, $request->file('image'), $imageType);
            }
        }

        return redirect()->route('disease.show', $disease->slug)
            ->with('success', 'Condition successfully documented.');
    }

    /**
     * Show form to edit disease.
     *
     * @param int|string $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $disease = Disease::where('slug', $id)->orWhere('id', $id)
            ->with(['symptoms', 'treatments'])
            ->firstOrFail();

        $symptoms = Symptom::orderBy('name', 'asc')->get();
        $treatments = Treatment::with('treatment_type')->orderBy('name', 'asc')->get();

        return view('disease.edit', compact('disease', 'symptoms', 'treatments'));
    }

    /**
     * Update the specified disease.
     *
     * @param Request $request
     * @param int|string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $disease = Disease::where('slug', $id)->orWhere('id', $id)->firstOrFail();

        if ($request->has('treatments') && is_array($request->input('treatments'))) {
            $filteredTreatments = array_values(array_filter($request->input('treatments'), function ($item) {
                return is_array($item) && !empty($item['id']);
            }));
            $request->merge(['treatments' => $filteredTreatments]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'overview' => 'nullable|string',
            'causes' => 'nullable|string',
            'prevention' => 'nullable|string',
            'symptoms' => 'nullable|array',
            'symptoms.*' => 'exists:symptom,id',
            'treatments' => 'nullable|array',
            'treatments.*.id' => 'required|exists:treatment,id',
            'treatments.*.is_primary_cure' => 'nullable|boolean',
            'treatments.*.instructions' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:8192',
        ]);

        $disease->update([
            'name' => $validated['name'],
            'overview' => $validated['overview'] ?? null,
            'causes' => $validated['causes'] ?? null,
            'prevention' => $validated['prevention'] ?? null,
        ]);

        // Sync symptoms
        $disease->symptoms()->sync($request->input('symptoms', []));

        // Sync treatments
        $treatmentSync = [];
        if ($request->has('treatments') && is_array($request->input('treatments'))) {
            foreach ($request->input('treatments') as $item) {
                if (!empty($item['id'])) {
                    $treatmentSync[$item['id']] = [
                        'is_primary_cure' => !empty($item['is_primary_cure']) ? 1 : 0,
                        'specific_instructions' => $item['instructions'] ?? null,
                        'line_of_treatment' => !empty($item['is_primary_cure']) ? 'Primary Cure' : 'Helpful Care',
                    ];
                }
            }
        }
        $disease->treatments()->sync($treatmentSync);

        // Upload image if provided
        if ($request->hasFile('image')) {
            $imageType = FileType::where('name', 'Image')->first();
            if ($imageType) {
                File::upload($disease, $request->file('image'), $imageType);
            }
        }

        return redirect()->route('disease.show', $disease->slug)
            ->with('success', 'Condition details updated.');
    }

    /**
     * Upload an image attachment.
     *
     * @param Request $request
     * @param int|string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadImage(Request $request, $id)
    {
        $disease = Disease::where('slug', $id)->orWhere('id', $id)->firstOrFail();

        $request->validate([
            'image' => 'required|image|max:8192',
        ]);

        $imageType = FileType::where('name', 'Image')->firstOrFail();
        File::upload($disease, $request->file('image'), $imageType);

        return redirect()->route('disease.show', $disease->slug)
            ->with('success', 'Image successfully added.');
    }

    /**
     * Remove the disease.
     *
     * @param int|string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $disease = Disease::where('slug', $id)->orWhere('id', $id)->firstOrFail();
        $disease->delete();

        return redirect()->route('disease.index')
            ->with('success', 'Condition deleted.');
    }
}
