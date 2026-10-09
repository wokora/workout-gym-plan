<?php

namespace App\Models\Meal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntryFood extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'meal_plan_day_entry_food';

    protected $fillable = ['meal_plan_day_entry_id', 'food_id'];

    public function entry()
    {
        return $this->belongsTo(Entry::class, 'meal_plan_day_entry_id');
    }

    public function food()
    {
        return $this->belongsTo(\App\Models\Food\Food::class, 'food_id');
    }
}
