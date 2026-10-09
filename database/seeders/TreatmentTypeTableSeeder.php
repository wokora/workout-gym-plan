<?php

namespace Database\Seeders;

use App\Models\Disease\TreatmentType;
use Illuminate\Database\Seeder;

class TreatmentTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Pharmacotherapy / Medication',
                'slug' => 'pharmacotherapy',
                'description' => 'Prescription and over-the-counter pharmaceutical drugs and targeted medications.',
                'icon' => 'fa-pills',
                'badge_color' => 'purple',
            ],
            [
                'name' => 'Lifestyle & Dietary Modification',
                'slug' => 'lifestyle-diet',
                'description' => 'Nutritional protocols, therapeutic diets, sleep optimization, and daily habit adjustments.',
                'icon' => 'fa-apple-alt',
                'badge_color' => 'success',
            ],
            [
                'name' => 'Physical Therapy & Exercise',
                'slug' => 'physical-therapy-exercise',
                'description' => 'Targeted rehabilitative routines, aerobic conditioning, resistance training, and mobility work.',
                'icon' => 'fa-running',
                'badge_color' => 'info',
            ],
            [
                'name' => 'Herbal, Botanical & Nutraceutical',
                'slug' => 'herbal-nutraceutical',
                'description' => 'Herbal extracts, vitamins, minerals, adaptogens, and clinically studied natural compounds.',
                'icon' => 'fa-leaf',
                'badge_color' => 'emerald',
            ],
            [
                'name' => 'Psychotherapy & Behavioral',
                'slug' => 'psychotherapy-behavioral',
                'description' => 'Cognitive behavioral therapy, stress management, meditation, and mental health interventions.',
                'icon' => 'fa-brain',
                'badge_color' => 'warning',
            ],
            [
                'name' => 'Surgical & Procedural',
                'slug' => 'surgical-procedural',
                'description' => 'Interventional procedures, surgery, minimally invasive clinical therapies.',
                'icon' => 'fa-syringe',
                'badge_color' => 'error',
            ],
            [
                'name' => 'Home Remedy & Supportive Care',
                'slug' => 'home-remedy-supportive',
                'description' => 'Hydration, steam inhalation, postural positioning, hot/cold therapy, and comfort care.',
                'icon' => 'fa-home',
                'badge_color' => 'secondary',
            ],
        ];

        foreach ($types as $type) {
            TreatmentType::updateOrCreate(['slug' => $type['slug']], $type);
        }
    }
}
