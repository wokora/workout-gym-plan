<?php

namespace App\Models\Disease;

use App\Models\Exercise\Exercise;
use App\Models\File\File;
use App\Models\Food\Food;
use App\Models\Workout\Workout;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Disease extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'disease';

    protected $fillable = [
        'name',
        'slug',
        'description',
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

    public function symptoms(): BelongsToMany
    {
        return $this->belongsToMany(Symptom::class, 'disease_symptom', 'disease_id', 'symptom_id')
            ->withPivot('id', 'is_primary', 'frequency', 'notes')
            ->withTimestamps();
    }

    public function disease_symptoms(): HasMany
    {
        return $this->hasMany(DiseaseSymptom::class, 'disease_id');
    }

    public function treatments(): BelongsToMany
    {
        return $this->belongsToMany(Treatment::class, 'disease_treatment', 'disease_id', 'treatment_id')
            ->withPivot('id', 'is_primary_cure', 'line_of_treatment', 'specific_instructions', 'dosage_or_frequency', 'expected_duration', 'notes')
            ->withTimestamps();
    }

    public function disease_treatments(): HasMany
    {
        return $this->hasMany(DiseaseTreatment::class, 'disease_id');
    }

    public function primary_cures(): BelongsToMany
    {
        return $this->treatments()->wherePivot('is_primary_cure', true);
    }

    public function supportive_treatments(): BelongsToMany
    {
        return $this->treatments()->wherePivot('is_primary_cure', false);
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(DiseaseRecommendation::class, 'disease_id');
    }

    public function foods(): MorphToMany
    {
        return $this->morphedByMany(Food::class, 'recommendable', 'disease_recommendation')
            ->withPivot('id', 'recommendation_type_id', 'rationale', 'instructions')
            ->withTimestamps();
    }

    public function workouts(): MorphToMany
    {
        return $this->morphedByMany(Workout::class, 'recommendable', 'disease_recommendation')
            ->withPivot('id', 'recommendation_type_id', 'rationale', 'instructions')
            ->withTimestamps();
    }

    public function exercises(): MorphToMany
    {
        return $this->morphedByMany(Exercise::class, 'recommendable', 'disease_recommendation')
            ->withPivot('id', 'recommendation_type_id', 'rationale', 'instructions')
            ->withTimestamps();
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
