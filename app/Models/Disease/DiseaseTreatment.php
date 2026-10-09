<?php

namespace App\Models\Disease;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiseaseTreatment extends Model
{
    use HasFactory;

    protected $table = 'disease_treatment';

    protected $fillable = [
        'disease_id',
        'treatment_id',
        'is_primary_cure',
        'line_of_treatment',
        'specific_instructions',
        'dosage_or_frequency',
        'expected_duration',
        'notes',
    ];

    protected $casts = [
        'is_primary_cure' => 'boolean',
    ];

    public function disease(): BelongsTo
    {
        return $this->belongsTo(Disease::class, 'disease_id');
    }

    public function treatment(): BelongsTo
    {
        return $this->belongsTo(Treatment::class, 'treatment_id');
    }
}
