<?php

namespace App\Models\Disease;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiseaseSymptom extends Model
{
    use HasFactory;

    protected $table = 'disease_symptom';

    protected $fillable = [
        'disease_id',
        'symptom_id',
        'is_primary',
        'frequency',
        'notes',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function disease(): BelongsTo
    {
        return $this->belongsTo(Disease::class, 'disease_id');
    }

    public function symptom(): BelongsTo
    {
        return $this->belongsTo(Symptom::class, 'symptom_id');
    }
}
