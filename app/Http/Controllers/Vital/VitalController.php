<?php

namespace App\Http\Controllers\Vital;

use App\Http\Controllers\Controller;
use App\Models\Vital\UserVital;
use App\Models\Vital\Vital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VitalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $vitals = $user->vitals()
            ->with(['vital.unit', 'vital.inputType'])
            ->orderBy('date_measured', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $latestWeight = $user->latestVital('weight');
        $latestHeight = $user->latestVital('height');
        $latestHeartRate = $user->latestVital('heart_rate');
        $latestBpSystolic = $user->latestVital('bp_systolic');
        $latestBpDiastolic = $user->latestVital('bp_diastolic');
        $latestBodyFat = $user->latestVital('body_fat');
        $bmi = $user->bmi();

        return view('vitals.index', [
            'vitals' => $vitals,
            'latestWeight' => $latestWeight,
            'latestHeight' => $latestHeight,
            'latestHeartRate' => $latestHeartRate,
            'latestBpSystolic' => $latestBpSystolic,
            'latestBpDiastolic' => $latestBpDiastolic,
            'latestBodyFat' => $latestBodyFat,
            'bmi' => $bmi,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $activeVitals = Vital::with(['unit', 'inputType'])
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $latestValues = [];
        foreach ($activeVitals as $v) {
            $latest = Auth::user()->latestVital($v->slug);
            $latestValues[$v->id] = $latest ? $latest->value : null;
        }

        return view('vitals.create', [
            'vitals' => $activeVitals,
            'latestValues' => $latestValues,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        // Single vital submitted via radio button
        if ($request->has('vital_id')) {
            $request->validate([
                'vital_id' => 'required|exists:vitals,id',
                'value' => 'required|numeric',
            ]);

            Auth::user()->vitals()->updateOrCreate(
                [
                    'vital_id' => $request->vital_id,
                    'date_measured' => $request->date,
                ],
                [
                    'value' => $request->value,
                ]
            );

            if ($request->input('action') === 'save_and_new') {
                return redirect()->route('vital.create')->with('success', 'Vital recorded successfully. Ready to record another.');
            }

            return redirect()->route('vital.index')->with('success', 'Vital recorded successfully.');
        }

        // Fallback: batch vitals array
        if ($request->has('vitals')) {
            $request->validate([
                'vitals' => 'required|array',
            ]);

            $saved = 0;
            foreach ($request->input('vitals', []) as $vitalId => $value) {
                if ($value !== null && $value !== '') {
                    Auth::user()->vitals()->updateOrCreate(
                        [
                            'vital_id' => $vitalId,
                            'date_measured' => $request->date,
                        ],
                        [
                            'value' => $value,
                        ]
                    );
                    $saved++;
                }
            }

            if ($saved === 0) {
                return back()->withErrors(['value' => 'Please enter at least one vital measurement.'])->withInput();
            }

            return redirect()->route('vital.index')->with('success', 'Vitals recorded successfully.');
        }

        return back()->withErrors(['value' => 'Please select a vital and enter a valid measurement.'])->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  mixed  $vital
     * @return \Illuminate\Http\Response
     */
    public function show($vital)
    {
        $userVital = Auth::user()->vitals()->with(['vital.unit', 'vital.inputType'])->findOrFail($vital);

        return view('vitals.view', ['vital' => $userVital]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  mixed  $vital
     * @return \Illuminate\Http\Response
     */
    public function edit($vital)
    {
        $userVital = Auth::user()->vitals()->with(['vital.unit', 'vital.inputType'])->findOrFail($vital);

        return view('vitals.edit', ['vital' => $userVital]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $vital
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $vital)
    {
        $userVital = Auth::user()->vitals()->findOrFail($vital);

        $request->validate([
            'date' => 'required|date',
            'value' => 'required|numeric',
        ]);

        $userVital->update([
            'date_measured' => $request->date,
            'value' => $request->value,
        ]);

        return redirect()->route('vital.show', $userVital->id)->with('success', 'Vital updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  mixed  $vital
     * @return \Illuminate\Http\Response
     */
    public function destroy($vital)
    {
        $userVital = Auth::user()->vitals()->findOrFail($vital);
        $userVital->delete();

        return redirect()->route('vital.index')->with('success', 'Vital deleted successfully.');
    }

    /**
     * Export vitals to a downloadable JSON file.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        $vitals = Auth::user()->vitals()
            ->with(['vital.unit'])
            ->orderBy('date_measured', 'asc')
            ->get()
            ->map(function ($uv) {
                return [
                    'vital' => $uv->vital ? $uv->vital->slug : null,
                    'name' => $uv->vital ? $uv->vital->name : null,
                    'value' => $uv->value,
                    'unit' => $uv->vital && $uv->vital->unit ? $uv->vital->unit->symbol : null,
                    'date_measured' => $uv->date_measured ? $uv->date_measured->format('Y-m-d') : null,
                ];
            });

        $json = $vitals->toJson(JSON_PRETTY_PRINT);
        $fileName = 'vitals_' . date('Y-m-d') . '.json';

        return response($json, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    /**
     * Import vitals from an uploaded JSON file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $request->validate([
            'vitals_file' => 'required|file|mimes:json,txt',
        ]);

        $content = file_get_contents($request->file('vitals_file')->getRealPath());
        $data = json_decode($content, true);

        if (!is_array($data)) {
            return redirect()->route('vital.index')->withErrors(['vitals_file' => 'Invalid JSON structure. Must be an array of records.']);
        }

        $weightVital = Vital::where('slug', 'weight')->first();
        $heightVital = Vital::where('slug', 'height')->first();
        $user = Auth::user();
        $imported = 0;

        foreach ($data as $entry) {
            // New normalized format
            if (isset($entry['vital']) && isset($entry['value']) && isset($entry['date_measured'])) {
                $vital = Vital::where('slug', $entry['vital'])->first();
                if ($vital) {
                    $date = date('Y-m-d', strtotime($entry['date_measured']));
                    $user->vitals()->updateOrCreate(
                        ['vital_id' => $vital->id, 'date_measured' => $date],
                        ['value' => floatval($entry['value'])]
                    );
                    $imported++;
                }
            }
            // Support legacy format if provided
            elseif (isset($entry['date_measured'])) {
                $date = date('Y-m-d', strtotime($entry['date_measured']));
                if (isset($entry['weight']) && $weightVital) {
                    $user->vitals()->updateOrCreate(
                        ['vital_id' => $weightVital->id, 'date_measured' => $date],
                        ['value' => floatval($entry['weight'])]
                    );
                    $imported++;
                }
                if (isset($entry['height']) && $heightVital) {
                    $user->vitals()->updateOrCreate(
                        ['vital_id' => $heightVital->id, 'date_measured' => $date],
                        ['value' => floatval($entry['height'])]
                    );
                    $imported++;
                }
            }
        }

        return redirect()->route('vital.index')->with('success', "Imported {$imported} vitals successfully.");
    }
}
