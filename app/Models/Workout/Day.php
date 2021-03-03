<?php

namespace App\Models\Workout;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Day extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'workout_day';

    protected $fillable = ['day_id', 'number'];

    public function day(){
        return $this->belongsTo(\App\Models\Day\Day::class, 'day_id');
    }

    public function workout(){
        return $this->belongsTo(\App\Models\Workout\Workout::class, 'workout_id');
    }

    public function exercise(){
        return $this->belongsTo(\App\Models\Exercise\Exercise::class, 'exercise_id');
    }

    public function workout_exercise(){
        return $this->hasMany( Exercise::class, 'workout_day_id' );
    }
}
