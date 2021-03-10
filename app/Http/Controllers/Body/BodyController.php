<?php

namespace App\Http\Controllers\Body;

use App\Http\Controllers\Controller;
use App\Models\Body\Body;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BodyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('body.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('body.create');
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
                'name' => 'required|unique:body_section,name',
                'description' => 'nullable|max:255'
            ]
        )->validate();

        $body = new Body();
        $body->name = $request->name;
        $body->description = $request->description;
        $body->save();

        return redirect()->route('body.show', $body->id)->with('success', 'Body section created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Body\Body  $body
     * @return \Illuminate\Http\Response
     */
    public function show(Body $body)
    {
        return view('body.view', ['body' => $body]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Body\Body  $body
     * @return \Illuminate\Http\Response
     */
    public function edit(Body $body)
    {
        return view('body.edit', ['body' => $body]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Body\Body  $body
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Body $body)
    {
        Validator::make($request->all(),
            [
                'name' => 'required|unique:body_section,name,'.$body->id,
                'description' => 'nullable|max:255'
            ]
        )->validate();

        $body->name = $request->name;
        $body->description = $request->description;
        $body->save();

        return redirect()->route('body.show', $body->id)->with('success', 'Body section updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Body\Body  $body
     * @return \Illuminate\Http\Response
     */
    public function destroy(Body $body)
    {
        //
    }
}
