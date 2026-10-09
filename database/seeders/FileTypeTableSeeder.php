<?php

namespace Database\Seeders;

use App\Models\File\FileType;
use Illuminate\Database\Seeder;

class FileTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Image'],
            ['name' => 'Audio'],
            ['name' => 'Video'],
            ['name' => 'Document'],
        ];

        foreach ($types as $type) {
            FileType::updateOrCreate(['name' => $type['name']], $type);
        }
    }
}
