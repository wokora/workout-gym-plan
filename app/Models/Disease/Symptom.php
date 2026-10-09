<?php

namespace App\Models\Disease;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Symptom extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'symptom';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'body_system',
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

    public function diseases(): BelongsToMany
    {
        return $this->belongsToMany(Disease::class, 'disease_symptom', 'symptom_id', 'disease_id')
            ->withPivot('id', 'is_primary', 'frequency', 'notes')
            ->withTimestamps();
    }

    public function disease_symptoms(): HasMany
    {
        return $this->hasMany(DiseaseSymptom::class, 'symptom_id');
    }
}
