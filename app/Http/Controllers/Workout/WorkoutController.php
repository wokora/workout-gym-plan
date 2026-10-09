<?php

namespace App\Http\Controllers\Workout;

use App\Http\Controllers\Controller;
use App\Http\Resources\WorkoutResource;
use App\Models\Day\Day;
use App\Models\Workout\Workout;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkoutController extends Controller
{
    /**
     * Display a listing of user workouts.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $workouts = Workout::where('user_id', $request->user()->id)
            ->with(['workout_day.day', 'workout_day.workout_exercise.exercise.body_section.body_section'])
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        return response()->json([
            'records' => WorkoutResource::collection($workouts),
            'pagination' => [
                'current_page' => $workouts->currentPage(),
                'last_page'    => $workouts->lastPage(),
                'per_page'     => $workouts->perPage(),
                'total'        => $workouts->total(),
            ]
        ]);
    }

    /**
     * Store a newly created workout in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $daysInput = $request->input('day', $request->input('days', []));
        $request->merge(['day' => $daysInput]);

        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'day' => 'required|array|min:1',
            'day.*' => 'exists:day,id',
        ]);

        $workout = new Workout();
        $workout->user_id = $request->user()->id;
        $workout->name = $request->name;
        $workout->start_time = $request->start_time;
        $workout->end_time = $request->end_time;
        $workout->save();

        if ($request->has('day')) {
            foreach ($request->day as $dayId) {
                $dayModel = Day::find($dayId);
                if ($dayModel) {
                    $workout->workout_day()->create([
                        'day_id' => $dayModel->id,
                        'number' => $dayModel->number,
                    ]);
                }
            }
        }

        $workout->load(['workout_day.day', 'workout_day.workout_exercise.exercise']);

        return response()->json([
            'message' => 'Workout created successfully.',
            'workout' => new WorkoutResource($workout),
        ], 201);
    }

    /**
     * Display the specified workout.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $workout = Workout::where('user_id', $request->user()->id)
            ->with(['workout_day.day', 'workout_day.workout_exercise.exercise.body_section.body_section'])
            ->findOrFail($id);

        return response()->json([
            'workout' => new WorkoutResource($workout),
        ]);
    }

    /**
     * Update the specified workout in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $workout = Workout::where('user_id', $request->user()->id)->findOrFail($id);

        if ($request->has('day') || $request->has('days')) {
            $daysInput = $request->input('day', $request->input('days', []));
            $request->merge(['day' => $daysInput]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'day' => 'nullable|array',
            'day.*' => 'exists:day,id',
        ]);

        $workout->name = $request->name;
        $workout->start_time = $request->start_time;
        $workout->end_time = $request->end_time;
        $workout->save();

        if ($request->has('day')) {
            $workout->workout_day()->whereNotIn('day_id', $request->day)->delete();

            foreach ($request->day as $dayId) {
                if ($workout->workout_day()->where('day_id', $dayId)->count() === 0) {
                    $dayModel = Day::find($dayId);
                    if ($dayModel) {
                        $workout->workout_day()->create([
                            'day_id' => $dayModel->id,
                            'number' => $dayModel->number,
                        ]);
                    }
                }
            }
        }

        $workout->load(['workout_day.day', 'workout_day.workout_exercise.exercise']);

        return response()->json([
            'message' => 'Workout updated successfully.',
            'workout' => new WorkoutResource($workout),
        ]);
    }

    /**
     * Remove the specified workout from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $workout = Workout::where('user_id', $request->user()->id)->findOrFail($id);
        $workout->delete();

        return response()->json([
            'message' => 'Workout deleted successfully.',
        ]);
    }
}
