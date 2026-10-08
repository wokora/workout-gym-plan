<?php

namespace App\Http\Controllers\Meal;

use App\Http\Controllers\Controller;
use App\Models\Day\Day;
use App\Models\Meal\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('meal.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('meal.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(),
            [
                'name' => 'required',
                'day' => 'required|array'
            ]
        )->validate();

        $plan = new Plan();
        $plan->user_id = Auth::id();
        $plan->name = $request->name;
        $plan->save();

        if($request->day) {

            foreach ($request->day as $day) {
                $day = Day::find($day);
                $plan->meal_plan_day()->create([
                    'day_id' => $day->id,
                    'number' => $day->number
                ]);
            }
        }


        return redirect()->route('meal.show', $plan->id)->with('success', 'Meal Plan Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Meal\Plan  $meal
     * @return \Illuminate\Http\Response
     */
    public function show(Plan $meal)
    {
        return view('meal.view', ['meal' => $meal]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Meal\Plan  $meal
     * @return \Illuminate\Http\Response
     */
    public function edit(Plan $meal)
    {
        return view('meal.edit', ['meal' => $meal]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Meal\Plan  $meal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plan $meal)
    {
        Validator::make($request->all(),
            [
                'name' => 'required',
                'day' => 'required|exists:day,id'
            ]
        )->validate();

        $meal->name = $request->name;
        $meal->save();

        $meal->meal_plan_day()->whereNotIn('day_id', $request->day)->delete();

        if($request->day) {

            foreach ($request->day as $day) {

                if ($meal->meal_plan_day()->where('day_id', $day)->count() == 0) {
                    $day = Day::find($day);
                    $meal->meal_plan_day()->create(['day_id' => $day->id, 'number' => $day->number]);
                }

            }
        }

        return redirect()->route('meal.show', $meal->id)->with('success', 'Meal Plan Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Meal\Plan  $meal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan $meal)
    {
        $meal->delete();

        return redirect()->route('meal.index')->with('success', 'Meal Plan Deleted');
    }
}
