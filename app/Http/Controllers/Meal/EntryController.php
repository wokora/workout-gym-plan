<?php

namespace App\Http\Controllers\Meal;

use App\Http\Controllers\Controller;
use App\Models\Meal\Day;
use App\Models\Meal\Entry;
use App\Models\Meal\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EntryController extends Controller
{
    public function index(Plan $meal, Day $day)
    {
        return view('meal.entry.index', ['meal' => $meal, 'day' => $day]);
    }

    public function create(Plan $meal, Day $day)
    {
        return view('meal.entry.create', ['meal' => $meal, 'day' => $day]);
    }

    public function store(Request $request, Plan $meal, Day $day)
    {
        Validator::make($request->all(),
            [
                'foods' => 'required|array|min:1',
                'foods.*' => 'exists:food,id',
                'meal_type' => 'required|exists:meal_type,id',
                'description' => 'nullable',
            ],
            [
                'foods.required' => 'Select at least one food.',
                'foods.min' => 'Select at least one food.',
            ]
        )->validate();

        $entry_count = $day->entry()->count();

        $entry = $day->entry()->create([
            'meal_type_id' => $request->meal_type,
            'number' => $entry_count + 1,
            'description' => $request->description,
        ]);

        foreach ($request->foods as $food_id) {
            $entry->foods()->create(['food_id' => $food_id]);
        }

        return redirect()->route('meal.day.entry.show', [$meal->id, $day->id, $entry->id])->with('success', 'Entry Created');
    }

    public function show(Plan $meal, Day $day, Entry $entry)
    {
        return view('meal.entry.view', ['entry' => $entry]);
    }

    public function edit(Plan $meal, Day $day, Entry $entry)
    {
        return view('meal.entry.edit', ['entry' => $entry]);
    }

    public function update(Request $request, Plan $meal, Day $day, Entry $entry)
    {
        Validator::make($request->all(),
            [
                'foods' => 'required|array|min:1',
                'foods.*' => 'exists:food,id',
                'meal_type' => 'required|exists:meal_type,id',
                'description' => 'nullable',
            ],
            [
                'foods.required' => 'Select at least one food.',
                'foods.min' => 'Select at least one food.',
            ]
        )->validate();

        $entry->meal_type_id = $request->meal_type;
        $entry->description = $request->description;
        $entry->save();

        $entry->foods()->forceDelete();
        foreach ($request->foods as $food_id) {
            $entry->foods()->create(['food_id' => $food_id]);
        }

        return redirect()->route('meal.day.entry.show', [$meal->id, $day->id, $entry->id])->with('success', 'Entry Updated');
    }

    public function destroy(Plan $meal, Day $day, Entry $entry)
    {
        $entry->delete();
        return redirect()->route('meal.day.show', [$meal->id, $day->id])->with('success', 'Entry Deleted');
    }
}
