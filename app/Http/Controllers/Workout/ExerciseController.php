<?php

namespace App\Http\Controllers\Workout;

use App\Http\Controllers\Controller;
use App\Http\Resources\WorkoutExerciseResource;
use App\Models\Workout\Day;
use App\Models\Workout\Exercise;
use App\Models\Workout\Workout;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    /**
     * Display a listing of exercises for a workout day.
     */
    public function index(Request $request, $workoutId, $dayId): JsonResponse
    {
        $workout = Workout::where('user_id', $request->user()->id)->findOrFail($workoutId);
        $day = $workout->workout_day()->findOrFail($dayId);

        $exercises = $day->workout_exercise()
            ->with('exercise.body_section.body_section')
            ->orderBy('number')
            ->get();

        return response()->json([
            'records' => WorkoutExerciseResource::collection($exercises),
        ]);
    }

    /**
     * Store a newly created exercise in the workout day.
     */
    public function store(Request $request, $workoutId, $dayId): JsonResponse
    {
        $workout = Workout::where('user_id', $request->user()->id)->findOrFail($workoutId);
        $day = $workout->workout_day()->findOrFail($dayId);

        $request->validate([
            'exercise_id' => [
                'required',
                'exists:exercise,id',
                function ($attribute, $value, $fail) use ($day) {
                    if ($day->workout_exercise()->where('exercise_id', $value)->exists()) {
                        $fail('This exercise is already added to this day routine.');
                    }
                }
            ],
            'sets' => 'required|numeric|min:1',
            'reps' => 'required|numeric|min:1',
            'number' => 'nullable|integer',
        ]);

        $count = $day->workout_exercise()->count();

        $workoutExercise = $day->workout_exercise()->create([
            'exercise_id' => $request->exercise_id,
            'number' => $request->number ?? ($count + 1),
            'sets' => $request->sets,
            'reps' => $request->reps,
        ]);

        $workoutExercise->load('exercise.body_section.body_section');

        return response()->json([
            'message' => 'Exercise added to workout day.',
            'exercise' => new WorkoutExerciseResource($workoutExercise),
        ], 201);
    }

    /**
     * Update the exercise details (sets, reps, order).
     */
    public function update(Request $request, $workoutId, $dayId, $exerciseId): JsonResponse
    {
        $workout = Workout::where('user_id', $request->user()->id)->findOrFail($workoutId);
        $day = $workout->workout_day()->findOrFail($dayId);
        $workoutExercise = $day->workout_exercise()->findOrFail($exerciseId);

        $request->validate([
            'sets' => 'sometimes|numeric|min:1',
            'reps' => 'sometimes|numeric|min:1',
            'number' => 'sometimes|integer',
        ]);

        $workoutExercise->update($request->only('sets', 'reps', 'number'));
        $workoutExercise->load('exercise.body_section.body_section');

        return response()->json([
            'message' => 'Exercise updated successfully.',
            'exercise' => new WorkoutExerciseResource($workoutExercise),
        ]);
    }

    /**
     * Remove the specified exercise from the workout day.
     */
    public function destroy(Request $request, $workoutId, $dayId, $exerciseId): JsonResponse
    {
        $workout = Workout::where('user_id', $request->user()->id)->findOrFail($workoutId);
        $day = $workout->workout_day()->findOrFail($dayId);
        $workoutExercise = $day->workout_exercise()->findOrFail($exerciseId);

        $workoutExercise->delete();

        return response()->json([
            'message' => 'Exercise removed from workout day successfully.',
        ]);
    }
}
