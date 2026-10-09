<?php

namespace App\Models\Meal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entry extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'meal_plan_day_entry';

    protected $fillable = ['meal_plan_day_id', 'meal_type_id', 'number', 'description'];

    public function meal_plan_day()
    {
        return $this->belongsTo(Day::class, 'meal_plan_day_id');
    }

    public function meal_type()
    {
        return $this->belongsTo(\App\Models\Meal\Type::class, 'meal_type_id');
    }

    public function foods()
    {
        return $this->hasMany(EntryFood::class, 'meal_plan_day_entry_id');
    }
}
