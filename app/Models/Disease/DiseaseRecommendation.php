<?php

namespace App\Models\Disease;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DiseaseRecommendation extends Model
{
    use HasFactory;

    protected $table = 'disease_recommendation';

    protected $fillable = [
        'disease_id',
        'recommendation_type_id',
        'recommendable_type',
        'recommendable_id',
        'rationale',
        'instructions',
    ];

    public function disease(): BelongsTo
    {
        return $this->belongsTo(Disease::class, 'disease_id');
    }

    public function recommendation_type(): BelongsTo
    {
        return $this->belongsTo(RecommendationType::class, 'recommendation_type_id');
    }

    public function recommendable(): MorphTo
    {
        return $this->morphTo();
    }
}
