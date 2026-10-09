<?php

namespace Database\Seeders;

use App\Models\Disease\DiseaseCategory;
use Illuminate\Database\Seeder;

class DiseaseCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Cardiovascular & Circulatory Disorders',
                'slug' => 'cardiovascular-circulatory',
                'description' => 'Disorders affecting the heart, blood vessels, arterial elasticity, and systemic circulation.',
                'icon' => 'fa-heartbeat',
                'color' => '#ef4444',
            ],
            [
                'name' => 'Metabolic & Endocrine Disorders',
                'slug' => 'metabolic-endocrine',
                'description' => 'Conditions involving hormonal balance, glucose regulation, thyroid function, and metabolic pathways.',
                'icon' => 'fa-dna',
                'color' => '#f59e0b',
            ],
            [
                'name' => 'Musculoskeletal & Joint Disorders',
                'slug' => 'musculoskeletal-joint',
                'description' => 'Ailments of the bones, synovial joints, ligaments, tendons, spine, and muscle tissue.',
                'icon' => 'fa-bone',
                'color' => '#3b82f6',
            ],
            [
                'name' => 'Gastrointestinal & Digestive Health',
                'slug' => 'gastrointestinal-digestive',
                'description' => 'Disorders of the esophagus, stomach, intestines, liver, gallbladder, and digestive microbiome.',
                'icon' => 'fa-utensils',
                'color' => '#10b981',
            ],
            [
                'name' => 'Respiratory & Pulmonary Disorders',
                'slug' => 'respiratory-pulmonary',
                'description' => 'Conditions impacting the lungs, bronchial airways, oxygen saturation, and breathing mechanics.',
                'icon' => 'fa-lungs',
                'color' => '#06b6d4',
            ],
            [
                'name' => 'Neurological & Cognitive Health',
                'slug' => 'neurological-cognitive',
                'description' => 'Disorders of the central and peripheral nervous systems, neurochemistry, sleep, and brain function.',
                'icon' => 'fa-brain',
                'color' => '#8b5cf6',
            ],
            [
                'name' => 'Immune & Inflammatory Conditions',
                'slug' => 'immune-inflammatory',
                'description' => 'Disorders of immune dysregulation, chronic systemic inflammation, and autoimmune responses.',
                'icon' => 'fa-shield-virus',
                'color' => '#ec4899',
            ],
        ];

        foreach ($categories as $category) {
            DiseaseCategory::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
