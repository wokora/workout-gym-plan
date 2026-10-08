<?php

namespace App\Models\Vital;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InputType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'input_types';

    protected $fillable = [
        'name',
        'code',
        'step',
    ];

    public function vitals()
    {
        return $this->hasMany(Vital::class, 'input_type_id');
    }
}
