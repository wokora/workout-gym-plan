<?php

namespace Database\Seeders;

use App\Models\Day\Day;
use Illuminate\Database\Seeder;

class DayTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $days = [
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday'
        ];

        foreach ($days as $key => $day){
            $new = new Day();
            $new->name = $day;
            $new->number = $key + 1;
            $new->save();
        }

    }
}
