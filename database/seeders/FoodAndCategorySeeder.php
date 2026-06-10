<?php

namespace Database\Seeders;

use App\Models\Food\Category;
use App\Models\Food\Food;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class FoodAndCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonPath = database_path('data/foods.json');

        if (!File::exists($jsonPath)) {
            $this->command->error("JSON data file not found at: {$jsonPath}");
            return;
        }

        $data = json_decode(File::get($jsonPath), true);

        if (!$data || !isset($data['categories']) || !isset($data['foods'])) {
            $this->command->error("Invalid JSON format in: {$jsonPath}");
            return;
        }

        DB::transaction(function () use ($data) {
            // 1. Seed Categories
            $categoryMap = [];
            foreach ($data['categories'] as $categoryName) {
                $category = Category::updateOrCreate(
                    ['name' => $categoryName],
                    ['description' => null]
                );
                $categoryMap[$categoryName] = $category->id;
            }

            // 2. Seed Foods and relationships
            foreach ($data['foods'] as $foodData) {
                $food = Food::updateOrCreate(
                    ['code' => $foodData['code']],
                    [
                        'name' => $foodData['name'],
                        'energy_kj' => $foodData['energy_kj'],
                        'energy_kcal' => $foodData['energy_kcal'],
                        'water' => $foodData['water'],
                        'protein' => $foodData['protein'],
                        'fat' => $foodData['fat'],
                        'carbs' => $foodData['carbs'],
                        'fibre' => $foodData['fibre'],
                        'ca' => $foodData['ca'],
                        'mg' => $foodData['mg'],
                        'fe' => $foodData['fe'],
                        'na' => $foodData['na'],
                        'vit_a' => $foodData['vit_a'],
                        'folate' => $foodData['folate'],
                        'vit_c' => $foodData['vit_c'],
                        'standard_portion_description' => $foodData['standard_portion_description'] ?? null,
                        'standard_portion_grams' => $foodData['standard_portion_grams'] ?? null,
                    ]
                );

                // Clean existing relationships for this food
                $food->food_category()->forceDelete();

                // Associate with category
                if (isset($foodData['category']) && isset($categoryMap[$foodData['category']])) {
                    $food->food_category()->create([
                        'category_id' => $categoryMap[$foodData['category']]
                    ]);
                }
            }
        });

        $this->command->info('Foods and Categories seeded successfully!');
    }
}
