<?php

namespace App\Http\Controllers\Disease;

use App\Http\Controllers\Controller;
use App\Models\Disease\EvidenceLevel;
use App\Models\Disease\Treatment;
use App\Models\Disease\TreatmentType;
use App\Models\File\File;
use App\Models\File\FileType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TreatmentController extends Controller
{
    /**
     * Display a listing of treatments with filtering.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Treatment::with(['treatment_type', 'evidence_level', 'diseases', 'images']);

        if ($request->filled('q')) {
            $search = trim($request->get('q'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('standard_protocol', 'like', "%{$search}%")
                  ->orWhere('mechanism_of_action', 'like', "%{$search}%");
            });
        }

        if ($request->filled('treatment_type_id')) {
            $query->where('treatment_type_id', $request->get('treatment_type_id'));
        }

        $treatments = $query->orderBy('name', 'asc')->paginate(12)->withQueryString();

        $treatmentTypes = TreatmentType::withCount('treatments')->orderBy('name', 'asc')->get();
        $totalTreatmentsCount = Treatment::count();

        return view('treatment.index', compact('treatments', 'treatmentTypes', 'totalTreatmentsCount'));
    }

    /**
     * Show form to create a new treatment/cure.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $treatmentTypes = TreatmentType::orderBy('name', 'asc')->get();

        return view('treatment.create', compact('treatmentTypes'));
    }

    /**
     * Store a newly created treatment.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'treatment_type_id' => 'required|exists:treatment_type,id',
            'description' => 'nullable|string',
            'standard_protocol' => 'nullable|string',
            'mechanism_of_action' => 'nullable|string',
            'side_effects' => 'nullable|string',
            'cautions_warnings' => 'nullable|string',
            'image' => 'nullable|image|max:8192',
        ]);

        $treatment = Treatment::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'treatment_type_id' => $validated['treatment_type_id'],
            'description' => $validated['description'] ?? null,
            'standard_protocol' => $validated['standard_protocol'] ?? null,
            'mechanism_of_action' => $validated['mechanism_of_action'] ?? null,
            'side_effects' => $validated['side_effects'] ?? null,
            'cautions_warnings' => $validated['cautions_warnings'] ?? null,
        ]);

        if ($request->hasFile('image')) {
            $imageType = FileType::where('name', 'Image')->first();
            if ($imageType) {
                File::upload($treatment, $request->file('image'), $imageType);
            }
        }

        return redirect()->route('treatment.show', $treatment->slug)
            ->with('success', 'Treatment / Remedy successfully added.');
    }

    /**
     * Display detailed treatment profile.
     *
     * @param string|int $slugOrId
     * @return \Illuminate\View\View
     */
    public function show($slugOrId)
    {
        $treatment = Treatment::where('slug', $slugOrId)
            ->orWhere('id', $slugOrId)
            ->with([
                'treatment_type',
                'evidence_level',
                'diseases',
                'files.file_type',
            ])
            ->firstOrFail();

        return view('treatment.show', compact('treatment'));
    }

    /**
     * Show form to edit an existing treatment.
     *
     * @param string|int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $treatment = Treatment::where('slug', $id)->orWhere('id', $id)->firstOrFail();
        $treatmentTypes = TreatmentType::orderBy('name', 'asc')->get();

        return view('treatment.edit', compact('treatment', 'treatmentTypes'));
    }

    /**
     * Update an existing treatment.
     *
     * @param Request $request
     * @param string|int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $treatment = Treatment::where('slug', $id)->orWhere('id', $id)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'treatment_type_id' => 'required|exists:treatment_type,id',
            'description' => 'nullable|string',
            'standard_protocol' => 'nullable|string',
            'mechanism_of_action' => 'nullable|string',
            'side_effects' => 'nullable|string',
            'cautions_warnings' => 'nullable|string',
            'image' => 'nullable|image|max:8192',
        ]);

        $treatment->update([
            'name' => $validated['name'],
            'treatment_type_id' => $validated['treatment_type_id'],
            'description' => $validated['description'] ?? null,
            'standard_protocol' => $validated['standard_protocol'] ?? null,
            'mechanism_of_action' => $validated['mechanism_of_action'] ?? null,
            'side_effects' => $validated['side_effects'] ?? null,
            'cautions_warnings' => $validated['cautions_warnings'] ?? null,
        ]);

        if ($request->hasFile('image')) {
            $imageType = FileType::where('name', 'Image')->first();
            if ($imageType) {
                File::upload($treatment, $request->file('image'), $imageType);
            }
        }

        return redirect()->route('treatment.show', $treatment->slug)
            ->with('success', 'Treatment details successfully updated.');
    }

    /**
     * Upload an image to the treatment.
     *
     * @param Request $request
     * @param int|string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadImage(Request $request, $id)
    {
        $treatment = Treatment::where('slug', $id)->orWhere('id', $id)->firstOrFail();

        $request->validate([
            'image' => 'required|image|max:8192',
        ]);

        $imageType = FileType::where('name', 'Image')->firstOrFail();
        File::upload($treatment, $request->file('image'), $imageType);

        return redirect()->route('treatment.show', $treatment->slug)
            ->with('success', 'Image successfully uploaded to treatment.');
    }

    /**
     * Delete a treatment.
     *
     * @param string|int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $treatment = Treatment::where('slug', $id)->orWhere('id', $id)->firstOrFail();
        $treatment->delete();

        return redirect()->route('treatment.index')
            ->with('success', 'Treatment successfully deleted.');
    }
}
