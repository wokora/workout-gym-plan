<?php

namespace App\Models\Food;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Food extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'food';

    public function food_category()
    {
        return $this->hasMany(FoodCategory::class, 'food_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'food_category', 'food_id', 'category_id');
    }

    public function diseases()
    {
        return $this->belongsToMany(\App\Models\Disease\Disease::class, 'disease_food', 'food_id', 'disease_id')
            ->withPivot('id', 'recommendation_type', 'rationale', 'serving_notes')
            ->withTimestamps();
    }
}

