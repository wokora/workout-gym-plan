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
        $this->call( GenderTableSeeder::class );
        $this->call( ExerciseAndBodySeeder::class );
        $this->call( DefaultRoutineSeeder::class );
        $this->call( FoodAndCategorySeeder::class );
        $this->call( MealTypeSeeder::class );
        $this->call( VitalUnitSeeder::class );
        $this->call( InputTypeSeeder::class );
        $this->call( VitalSeeder::class );
        $this->call( FileTypeTableSeeder::class );
        $this->call( FileStatusTableSeeder::class );
        $this->call( FileDimensionTableSeeder::class );
        $this->call( DiseaseCategoryTableSeeder::class );
        $this->call( TreatmentTypeTableSeeder::class );
        $this->call( EvidenceLevelTableSeeder::class );
        $this->call( SymptomTableSeeder::class );
        $this->call( ClinicalDiseaseAndTreatmentSeeder::class );
    }
}
