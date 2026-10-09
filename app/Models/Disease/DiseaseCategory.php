<?php

namespace App\Models\Disease;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class DiseaseCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'disease_category';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
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

    public function diseases(): HasMany
    {
        return $this->hasMany(Disease::class, 'disease_category_id');
    }
}
