<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call( DayTableSeeder::class );
        $this->call( ExerciseAndBodySeeder::class );
        $this->call( DefaultRoutineSeeder::class );
        $this->call( FoodAndCategorySeeder::class );
        $this->call( MealTypeSeeder::class );
    }
}
