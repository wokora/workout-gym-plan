<?php

namespace App\Http\Controllers;

use App\Http\Resources\BodySectionResource;
use App\Models\Body\Body;
use App\Models\Exercise\Exercise;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MuscleGroupController extends Controller
{
    /**
     * Display the interactive Anatomical Muscle Groups and mapped exercises as JSON.
     */
    public function index(Request $request): JsonResponse
    {
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
                    'targeted_muscles' => $ex->targetedMuscleNames(),
                ];
            })->all();
        }

        return response()->json([
            'body_sections' => BodySectionResource::collection($bodySections),
            'exercises_by_muscle' => $exercisesByMuscle,
        ]);
    }
}
