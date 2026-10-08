<?php

namespace App\Models\Workout;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workout extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'workout';

    protected $guarded = [];

    public function workout_exercise(){
        return $this->hasMany(Exercise::class, 'workout_id');
    }

    public function workout_day(){
        return $this->hasMany(Day::class, 'workout_id');
    }

    /**
     * Get unique muscle group names targeted across all exercises in this workout routine.
     *
     * @return array<string>
     */
    public function targetedMuscleNames(): array
    {
        return $this->workout_day()
            ->with('workout_exercise.exercise.body_section.body_section')
            ->get()
            ->flatMap(function ($day) {
                return $day->targetedMuscleNames();
            })
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
