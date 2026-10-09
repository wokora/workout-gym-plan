<?php

namespace App\Models\Food;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Food extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'food';

    protected $guarded = ['id'];

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
        return $this->morphToMany(\App\Models\Disease\Disease::class, 'recommendable', 'disease_recommendation')
            ->withPivot('id', 'recommendation_type_id', 'rationale', 'instructions')
            ->withTimestamps();
    }
}

