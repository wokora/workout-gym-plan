<?php

namespace Database\Seeders;

use App\Models\Meal\Type;
use Illuminate\Database\Seeder;

class MealTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['name' => 'Breakfast',  'number' => 1],
            ['name' => 'Snack',      'number' => 2],
            ['name' => 'Lunch',      'number' => 3],
            ['name' => 'Snack',      'number' => 4],
            ['name' => 'Dinner',     'number' => 5],
            ['name' => 'Additional', 'number' => 6],
        ];

        foreach ($types as $type) {
            Type::updateOrCreate(
                ['name' => $type['name'], 'number' => $type['number']],
                []
            );
        }
    }
}
