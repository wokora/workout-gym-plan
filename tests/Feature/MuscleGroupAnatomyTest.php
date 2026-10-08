<?php

namespace Tests\Feature;

use App\Models\Body\Body;
use App\Models\Day\Day as DayLookup;
use App\Models\Exercise\Body as ExerciseBody;
use App\Models\Exercise\Exercise;
use App\Models\User;
use App\Models\Workout\Day;
use App\Models\Workout\Exercise as WorkoutExercise;
use App\Models\Workout\Workout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MuscleGroupAnatomyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\DayTableSeeder::class);
    }

    public function testMuscleGroupsExplorerPageLoadsSuccessfully(): void
    {
        $user = User::factory()->create();

        $chest = Body::create(['name' => 'Chest', 'description' => 'Pectoralis major and minor']);
        $exercise = Exercise::create([
            'name' => 'Bench Press',
            'description' => 'Lie on bench and push barbell',
        ]);
        ExerciseBody::create([
            'exercise_id' => $exercise->id,
            'body_section_id' => $chest->id,
        ]);

        $response = $this->actingAs($user)->get(route('muscle-groups.index'));

        $response->assertStatus(200);
        $response->assertSee('Muscle Groups &amp; Anatomy Explorer', false);
        $response->assertSee('Bench Press');
        $response->assertSee('Chest');
        $response->assertSee('muscle-map-container');
    }

    public function testExerciseViewDisplaysAnatomicalBodyMuscleMap(): void
    {
        $user = User::factory()->create();

        $chest = Body::create(['name' => 'Chest', 'description' => 'Pectoralis']);
        $triceps = Body::create(['name' => 'Triceps', 'description' => 'Triceps brachii']);

        $exercise = Exercise::create([
            'name' => 'Dips',
            'description' => 'Bodyweight dips on parallel bars',
        ]);

        ExerciseBody::create(['exercise_id' => $exercise->id, 'body_section_id' => $chest->id]);
        ExerciseBody::create(['exercise_id' => $exercise->id, 'body_section_id' => $triceps->id]);

        $this->assertEquals(['Chest', 'Triceps'], $exercise->targetedMuscleNames());

        $response = $this->actingAs($user)->get(route('exercise.show', $exercise->id));

        $response->assertStatus(200);
        $response->assertSee('Dips');
        $response->assertSee('Target Muscle Groups');
        $response->assertSee('muscle-map-container');
        $response->assertSee('Chest');
        $response->assertSee('Triceps');
    }

    public function testWorkoutViewDisplaysCombinedRoutineMuscleMap(): void
    {
        $user = User::factory()->create();

        $chest = Body::create(['name' => 'Chest']);
        $shoulders = Body::create(['name' => 'Shoulders']);

        $ex1 = Exercise::create(['name' => 'Push Ups']);
        ExerciseBody::create(['exercise_id' => $ex1->id, 'body_section_id' => $chest->id]);

        $ex2 = Exercise::create(['name' => 'Overhead Press']);
        ExerciseBody::create(['exercise_id' => $ex2->id, 'body_section_id' => $shoulders->id]);

        $workout = Workout::create([
            'user_id' => $user->id,
            'name' => 'Upper Push Day',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'start_time' => '07:00:00',
            'end_time' => '08:00:00',
        ]);

        $monday = DayLookup::where('number', 1)->first();
        $workoutDay = Day::create([
            'workout_id' => $workout->id,
            'day_id' => $monday->id,
            'number' => 1,
        ]);

        WorkoutExercise::create([
            'workout_day_id' => $workoutDay->id,
            'exercise_id' => $ex1->id,
            'number' => 1,
            'sets' => 3,
            'reps' => 10,
        ]);

        WorkoutExercise::create([
            'workout_day_id' => $workoutDay->id,
            'exercise_id' => $ex2->id,
            'number' => 2,
            'sets' => 3,
            'reps' => 8,
        ]);

        $this->assertContains('Chest', $workout->targetedMuscleNames());
        $this->assertContains('Shoulders', $workout->targetedMuscleNames());

        $response = $this->actingAs($user)->get(route('workout.show', $workout->id));

        $response->assertStatus(200);
        $response->assertSee('Upper Push Day');
        $response->assertSee('muscle-map-container');
        $response->assertSee('Chest');
        $response->assertSee('Shoulders');
    }

    public function testWorkoutDayAndExerciseViewsDisplayMuscleMap(): void
    {
        $user = User::factory()->create();

        $back = Body::create(['name' => 'Back']);
        $biceps = Body::create(['name' => 'Biceps']);

        $exercise = Exercise::create(['name' => 'Pull-Up']);
        ExerciseBody::create(['exercise_id' => $exercise->id, 'body_section_id' => $back->id]);
        ExerciseBody::create(['exercise_id' => $exercise->id, 'body_section_id' => $biceps->id]);

        $workout = Workout::create([
            'user_id' => $user->id,
            'name' => 'Pull Day',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'start_time' => '07:00:00',
            'end_time' => '08:00:00',
        ]);

        $wednesday = DayLookup::where('number', 3)->first();
        $workoutDay = Day::create([
            'workout_id' => $workout->id,
            'day_id' => $wednesday->id,
            'number' => 1,
        ]);

        $we = WorkoutExercise::create([
            'workout_day_id' => $workoutDay->id,
            'exercise_id' => $exercise->id,
            'number' => 1,
            'sets' => 4,
            'reps' => 8,
        ]);

        // Test Workout Day View
        $dayResponse = $this->actingAs($user)->get(route('workout.day.show', [$workout->id, $workoutDay->id]));
        $dayResponse->assertStatus(200);
        $dayResponse->assertSee('Daily Muscle Focus');
        $dayResponse->assertSee('muscle-map-container');
        $dayResponse->assertSee('Back');
        $dayResponse->assertSee('Biceps');

        // Test Workout Exercise View
        $exerciseResponse = $this->actingAs($user)->get(route('workout.day.exercise.show', [$workout->id, $workoutDay->id, $we->id]));
        $exerciseResponse->assertStatus(200);
        $exerciseResponse->assertSee('Target Muscle Groups');
        $exerciseResponse->assertSee('muscle-map-container');
        $exerciseResponse->assertSee('Back');
        $exerciseResponse->assertSee('Biceps');
    }
}
