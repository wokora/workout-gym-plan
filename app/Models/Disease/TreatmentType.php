<?php

namespace App\Models\Disease;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TreatmentType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'treatment_type';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'badge_color',
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

    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class, 'treatment_type_id');
    }
}
