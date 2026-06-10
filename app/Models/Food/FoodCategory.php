<?php

namespace App\Models\Food;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FoodCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'food_category';

    protected $fillable = ['food_id', 'category_id'];

    public function food()
    {
        return $this->belongsTo(Food::class, 'food_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
