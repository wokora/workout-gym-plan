<?php

namespace App\Http\Controllers\Vital;

use App\Http\Controllers\Controller;
use App\Models\Vital\Vital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VitalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vitals = Auth::user()->vitals()->orderBy('date_measured', 'ASC')->get();

        return view('vitals.index', ['vitals' => $vitals]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('vitals.create');
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
                'date' => 'required|date',
                'weight' => 'required|numeric',
                'height' => 'required|numeric'
            ]
        )->validate();

        $user_vital = Auth::user()->vitals()->create([
            'weight' => $request->weight,
            'height' => $request->height,
            'date_measured' => $request->date
        ]);

        return redirect()->route('vital.show', $user_vital->id)->with('success', 'Vital Recorded');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vital\Vital  $vitals
     * @return \Illuminate\Http\Response
     */
    public function show(Vital $vital)
    {
        return view('vitals.view', ['vital' => $vital]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vitals\Vitals  $vitals
     * @return \Illuminate\Http\Response
     */
    public function edit(Vital $vital)
    {
        return view('vitals.edit', ['vital' => $vital]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vitals\Vitals  $vitals
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vital $vital)
    {
        Validator::make($request->all(),
            [
                'date' => 'required|date',
                'weight' => 'required|numeric',
                'height' => 'required|numeric'
            ]
        )->validate();

        $vital->height = $request->height;
        $vital->weight = $request->weight;
        $vital->date_measured = $request->date;
        $vital->save();


        return redirect()->route('vital.show', $vital->id)->with('success', 'Vital Updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vitals\Vitals  $vitals
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vital $vital)
    {
        $vital->delete();

        return redirect()->route('vital.index')->with('success', 'Vital Deleted');
    }
}
