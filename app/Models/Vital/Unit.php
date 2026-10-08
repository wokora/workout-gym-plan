<?php

namespace App\Models\Vital;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'units';

    protected $fillable = [
        'name',
        'symbol',
        'category',
    ];

    public function vitals()
    {
        return $this->hasMany(Vital::class, 'unit_id');
    }
}
