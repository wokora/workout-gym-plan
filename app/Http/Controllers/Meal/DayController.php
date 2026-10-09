<?php

namespace App\Http\Controllers\Meal;

use App\Http\Controllers\Controller;
use App\Http\Resources\MealPlanDayResource;
use App\Models\Meal\Day;
use App\Models\Meal\Plan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DayController extends Controller
{
    /**
     * Display a listing of days for a meal plan.
     */
    public function index(Request $request, $mealId): JsonResponse
    {
        $plan = Plan::where('user_id', $request->user()->id)->findOrFail($mealId);

        $days = $plan->meal_plan_day()
            ->with(['day', 'entry.meal_type', 'entry.foods.food'])
            ->orderBy('number')
            ->get();

        return response()->json([
            'records' => MealPlanDayResource::collection($days),
        ]);
    }

    /**
     * Display the specified day in a meal plan.
     */
    public function show(Request $request, $mealId, $dayId): JsonResponse
    {
        $plan = Plan::where('user_id', $request->user()->id)->findOrFail($mealId);

        $day = $plan->meal_plan_day()
            ->with(['day', 'entry.meal_type', 'entry.foods.food'])
            ->findOrFail($dayId);

        return response()->json([
            'day' => new MealPlanDayResource($day),
        ]);
    }

    /**
     * Add a day to a meal plan.
     */
    public function store(Request $request, $mealId): JsonResponse
    {
        $plan = Plan::where('user_id', $request->user()->id)->findOrFail($mealId);

        $request->validate([
            'day_id' => 'required|exists:day,id',
            'number' => 'nullable|integer',
        ]);

        $dayModel = \App\Models\Day\Day::findOrFail($request->day_id);

        $day = $plan->meal_plan_day()->create([
            'day_id' => $dayModel->id,
            'number' => $request->number ?? $dayModel->number,
        ]);

        $day->load(['day', 'entry.meal_type']);

        return response()->json([
            'message' => 'Day added to meal plan.',
            'day' => new MealPlanDayResource($day),
        ], 201);
    }

    /**
     * Remove a day from a meal plan.
     */
    public function destroy(Request $request, $mealId, $dayId): JsonResponse
    {
        $plan = Plan::where('user_id', $request->user()->id)->findOrFail($mealId);
        $day = $plan->meal_plan_day()->findOrFail($dayId);
        $day->delete();

        return response()->json([
            'message' => 'Day removed from meal plan successfully.',
        ]);
    }
}
