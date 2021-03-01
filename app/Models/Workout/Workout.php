<?php

namespace App\Models\Workout;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workout extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'workout';

    public function workout_exercise(){
        return $this->hasMany(Exercise::class, 'workout_id');
    }
}
