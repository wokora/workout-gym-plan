<?php

namespace Database\Seeders;

use App\Models\Disease\Symptom;
use Illuminate\Database\Seeder;

class SymptomTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $symptoms = [
            // Cardiovascular
            [
                'name' => 'High Blood Pressure Spikes',
                'slug' => 'high-blood-pressure-spikes',
                'description' => 'Systolic or diastolic arterial pressure readings exceeding 130/80 mmHg consistently.',
                'body_system' => 'Cardiovascular',
                'severity_indication' => 'Moderate',
            ],
            [
                'name' => 'Chest Pain & Angina',
                'slug' => 'chest-pain-angina',
                'description' => 'Discomfort, pressure, tightness or squeezing sensation across the precordium or retrosternal region.',
                'body_system' => 'Cardiovascular',
                'severity_indication' => 'Severe',
            ],
            [
                'name' => 'Heart Palpitations & Arrhythmia',
                'slug' => 'heart-palpitations-arrhythmia',
                'description' => 'Sensations of racing, pounding, fluttering, or irregular cardiac rhythm.',
                'body_system' => 'Cardiovascular',
                'severity_indication' => 'Moderate',
            ],

            // Metabolic & Endocrine
            [
                'name' => 'Excessive Thirst (Polydipsia)',
                'slug' => 'excessive-thirst-polydipsia',
                'description' => 'Abnormal, unquenchable urge to consume fluids despite adequate hydration.',
                'body_system' => 'Metabolic',
                'severity_indication' => 'Moderate',
            ],
            [
                'name' => 'Frequent Urination (Polyuria)',
                'slug' => 'frequent-urination-polyuria',
                'description' => 'Production of abnormally large volumes of urine, particularly waking up multiple times at night.',
                'body_system' => 'Metabolic',
                'severity_indication' => 'Moderate',
            ],
            [
                'name' => 'Unexplained Fatigue & Energy Crashes',
                'slug' => 'unexplained-fatigue-energy-crashes',
                'description' => 'Persistent exhaustion that does not resolve with adequate sleep, often acute post-prandially.',
                'body_system' => 'Metabolic',
                'severity_indication' => 'Mild',
            ],
            [
                'name' => 'Brain Fog & Poor Focus',
                'slug' => 'brain-fog-poor-focus',
                'description' => 'Feelings of mental confusion, lack of clarity, forgetfulness, and inability to concentrate.',
                'body_system' => 'Neurological',
                'severity_indication' => 'Mild',
            ],

            // Musculoskeletal
            [
                'name' => 'Joint Stiffness & Morning Pain',
                'slug' => 'joint-stiffness-morning-pain',
                'description' => 'Aching, crepitus, and rigidity in joints upon waking or following prolonged periods of immobility.',
                'body_system' => 'Musculoskeletal',
                'severity_indication' => 'Moderate',
            ],
            [
                'name' => 'Reduced Joint Range of Motion',
                'slug' => 'reduced-joint-range-of-motion',
                'description' => 'Inability to fully flex, extend, or rotate an anatomical joint without pain or friction.',
                'body_system' => 'Musculoskeletal',
                'severity_indication' => 'Moderate',
            ],
            [
                'name' => 'Muscle Cramps & Spasms',
                'slug' => 'muscle-cramps-spasms',
                'description' => 'Involuntary, intensely painful sustained contractions of muscle groups.',
                'body_system' => 'Musculoskeletal',
                'severity_indication' => 'Mild',
            ],

            // Gastrointestinal
            [
                'name' => 'Heartburn & Acid Regurgitation',
                'slug' => 'heartburn-acid-regurgitation',
                'description' => 'Burning retrosternal discomfort provoked by gastric acid flowing upward into the esophagus.',
                'body_system' => 'Gastrointestinal',
                'severity_indication' => 'Moderate',
            ],
            [
                'name' => 'Epigastric Bloating & Fullness',
                'slug' => 'epigastric-bloating-fullness',
                'description' => 'Distention, gas accumulation, and discomfort in the upper abdomen following meals.',
                'body_system' => 'Gastrointestinal',
                'severity_indication' => 'Mild',
            ],

            // Respiratory
            [
                'name' => 'Shortness of Breath on Exertion (Dyspnea)',
                'slug' => 'shortness-of-breath-on-exertion',
                'description' => 'Difficulty breathing or sensation of air hunger disproportionate to the level of physical exertion.',
                'body_system' => 'Respiratory',
                'severity_indication' => 'Severe',
            ],
            [
                'name' => 'Chronic Wheezing & Bronchospasm',
                'slug' => 'chronic-wheezing-bronchospasm',
                'description' => 'High-pitched whistling sound produced during breathing, particularly on expiration.',
                'body_system' => 'Respiratory',
                'severity_indication' => 'Moderate',
            ],
            [
                'name' => 'Persistent Night Cough',
                'slug' => 'persistent-night-cough',
                'description' => 'Irritating reflexive coughing fits that occur predominantly when lying down or during sleep.',
                'body_system' => 'Respiratory',
                'severity_indication' => 'Mild',
            ],

            // Neurological & Sleep
            [
                'name' => 'Throbbing Unilateral Headache',
                'slug' => 'throbbing-unilateral-headache',
                'description' => 'Pulsating cranial pain often accompanied by photophobia, phonophobia, or sensory aura.',
                'body_system' => 'Neurological',
                'severity_indication' => 'Severe',
            ],
            [
                'name' => 'Insomnia & Sleep Disruption',
                'slug' => 'insomnia-sleep-disruption',
                'description' => 'Difficulty initiating or maintaining restorative nocturnal sleep stages.',
                'body_system' => 'Neurological',
                'severity_indication' => 'Moderate',
            ],
        ];

        foreach ($symptoms as $symptom) {
            Symptom::updateOrCreate(['slug' => $symptom['slug']], $symptom);
        }
    }
}
