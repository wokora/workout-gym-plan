<?php

namespace App\Http\Controllers\Workout;

use App\Http\Controllers\Controller;
use App\Http\Resources\WorkoutDayResource;
use App\Models\Workout\Day;
use App\Models\Workout\Workout;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DayController extends Controller
{
    /**
     * Display a listing of days for a workout.
     */
    public function index(Request $request, $workoutId): JsonResponse
    {
        $workout = Workout::where('user_id', $request->user()->id)->findOrFail($workoutId);

        $days = $workout->workout_day()
            ->with(['day', 'workout_exercise.exercise.body_section.body_section'])
            ->orderBy('number')
            ->get();

        return response()->json([
            'records' => WorkoutDayResource::collection($days),
        ]);
    }

    /**
     * Display the specified day.
     */
    public function show(Request $request, $workoutId, $dayId): JsonResponse
    {
        $workout = Workout::where('user_id', $request->user()->id)->findOrFail($workoutId);

        $day = $workout->workout_day()
            ->with(['day', 'workout_exercise.exercise.body_section.body_section'])
            ->findOrFail($dayId);

        return response()->json([
            'day' => new WorkoutDayResource($day),
        ]);
    }

    /**
     * Store a newly created day in a workout routine.
     */
    public function store(Request $request, $workoutId): JsonResponse
    {
        $workout = Workout::where('user_id', $request->user()->id)->findOrFail($workoutId);

        $request->validate([
            'day_id' => 'required|exists:day,id',
            'number' => 'nullable|integer',
        ]);

        $dayModel = \App\Models\Day\Day::findOrFail($request->day_id);

        $day = $workout->workout_day()->create([
            'day_id' => $dayModel->id,
            'number' => $request->number ?? $dayModel->number,
        ]);

        $day->load(['day', 'workout_exercise.exercise']);

        return response()->json([
            'message' => 'Day added to workout routine.',
            'day' => new WorkoutDayResource($day),
        ], 201);
    }

    /**
     * Remove the specified day from a workout routine.
     */
    public function destroy(Request $request, $workoutId, $dayId): JsonResponse
    {
        $workout = Workout::where('user_id', $request->user()->id)->findOrFail($workoutId);
        $day = $workout->workout_day()->findOrFail($dayId);
        $day->delete();

        return response()->json([
            'message' => 'Day removed from workout successfully.',
        ]);
    }
}
