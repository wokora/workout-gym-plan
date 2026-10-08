<?php

namespace Database\Seeders;

use App\Models\Vital\InputType;
use App\Models\Vital\Unit;
use App\Models\Vital\Vital;
use Illuminate\Database\Seeder;

class VitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kg = Unit::where('symbol', 'kg')->first();
        $cm = Unit::where('symbol', 'cm')->first();
        $bpm = Unit::where('symbol', 'bpm')->first();
        $mmHg = Unit::where('symbol', 'mmHg')->first();
        $percent = Unit::where('symbol', '%')->first();

        $decimal = InputType::where('code', 'decimal')->first();
        $integer = InputType::where('code', 'integer')->first();

        $vitals = [
            [
                'name' => 'Weight',
                'slug' => 'weight',
                'unit_id' => $kg ? $kg->id : null,
                'input_type_id' => $decimal ? $decimal->id : null,
                'description' => 'Body weight measurement',
                'min_value' => 1.00,
                'max_value' => 500.00,
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'Height',
                'slug' => 'height',
                'unit_id' => $cm ? $cm->id : null,
                'input_type_id' => $decimal ? $decimal->id : null,
                'description' => 'Standing height measurement',
                'min_value' => 30.00,
                'max_value' => 300.00,
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Resting Heart Rate',
                'slug' => 'heart_rate',
                'unit_id' => $bpm ? $bpm->id : null,
                'input_type_id' => $integer ? $integer->id : null,
                'description' => 'Resting heart rate in beats per minute',
                'min_value' => 30.00,
                'max_value' => 250.00,
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name' => 'Blood Pressure (Systolic)',
                'slug' => 'bp_systolic',
                'unit_id' => $mmHg ? $mmHg->id : null,
                'input_type_id' => $integer ? $integer->id : null,
                'description' => 'Systolic blood pressure (upper reading)',
                'min_value' => 50.00,
                'max_value' => 250.00,
                'is_active' => true,
                'order' => 4,
            ],
            [
                'name' => 'Blood Pressure (Diastolic)',
                'slug' => 'bp_diastolic',
                'unit_id' => $mmHg ? $mmHg->id : null,
                'input_type_id' => $integer ? $integer->id : null,
                'description' => 'Diastolic blood pressure (lower reading)',
                'min_value' => 30.00,
                'max_value' => 160.00,
                'is_active' => true,
                'order' => 5,
            ],
            [
                'name' => 'Body Fat Percentage',
                'slug' => 'body_fat',
                'unit_id' => $percent ? $percent->id : null,
                'input_type_id' => $decimal ? $decimal->id : null,
                'description' => 'Estimated body fat percentage',
                'min_value' => 1.00,
                'max_value' => 80.00,
                'is_active' => true,
                'order' => 6,
            ],
        ];

        foreach ($vitals as $vital) {
            Vital::updateOrCreate(
                ['slug' => $vital['slug']],
                $vital
            );
        }
    }
}
