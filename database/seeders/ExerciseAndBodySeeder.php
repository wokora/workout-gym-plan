<?php

namespace Database\Seeders;

use App\Models\Body\Body as BodySection;
use App\Models\Exercise\Exercise;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ExerciseAndBodySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonPath = database_path('data/exercises.json');

        if (!File::exists($jsonPath)) {
            $this->command->error("JSON data file not found at: {$jsonPath}");
            return;
        }

        $data = json_decode(File::get($jsonPath), true);

        if (!$data || !isset($data['body_sections']) || !isset($data['exercises'])) {
            $this->command->error("Invalid JSON format in: {$jsonPath}");
            return;
        }

        DB::transaction(function () use ($data) {
            // 1. Seed Body Sections
            $bodySectionMap = [];
            foreach ($data['body_sections'] as $section) {
                $bodySection = BodySection::updateOrCreate(
                    ['name' => $section['name']],
                    ['description' => $section['description']]
                );
                // Keep track of IDs for mapping
                $bodySectionMap[$section['name']] = $bodySection->id;
            }

            // 2. Seed Exercises and relationships
            foreach ($data['exercises'] as $exerciseData) {
                $exercise = Exercise::updateOrCreate(
                    ['name' => $exerciseData['name']],
                    ['description' => $exerciseData['description']]
                );

                // Clean existing relationships for this exercise
                $exercise->body_section()->forceDelete();

                // Associate with targeted body sections
                if (isset($exerciseData['body_sections'])) {
                    foreach ($exerciseData['body_sections'] as $sectionName) {
                        if (isset($bodySectionMap[$sectionName])) {
                            $exercise->body_section()->create([
                                'body_section_id' => $bodySectionMap[$sectionName]
                            ]);
                        }
                    }
                }
            }
        });

        $this->command->info('Exercises and Body Sections seeded successfully!');
    }
}
