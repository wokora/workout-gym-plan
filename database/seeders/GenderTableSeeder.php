<?php

namespace Database\Seeders;

use App\Models\Gender;
use Illuminate\Database\Seeder;

class GenderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genders = [
            ['id' => 1, 'name' => 'Male', 'slug' => 'male'],
            ['id' => 2, 'name' => 'Female', 'slug' => 'female'],
            ['id' => 3, 'name' => 'Other', 'slug' => 'other'],
            ['id' => 4, 'name' => 'Prefer not to say', 'slug' => 'prefer_not_to_say'],
        ];

        foreach ($genders as $item) {
            Gender::updateOrCreate(
                ['id' => $item['id']],
                [
                    'name' => $item['name'],
                    'slug' => $item['slug'],
                ]
            );
        }
    }
}
