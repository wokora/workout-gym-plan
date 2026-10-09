<?php

namespace App\Models\Disease;

use App\Models\File\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Treatment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'treatment';

    protected $fillable = [
        'treatment_type_id',
        'evidence_level_id',
        'name',
        'slug',
        'description',
        'mechanism_of_action',
        'standard_protocol',
        'side_effects',
        'cautions_warnings',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });

        static::updating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function treatment_type(): BelongsTo
    {
        return $this->belongsTo(TreatmentType::class, 'treatment_type_id');
    }

    public function evidence_level(): BelongsTo
    {
        return $this->belongsTo(EvidenceLevel::class, 'evidence_level_id');
    }

    public function diseases(): BelongsToMany
    {
        return $this->belongsToMany(Disease::class, 'disease_treatment', 'treatment_id', 'disease_id')
            ->withPivot('id', 'is_primary_cure', 'line_of_treatment', 'specific_instructions', 'dosage_or_frequency', 'expected_duration', 'notes')
            ->withTimestamps();
    }

    public function disease_treatments(): HasMany
    {
        return $this->hasMany(DiseaseTreatment::class, 'treatment_id');
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function images(): MorphMany
    {
        return $this->files()->whereHas('file_type', function ($q) {
            $q->where('name', 'Image');
        });
    }
}
