<?php

namespace App\Http\Controllers\Disease;

use App\Http\Controllers\Controller;
use App\Models\Disease\Symptom;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SymptomController extends Controller
{
    /**
     * Display a listing of symptoms grouped by body system with disease linkages.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Symptom::with(['diseases']);

        if ($request->filled('q')) {
            $search = trim($request->get('q'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('body_system', 'like', "%{$search}%");
            });
        }

        if ($request->filled('body_system')) {
            $query->where('body_system', $request->get('body_system'));
        }

        $symptoms = $query->orderBy('name', 'asc')->get();

        // Unique body systems list
        $bodySystems = Symptom::select('body_system')->distinct()->whereNotNull('body_system')->orderBy('body_system')->pluck('body_system');

        return view('symptom.index', compact('symptoms', 'bodySystems'));
    }

    /**
     * Show the form for creating a new symptom.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $bodySystems = Symptom::select('body_system')->distinct()->whereNotNull('body_system')->orderBy('body_system')->pluck('body_system');

        return view('symptom.create', compact('bodySystems'));
    }

    /**
     * Store a newly created symptom in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'body_system' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (Symptom::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $symptom = Symptom::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'body_system' => $validated['body_system'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('symptom.show', $symptom->slug)
            ->with('success', 'Symptom successfully recorded.');
    }

    /**
     * Display the specified symptom.
     *
     * @param string|int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $symptom = Symptom::where('slug', $id)
            ->orWhere('id', $id)
            ->with(['diseases.treatments.treatment_type'])
            ->firstOrFail();

        return view('symptom.show', compact('symptom'));
    }

    /**
     * Show the form for editing the specified symptom.
     *
     * @param string|int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $symptom = Symptom::where('slug', $id)->orWhere('id', $id)->firstOrFail();
        $bodySystems = Symptom::select('body_system')->distinct()->whereNotNull('body_system')->orderBy('body_system')->pluck('body_system');

        return view('symptom.edit', compact('symptom', 'bodySystems'));
    }

    /**
     * Update the specified symptom in storage.
     *
     * @param Request $request
     * @param string|int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $symptom = Symptom::where('slug', $id)->orWhere('id', $id)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'body_system' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $slug = $symptom->slug;
        if ($symptom->name !== $validated['name']) {
            $baseSlug = Str::slug($validated['name']);
            $slug = $baseSlug;
            $counter = 1;
            while (Symptom::where('slug', $slug)->where('id', '!=', $symptom->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
        }

        $symptom->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'body_system' => $validated['body_system'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('symptom.show', $symptom->slug)
            ->with('success', 'Symptom details updated successfully.');
    }

    /**
     * Remove the specified symptom from storage.
     *
     * @param string|int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $symptom = Symptom::where('slug', $id)->orWhere('id', $id)->firstOrFail();

        $symptom->diseases()->detach();
        $symptom->delete();

        return redirect()->route('symptom.index')
            ->with('success', 'Symptom deleted successfully.');
    }
}
