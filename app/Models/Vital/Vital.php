<?php

namespace App\Models\Vital;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vital extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'vitals';

    protected $fillable = [
        'name',
        'slug',
        'unit_id',
        'input_type_id',
        'description',
        'min_value',
        'max_value',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'min_value' => 'float',
        'max_value' => 'float',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function inputType()
    {
        return $this->belongsTo(InputType::class, 'input_type_id');
    }

    public function userVitals()
    {
        return $this->hasMany(UserVital::class, 'vital_id');
    }
}
