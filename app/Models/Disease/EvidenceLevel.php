<?php

namespace App\Models\Disease;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvidenceLevel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'evidence_level';

    protected $fillable = [
        'name',
        'code',
        'description',
        'badge_class',
        'rank',
    ];

    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class, 'evidence_level_id');
    }
}
