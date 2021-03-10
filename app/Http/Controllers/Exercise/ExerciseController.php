<?php

namespace App\Http\Controllers\Exercise;

use App\Http\Controllers\Controller;
use App\Models\Exercise\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExerciseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('exercise.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('exercise.create');
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
                'name' => 'required|unique:exercise,name',
                'description' => 'nullable|max:255'
            ]
        )->validate();

        $exercise = new Exercise();
        $exercise->name = $request->name;
        $exercise->description = $request->description;
        $exercise->save();

        if($request->body) {
            foreach ($request->body as $body) {

                $exercise->body_section()->create([
                    'body_section_id' => $body
                ]);
            }
        }

        return redirect()->route('exercise.show', $exercise->id)->with('success', 'Exercise Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Exercise\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function show(Exercise $exercise)
    {
        return view('exercise.view', ['exercise' => $exercise]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Exercise\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function edit(Exercise $exercise)
    {
        return view('exercise.edit', ['exercise' => $exercise]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Exercise\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exercise $exercise)
    {
        Validator::make($request->all(),
            [
                'name' => 'required|unique:exercise,name,'.$exercise->id,
                'description' => 'nullable|max:255'
            ]
        )->validate();

        $exercise->name = $request->name;
        $exercise->description = $request->description;
        $exercise->save();

        $exercise->body_section()->delete();

        if($request->body) {
            foreach ($request->body as $body) {

                $exercise->body_section()->create([
                    'body_section_id' => $body
                ]);
            }
        }

        return redirect()->route('exercise.show', $exercise->id)->with('success', 'Exercise Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Exercise\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exercise $exercise)
    {
        $exercise->delete();

        return redirect()->route('exercise.index')->with('success', 'Exercise Deleted');
    }
}
