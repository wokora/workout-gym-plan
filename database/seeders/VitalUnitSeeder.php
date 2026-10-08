<?php

namespace Database\Seeders;

use App\Models\Vital\Unit;
use Illuminate\Database\Seeder;

class VitalUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = [
            ['name' => 'Kilogram', 'symbol' => 'kg', 'category' => 'mass'],
            ['name' => 'Pound', 'symbol' => 'lbs', 'category' => 'mass'],
            ['name' => 'Centimeter', 'symbol' => 'cm', 'category' => 'length'],
            ['name' => 'Inch', 'symbol' => 'in', 'category' => 'length'],
            ['name' => 'Beats per Minute', 'symbol' => 'bpm', 'category' => 'frequency'],
            ['name' => 'Millimeters of Mercury', 'symbol' => 'mmHg', 'category' => 'pressure'],
            ['name' => 'Percentage', 'symbol' => '%', 'category' => 'percentage'],
            ['name' => 'Milligrams per Deciliter', 'symbol' => 'mg/dL', 'category' => 'concentration'],
            ['name' => 'Millimoles per Liter', 'symbol' => 'mmol/L', 'category' => 'concentration'],
        ];

        foreach ($units as $unit) {
            Unit::updateOrCreate(
                ['symbol' => $unit['symbol']],
                [
                    'name' => $unit['name'],
                    'category' => $unit['category'],
                ]
            );
        }
    }
}
