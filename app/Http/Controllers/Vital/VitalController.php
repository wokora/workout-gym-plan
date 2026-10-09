<?php

namespace App\Http\Controllers\Vital;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserVitalResource;
use App\Http\Resources\VitalResource;
use App\Models\Vital\UserVital;
use App\Models\Vital\Vital;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VitalController extends Controller
{
    /**
     * Display a listing of recorded user vitals and active vital definitions.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $perPage = (int) $request->input('per_page', 20);
        $vitals = $user->vitals()
            ->with(['vital.unit', 'vital.inputType'])
            ->orderBy('date_measured', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        $activeVitals = Vital::with(['unit', 'inputType'])
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $latestWeight = $user->latestVital('weight');
        $latestHeight = $user->latestVital('height');
        $latestHeartRate = $user->latestVital('heart_rate');
        $latestBpSystolic = $user->latestVital('bp_systolic');
        $latestBpDiastolic = $user->latestVital('bp_diastolic');
        $latestBodyFat = $user->latestVital('body_fat');

        return response()->json([
            'records' => UserVitalResource::collection($vitals),
            'active_vitals' => VitalResource::collection($activeVitals),
            'latest' => [
                'weight' => $latestWeight ? $latestWeight->value : null,
                'height' => $latestHeight ? $latestHeight->value : null,
                'heart_rate' => $latestHeartRate ? $latestHeartRate->value : null,
                'bp_systolic' => $latestBpSystolic ? $latestBpSystolic->value : null,
                'bp_diastolic' => $latestBpDiastolic ? $latestBpDiastolic->value : null,
                'body_fat' => $latestBodyFat ? $latestBodyFat->value : null,
                'bmi' => $user->bmi(),
            ],
            'pagination' => [
                'current_page' => $vitals->currentPage(),
                'last_page'    => $vitals->lastPage(),
                'per_page'     => $vitals->perPage(),
                'total'        => $vitals->total(),
            ]
        ]);
    }

    /**
     * Store or update a newly created vital reading.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'date_measured' => 'required|date',
            'vital_id' => 'required|exists:vitals,id',
            'value' => 'required|numeric',
        ]);

        $userVital = $request->user()->vitals()->updateOrCreate(
            [
                'vital_id' => $request->vital_id,
                'date_measured' => $request->date_measured,
            ],
            [
                'value' => $request->value,
            ]
        );

        $userVital->load(['vital.unit']);

        return response()->json([
            'message' => 'Vital measurement recorded successfully.',
            'vital' => new UserVitalResource($userVital),
        ], 201);
    }

    /**
     * Display the specified vital record.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $userVital = $request->user()->vitals()
            ->with(['vital.unit', 'vital.inputType'])
            ->findOrFail($id);

        return response()->json([
            'vital' => new UserVitalResource($userVital),
        ]);
    }

    /**
     * Update a vital measurement.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $userVital = $request->user()->vitals()->findOrFail($id);

        $request->validate([
            'value' => 'required|numeric',
            'date_measured' => 'sometimes|date',
        ]);

        $userVital->update($request->only('value', 'date_measured'));
        $userVital->load(['vital.unit']);

        return response()->json([
            'message' => 'Vital measurement updated successfully.',
            'vital' => new UserVitalResource($userVital),
        ]);
    }

    /**
     * Remove the specified vital record.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $userVital = $request->user()->vitals()->findOrFail($id);
        $userVital->delete();

        return response()->json([
            'message' => 'Vital measurement deleted successfully.',
        ]);
    }

    /**
     * Export all user vitals as JSON.
     */
    public function export(Request $request): JsonResponse
    {
        $vitals = $request->user()->vitals()
            ->with('vital')
            ->orderBy('date_measured', 'desc')
            ->get();

        $data = $vitals->map(function ($item) {
            return [
                'vital' => $item->vital ? $item->vital->slug : null,
                'name' => $item->vital ? $item->vital->name : null,
                'value' => $item->value,
                'date_measured' => $item->date_measured ? $item->date_measured->format('Y-m-d') : null,
            ];
        });

        return response()->json([
            'exported_at' => now()->toIso8601String(),
            'records' => $data,
        ]);
    }

    /**
     * Import vitals from a JSON file upload or JSON payload.
     */
    public function import(Request $request): JsonResponse
    {
        $records = [];

        if ($request->hasFile('vitals_file')) {
            $file = $request->file('vitals_file');
            $content = file_get_contents($file->getRealPath());
            $decoded = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'message' => 'The uploaded file is not a valid JSON document.'
                ], 422);
            }

            if (isset($decoded['records']) && is_array($decoded['records'])) {
                $records = $decoded['records'];
            } elseif (is_array($decoded)) {
                $records = $decoded;
            }
        } elseif ($request->has('records') && is_array($request->input('records'))) {
            $records = $request->input('records');
        }

        if (empty($records) || !is_array($records)) {
            return response()->json([
                'message' => 'No valid vitals records found to import. Please upload a JSON file with vitals records.'
            ], 422);
        }

        $imported = 0;
        foreach ($records as $row) {
            if (!is_array($row)) continue;

            $vitalIdentifier = $row['vital'] ?? ($row['slug'] ?? ($row['name'] ?? null));
            $vitalId = $row['vital_id'] ?? null;
            $value = $row['value'] ?? null;
            $dateMeasured = $row['date_measured'] ?? ($row['date'] ?? null);

            if ($value === null || empty($dateMeasured)) continue;

            $vitalDef = null;
            if ($vitalId) {
                $vitalDef = Vital::find($vitalId);
            } elseif ($vitalIdentifier) {
                $vitalDef = Vital::where('slug', $vitalIdentifier)
                    ->orWhere('name', $vitalIdentifier)
                    ->first();
            }

            if ($vitalDef) {
                $request->user()->vitals()->updateOrCreate(
                    [
                        'vital_id' => $vitalDef->id,
                        'date_measured' => date('Y-m-d', strtotime($dateMeasured)),
                    ],
                    [
                        'value' => floatval($value),
                    ]
                );
                $imported++;
            }
        }

        return response()->json([
            'message' => "Successfully imported {$imported} vital records.",
            'imported_count' => $imported,
        ]);
    }
}
