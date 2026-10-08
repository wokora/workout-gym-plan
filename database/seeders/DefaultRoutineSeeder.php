<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workout\Workout;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultRoutineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::get();

        if ($users->isEmpty()) {
            $this->command->warn('No users found in database. Skipping routine template seeding.');
            return;
        }

        $routines = [
            [
                'name' => 'Push-Pull-Legs Split',
                'start_time' => '07:00:00',
                'end_time' => '08:30:00',
                'days' => [
                    [
                        'day_name' => 'Monday',
                        'description' => 'Targeting Chest, Shoulders, and Triceps (Pushing muscles).',
                        'exercises' => [
                            ['name' => 'Barbell Bench Press', 'sets' => 4, 'reps' => 8],
                            ['name' => 'Overhead Barbell Press', 'sets' => 4, 'reps' => 10],
                            ['name' => 'Incline Dumbbell Press', 'sets' => 3, 'reps' => 10],
                            ['name' => 'Dumbbell Lateral Raise', 'sets' => 3, 'reps' => 12],
                            ['name' => 'Tricep Pushdown', 'sets' => 3, 'reps' => 12],
                            ['name' => 'Skull Crusher', 'sets' => 3, 'reps' => 12]
                        ]
                    ],
                    [
                        'day_name' => 'Wednesday',
                        'description' => 'Targeting Back, Biceps, and Rear Delts (Pulling muscles).',
                        'exercises' => [
                            ['name' => 'Deadlift', 'sets' => 4, 'reps' => 5],
                            ['name' => 'Pull-Up', 'sets' => 4, 'reps' => 8],
                            ['name' => 'Bent-Over Barbell Row', 'sets' => 4, 'reps' => 10],
                            ['name' => 'Lat Pulldown', 'sets' => 3, 'reps' => 10],
                            ['name' => 'Dumbbell Bicep Curl', 'sets' => 3, 'reps' => 12],
                            ['name' => 'Hammer Curl', 'sets' => 3, 'reps' => 12]
                        ]
                    ],
                    [
                        'day_name' => 'Friday',
                        'description' => 'Targeting Quadriceps, Hamstrings, Glutes, Calves, and Core.',
                        'exercises' => [
                            ['name' => 'Back Squat', 'sets' => 4, 'reps' => 8],
                            ['name' => 'Leg Press', 'sets' => 3, 'reps' => 10],
                            ['name' => 'Lying Leg Curl', 'sets' => 3, 'reps' => 12],
                            ['name' => 'Standing Calf Raise', 'sets' => 4, 'reps' => 15],
                            ['name' => 'Plank', 'sets' => 3, 'reps' => 60, 'description' => 'Hold for 60 seconds.'],
                            ['name' => 'Russian Twist', 'sets' => 3, 'reps' => 20]
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Upper-Lower Split',
                'start_time' => '08:00:00',
                'end_time' => '09:30:00',
                'days' => [
                    [
                        'day_name' => 'Tuesday',
                        'description' => 'Targeting chest, back, shoulders, and arms.',
                        'exercises' => [
                            ['name' => 'Barbell Bench Press', 'sets' => 4, 'reps' => 8],
                            ['name' => 'Bent-Over Barbell Row', 'sets' => 4, 'reps' => 8],
                            ['name' => 'Seated Dumbbell Shoulder Press', 'sets' => 3, 'reps' => 10],
                            ['name' => 'Lat Pulldown', 'sets' => 3, 'reps' => 10],
                            ['name' => 'Preacher Curl', 'sets' => 3, 'reps' => 12],
                            ['name' => 'Tricep Pushdown', 'sets' => 3, 'reps' => 12]
                        ]
                    ],
                    [
                        'day_name' => 'Thursday',
                        'description' => 'Targeting thighs, hips, calves, and abs.',
                        'exercises' => [
                            ['name' => 'Back Squat', 'sets' => 4, 'reps' => 8],
                            ['name' => 'Romanian Deadlift', 'sets' => 4, 'reps' => 10],
                            ['name' => 'Leg Press', 'sets' => 3, 'reps' => 10],
                            ['name' => 'Seated Leg Curl', 'sets' => 3, 'reps' => 12],
                            ['name' => 'Seated Calf Raise', 'sets' => 4, 'reps' => 15],
                            ['name' => 'Hanging Knee Raise', 'sets' => 3, 'reps' => 15]
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Full-Body Routine',
                'start_time' => '18:00:00',
                'end_time' => '19:00:00',
                'days' => [
                    [
                        'day_name' => 'Wednesday',
                        'description' => 'Full body compound movements for strength and conditioning.',
                        'exercises' => [
                            ['name' => 'Back Squat', 'sets' => 3, 'reps' => 10],
                            ['name' => 'Barbell Bench Press', 'sets' => 3, 'reps' => 10],
                            ['name' => 'Pull-Up', 'sets' => 3, 'reps' => 8],
                            ['name' => 'Overhead Barbell Press', 'sets' => 3, 'reps' => 10],
                            ['name' => 'Dumbbell Bicep Curl', 'sets' => 3, 'reps' => 12],
                            ['name' => 'Plank', 'sets' => 3, 'reps' => 60, 'description' => 'Hold for 60 seconds.']
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Weight Reduction (Dumbbells & Curl Bar)',
                'start_time' => '06:00:00',
                'end_time' => '07:15:00',
                'days' => [
                    [
                        'day_name' => 'Monday',
                        'description' => 'High-intensity dumbbell compound and isolation movements for fat burn.',
                        'exercises' => [
                            ['name' => 'Flat Dumbbell Press', 'sets' => 4, 'reps' => 12],
                            ['name' => 'One-Arm Dumbbell Row', 'sets' => 4, 'reps' => 12],
                            ['name' => 'Dumbbell Lateral Raise', 'sets' => 3, 'reps' => 15],
                            ['name' => 'Dumbbell Bicep Curl', 'sets' => 3, 'reps' => 15],
                            ['name' => 'Dumbbell Kickback', 'sets' => 3, 'reps' => 15],
                            ['name' => 'Bulgarian Split Squat', 'sets' => 3, 'reps' => 12]
                        ]
                    ],
                    [
                        'day_name' => 'Wednesday',
                        'description' => 'Conditioning routine utilizing a 20 kg curl bar / barbell for muscular endurance.',
                        'exercises' => [
                            ['name' => 'Back Squat', 'sets' => 4, 'reps' => 12],
                            ['name' => 'Overhead Barbell Press', 'sets' => 4, 'reps' => 12],
                            ['name' => 'Bent-Over Barbell Row', 'sets' => 4, 'reps' => 12],
                            ['name' => 'Preacher Curl', 'sets' => 3, 'reps' => 15, 'description' => 'Use the 20 kg curl bar.'],
                            ['name' => 'Skull Crusher', 'sets' => 3, 'reps' => 15, 'description' => 'Use the 20 kg curl bar.'],
                            ['name' => 'Barbell Wrist Curl', 'sets' => 3, 'reps' => 15]
                        ]
                    ],
                    [
                        'day_name' => 'Friday',
                        'description' => 'Abs and core workout paired with high-energy dumbbell movements.',
                        'exercises' => [
                            ['name' => 'Plank', 'sets' => 3, 'reps' => 60, 'description' => 'Hold for 60 seconds.'],
                            ['name' => 'Russian Twist', 'sets' => 3, 'reps' => 25],
                            ['name' => 'Hanging Knee Raise', 'sets' => 3, 'reps' => 15],
                            ['name' => 'Farmer\'s Walk', 'sets' => 3, 'reps' => 30, 'description' => 'Walk 30 steps with heavy dumbbells.']
                        ]
                    ]
                ]
            ]
        ];

        DB::transaction(function () use ($users, $routines) {
            foreach ($users as $user) {
                foreach ($routines as $routine) {
                    // Update or create the workout plan
                    $workout = Workout::updateOrCreate([
                        'user_id' => $user->id,
                        'name' => $routine['name']
                    ], [
                        'start_time' => $routine['start_time'],
                        'end_time' => $routine['end_time']
                    ]);

                    // Clean out existing days for a fresh rewrite
                    $workout->workout_day()->forceDelete();

                    // Seed days & exercises
                    foreach ($routine['days'] as $dayIndex => $dayData) {
                        $dayRecord = \App\Models\Day\Day::where('name', $dayData['day_name'])->first();
                        if (!$dayRecord) continue;

                        $workoutDay = $workout->workout_day()->create([
                            'day_id' => $dayRecord->id,
                            'number' => $dayIndex + 1,
                            'description' => $dayData['description']
                        ]);

                        foreach ($dayData['exercises'] as $exIndex => $exData) {
                            // Find the seeded global exercise
                            $exerciseRecord = \App\Models\Exercise\Exercise::where('name', $exData['name'])->first();
                            if (!$exerciseRecord) continue;

                            $workoutDay->workout_exercise()->create([
                                'exercise_id' => $exerciseRecord->id,
                                'number' => $exIndex + 1,
                                'sets' => $exData['sets'],
                                'reps' => $exData['reps'],
                                'description' => $exData['description'] ?? null
                            ]);
                        }
                    }
                }
            }
        });

        $this->command->info('Default routine templates seeded successfully!');
    }
}
