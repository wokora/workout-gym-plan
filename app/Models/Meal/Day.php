<?php

namespace App\Models\Meal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Day extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'meal_plan_day';

    protected $fillable = ['day_id', 'number'];

    public function day()
    {
        return $this->belongsTo(\App\Models\Day\Day::class, 'day_id');
    }

    public function meal_plan()
    {
        return $this->belongsTo(\App\Models\Meal\Plan::class, 'meal_plan_id');
    }

    public function entry()
    {
        return $this->hasMany(Entry::class, 'meal_plan_day_id');
    }
}
