<?php

namespace App\Http\Controllers\Workout;

use App\Http\Controllers\Controller;
use App\Models\Workout\Exercise;
use App\Models\Workout\Workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExerciseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Workout $workout)
    {
        return view('workout.exercise.index', ['workout' => $workout]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Workout $workout)
    {
        return view('workout.exercise.create', ['workout' => $workout]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Workout $workout)
    {

        Validator::make($request->all(),
            [
                'exercise' => 'required|exists:exercise,id|unique:workout_exercise,exercise_id,NULL,id,workout_id,'.$workout->id,
                'sets' => 'required|numeric|min:1',
                'reps' => 'required|numeric|min:1',
            ],
            [
                'exercise.unique' => 'You already have this exercise in the workout'
            ]
        )->validate();

        $workout_excercise = $workout->workout_exercise()->create([
            'exercise_id' => $request->exercise,
            'sets' => $request->sets,
            'reps' => $request->reps
        ]);

        return redirect()->route('workout.exercise.show', [$workout->id, $workout_excercise->id])->with('success', 'Workout Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Workout\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function show(Workout $workout, Exercise $exercise)
    {
        return view('workout.exercise.view', ['workout_exercise' => $exercise]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Workout\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function edit(Workout $workout, Exercise $exercise)
    {
        return view('workout.exercise.view', ['workout_exercise' => $exercise]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Workout\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Workout $workout, Exercise $exercise)
    {
        Validator::make($request->all(),
            [
                'exercise' => 'required|exists:exercise,id|unique:workout_exercise,exercise_id,'.$exercise->id.',id,workout_id,'.$workout->id,
                'sets' => 'required|numeric|min:1',
                'reps' => 'required|numeric|min:1',
            ],
            [
                'exercise.unique' => 'You already have this exercise in the workout'
            ]
        )->validate();

        $exercise->exercise_id = $request->exercise;
        $exercise->sets = $request->sets;
        $exercise->reps = $request->reps;
        $exercise->save();

        return redirect()->route('workout.exercise.show', [$workout->id, $exercise->id])->with('success', 'Workout Updates');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Workout\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exercise $exercise)
    {
        //
    }
}
