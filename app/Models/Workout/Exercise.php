<?php

namespace App\Models\Workout;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exercise extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'workout_day_exercise';

    protected $fillable = ['exercise_id' ,'number','sets','reps'];

    public function workout(){
        return $this->belongsTo(Workout::class, 'workout_id');
    }

    public function workout_day(){
        return $this->belongsTo(\App\Models\Workout\Day::class, 'workout_day_id');
    }

    public function exercise(){
        return $this->belongsTo(\App\Models\Exercise\Exercise::class, 'exercise_id');
    }
}
