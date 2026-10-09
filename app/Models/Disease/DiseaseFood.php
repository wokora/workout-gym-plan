<?php

namespace App\Models\Disease;

use App\Models\Food\Food;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiseaseFood extends Model
{
    use HasFactory;

    protected $table = 'disease_food';

    protected $fillable = [
        'disease_id',
        'food_id',
        'recommendation_type',
        'rationale',
        'serving_notes',
    ];

    public function disease(): BelongsTo
    {
        return $this->belongsTo(Disease::class, 'disease_id');
    }

    public function food(): BelongsTo
    {
        return $this->belongsTo(Food::class, 'food_id');
    }
}
