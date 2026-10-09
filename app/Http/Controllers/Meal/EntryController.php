<?php

namespace App\Http\Controllers\Meal;

use App\Http\Controllers\Controller;
use App\Http\Resources\MealPlanEntryResource;
use App\Models\Meal\Day;
use App\Models\Meal\Entry;
use App\Models\Meal\Plan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    /**
     * Resolve a meal plan day by either meal_plan_day.id or day_id.
     */
    protected function getPlanDay(Plan $plan, $dayId)
    {
        return $plan->meal_plan_day()
            ->where(function ($q) use ($dayId) {
                $q->where('id', $dayId)->orWhere('day_id', $dayId);
            })
            ->firstOrFail();
    }

    /**
     * Display a listing of entries for a meal plan day.
     */
    public function index(Request $request, $mealId, $dayId): JsonResponse
    {
        $plan = Plan::where('user_id', $request->user()->id)->findOrFail($mealId);
        $day = $this->getPlanDay($plan, $dayId);

        $entries = $day->entry()
            ->with(['meal_type', 'foods.food'])
            ->orderBy('number')
            ->get();

        return response()->json([
            'records' => MealPlanEntryResource::collection($entries),
        ]);
    }

    /**
     * Store a newly created entry in a meal plan day.
     */
    public function store(Request $request, $mealId, $dayId): JsonResponse
    {
        $plan = Plan::where('user_id', $request->user()->id)->findOrFail($mealId);
        $day = $this->getPlanDay($plan, $dayId);

        // Normalize foods array to scalar IDs if sent as objects
        if ($request->has('foods') && is_array($request->foods)) {
            $normalizedFoods = array_map(function ($item) {
                if (is_array($item)) {
                    return $item['food_id'] ?? ($item['id'] ?? null);
                }
                return $item;
            }, $request->foods);
            $request->merge(['foods' => array_values(array_filter($normalizedFoods))]);
        }

        $request->validate([
            'meal_type_id' => 'required|exists:meal_type,id',
            'description' => 'nullable|string',
            'foods' => 'required|array|min:1',
            'foods.*' => 'exists:food,id',
        ]);

        $entryCount = $day->entry()->count();

        $entry = $day->entry()->create([
            'meal_type_id' => $request->meal_type_id,
            'number' => $entryCount + 1,
            'description' => $request->description,
        ]);

        foreach ($request->foods as $foodId) {
            $entry->foods()->create(['food_id' => $foodId]);
        }

        $entry->load(['meal_type', 'foods.food']);

        return response()->json([
            'message' => 'Meal entry created successfully.',
            'entry' => new MealPlanEntryResource($entry),
        ], 201);
    }

    /**
     * Display the specified meal entry.
     */
    public function show(Request $request, $mealId, $dayId, $entryId): JsonResponse
    {
        $plan = Plan::where('user_id', $request->user()->id)->findOrFail($mealId);
        $day = $this->getPlanDay($plan, $dayId);
        $entry = $day->entry()->with(['meal_type', 'foods.food'])->findOrFail($entryId);

        return response()->json([
            'entry' => new MealPlanEntryResource($entry),
        ]);
    }

    /**
     * Update the specified meal entry.
     */
    public function update(Request $request, $mealId, $dayId, $entryId): JsonResponse
    {
        $plan = Plan::where('user_id', $request->user()->id)->findOrFail($mealId);
        $day = $this->getPlanDay($plan, $dayId);
        $entry = $day->entry()->findOrFail($entryId);

        // Normalize foods array to scalar IDs if sent as objects
        if ($request->has('foods') && is_array($request->foods)) {
            $normalizedFoods = array_map(function ($item) {
                if (is_array($item)) {
                    return $item['food_id'] ?? ($item['id'] ?? null);
                }
                return $item;
            }, $request->foods);
            $request->merge(['foods' => array_values(array_filter($normalizedFoods))]);
        }

        $request->validate([
            'meal_type_id' => 'sometimes|exists:meal_type,id',
            'description' => 'nullable|string',
            'foods' => 'sometimes|array|min:1',
            'foods.*' => 'exists:food,id',
        ]);

        $entry->update($request->only('meal_type_id', 'description'));

        if ($request->has('foods')) {
            $entry->foods()->delete();
            foreach ($request->foods as $foodId) {
                $entry->foods()->create(['food_id' => $foodId]);
            }
        }

        $entry->load(['meal_type', 'foods.food']);

        return response()->json([
            'message' => 'Meal entry updated successfully.',
            'entry' => new MealPlanEntryResource($entry),
        ]);
    }

    /**
     * Remove the specified meal entry.
     */
    public function destroy(Request $request, $mealId, $dayId, $entryId): JsonResponse
    {
        $plan = Plan::where('user_id', $request->user()->id)->findOrFail($mealId);
        $day = $this->getPlanDay($plan, $dayId);
        $entry = $day->entry()->findOrFail($entryId);

        $entry->foods()->delete();
        $entry->delete();

        return response()->json([
            'message' => 'Meal entry deleted successfully.',
        ]);
    }
}
