<?php

namespace App\Http\Controllers\Workout;

use App\Http\Controllers\Controller;
use App\Models\Workout\Workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WorkoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('workout.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('workout.create');
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
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'day' => 'required|exists:day,id',
                'start_time' => 'required',
                'end_time' => 'required'
            ]
        )->validate();

        $workout = new Workout();
        $workout->user_id = Auth::id();
        $workout->name = $request->name;
        $workout->day_id = $request->day;
        $workout->start_date = $request->start_date;
        $workout->end_date = $request->end_date;
        $workout->start_time = $request->start_time;
        $workout->end_time = $request->end_time;
        $workout->save();

        return redirect()->route('workout.show', $workout->id)->with('success', 'Workout Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Workout\Workout  $workout
     * @return \Illuminate\Http\Response
     */
    public function show(Workout $workout)
    {
        return view('workout.view', ['workout' => $workout]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Workout\Workout  $workout
     * @return \Illuminate\Http\Response
     */
    public function edit(Workout $workout)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Workout\Workout  $workout
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Workout $workout)
    {
        Validator::make($request->all(),
            [
                'name' => 'required',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'day' => 'required|exists:day,id',
                'start_time' => 'required',
                'end_time' => 'required'
            ]
        )->validate();

        $workout->name = $request->name;
        $workout->day_id = $request->day;
        $workout->start_date = $request->start_date;
        $workout->end_date = $request->end_date;
        $workout->start_time = $request->start_time;
        $workout->end_time = $request->end_time;
        $workout->save();

        return redirect()->route('workout.show', $workout->id)->with('success', 'Workout Created');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Workout\Workout  $workout
     * @return \Illuminate\Http\Response
     */
    public function destroy(Workout $workout)
    {
        //
    }
}
