<?php

namespace Database\Seeders;

use App\Models\File\FileStatus;
use Illuminate\Database\Seeder;

class FileStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'Pending Processing'],
            ['name' => 'Processed'],
            ['name' => 'Failed'],
        ];

        foreach ($statuses as $status) {
            FileStatus::updateOrCreate(['name' => $status['name']], $status);
        }
    }
}
