<?php

namespace App\Models\Disease;

use App\Models\Exercise\Exercise;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiseaseExercise extends Model
{
    use HasFactory;

    protected $table = 'disease_exercise';

    protected $fillable = [
        'disease_id',
        'exercise_id',
        'safety_type',
        'instructions',
        'modifications',
    ];

    public function disease(): BelongsTo
    {
        return $this->belongsTo(Disease::class, 'disease_id');
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class, 'exercise_id');
    }
}
