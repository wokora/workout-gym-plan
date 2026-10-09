<?php

namespace App\Http\Controllers\Meal;

use App\Http\Controllers\Controller;
use App\Http\Resources\MealPlanResource;
use App\Models\Day\Day;
use App\Models\Meal\Plan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a listing of user meal plans.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $plans = Plan::where('user_id', $request->user()->id)
            ->with(['meal_plan_day.day', 'meal_plan_day.entry.meal_type', 'meal_plan_day.entry.foods.food'])
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        return response()->json([
            'records' => MealPlanResource::collection($plans),
            'pagination' => [
                'current_page' => $plans->currentPage(),
                'last_page'    => $plans->lastPage(),
                'per_page'     => $plans->perPage(),
                'total'        => $plans->total(),
            ]
        ]);
    }

    /**
     * Store a newly created meal plan in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $daysInput = $request->input('day', $request->input('days', []));
        $request->merge(['day' => $daysInput]);

        $request->validate([
            'name' => 'required|string|max:255',
            'day' => 'required|array|min:1',
            'day.*' => 'exists:day,id',
        ]);

        $plan = new Plan();
        $plan->user_id = $request->user()->id;
        $plan->name = $request->name;
        $plan->save();

        if ($request->has('day')) {
            foreach ($request->day as $dayId) {
                $dayModel = Day::find($dayId);
                if ($dayModel) {
                    $plan->meal_plan_day()->create([
                        'day_id' => $dayModel->id,
                        'number' => $dayModel->number,
                    ]);
                }
            }
        }

        $plan->load(['meal_plan_day.day', 'meal_plan_day.entry.meal_type']);

        return response()->json([
            'message' => 'Meal plan created successfully.',
            'meal_plan' => new MealPlanResource($plan),
        ], 201);
    }

    /**
     * Display the specified meal plan.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $plan = Plan::where('user_id', $request->user()->id)
            ->with(['meal_plan_day.day', 'meal_plan_day.entry.meal_type', 'meal_plan_day.entry.foods.food'])
            ->findOrFail($id);

        return response()->json([
            'meal_plan' => new MealPlanResource($plan),
        ]);
    }

    /**
     * Update the specified meal plan in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $plan = Plan::where('user_id', $request->user()->id)->findOrFail($id);

        if ($request->has('day') || $request->has('days')) {
            $daysInput = $request->input('day', $request->input('days', []));
            $request->merge(['day' => $daysInput]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'day' => 'nullable|array',
            'day.*' => 'exists:day,id',
        ]);

        $plan->name = $request->name;
        $plan->save();

        if ($request->has('day')) {
            $plan->meal_plan_day()->whereNotIn('day_id', $request->day)->delete();

            foreach ($request->day as $dayId) {
                if ($plan->meal_plan_day()->where('day_id', $dayId)->count() === 0) {
                    $dayModel = Day::find($dayId);
                    if ($dayModel) {
                        $plan->meal_plan_day()->create([
                            'day_id' => $dayModel->id,
                            'number' => $dayModel->number,
                        ]);
                    }
                }
            }
        }

        $plan->load(['meal_plan_day.day', 'meal_plan_day.entry.meal_type']);

        return response()->json([
            'message' => 'Meal plan updated successfully.',
            'meal_plan' => new MealPlanResource($plan),
        ]);
    }

    /**
     * Remove the specified meal plan from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $plan = Plan::where('user_id', $request->user()->id)->findOrFail($id);
        $plan->delete();

        return response()->json([
            'message' => 'Meal plan deleted successfully.',
        ]);
    }
}
