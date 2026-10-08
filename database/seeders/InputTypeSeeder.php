<?php

namespace Database\Seeders;

use App\Models\Vital\InputType;
use Illuminate\Database\Seeder;

class InputTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['name' => 'Decimal Number', 'code' => 'decimal', 'step' => '0.01'],
            ['name' => 'Whole Number', 'code' => 'integer', 'step' => '1'],
            ['name' => 'Text', 'code' => 'text', 'step' => null],
        ];

        foreach ($types as $type) {
            InputType::updateOrCreate(
                ['code' => $type['code']],
                [
                    'name' => $type['name'],
                    'step' => $type['step'],
                ]
            );
        }
    }
}
