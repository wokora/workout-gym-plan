<?php

namespace Database\Seeders;

use App\Models\File\FileDimension;
use Illuminate\Database\Seeder;

class FileDimensionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dimensions = [
            [
                'name' => 'Thumbnail',
                'slug' => 'thumbnail',
                'width' => 150,
                'height' => 150,
            ],
            [
                'name' => 'Medium',
                'slug' => 'medium',
                'width' => 400,
                'height' => 300,
            ],
            [
                'name' => 'Large',
                'slug' => 'large',
                'width' => 800,
                'height' => 600,
            ],
            [
                'name' => 'Square',
                'slug' => 'square',
                'width' => 300,
                'height' => 300,
            ],
        ];

        foreach ($dimensions as $dimension) {
            FileDimension::updateOrCreate(['slug' => $dimension['slug']], $dimension);
        }
    }
}
