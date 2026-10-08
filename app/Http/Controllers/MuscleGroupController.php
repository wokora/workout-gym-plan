<?php

namespace App\Http\Controllers;

use App\Models\Body\Body;
use App\Models\Exercise\Exercise;
use Illuminate\Http\Request;

class MuscleGroupController extends Controller
{
    /**
     * Display the interactive Anatomical Muscle Groups exploration tool.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $selectedMuscle = $request->query('muscle');

        $bodySections = Body::orderBy('name')->get();

        $exercises = Exercise::with('body_section.body_section')
            ->orderBy('name')
            ->get();

        // Build structured map of exercises keyed by muscle name
        $exercisesByMuscle = [];
        foreach ($bodySections as $body) {
            $matchingExercises = $exercises->filter(function ($ex) use ($body) {
                return $ex->body_section->contains(function ($section) use ($body) {
                    return $section->body_section_id === $body->id;
                });
            })->values();

            $exercisesByMuscle[$body->name] = $matchingExercises->map(function ($ex) {
                return [
                    'id' => $ex->id,
                    'name' => $ex->name,
                    'description' => $ex->description,
                    'view_url' => route('exercise.show', $ex->id),
                    'body_sections' => $ex->targetedMuscleNames(),
                ];
            })->all();
        }

        return view('muscle-groups.index', compact('bodySections', 'exercises', 'exercisesByMuscle', 'selectedMuscle'));
    }
}
