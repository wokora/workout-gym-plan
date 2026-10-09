<?php

namespace App\Models\Exercise;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exercise extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'exercise';

    protected $fillable = ['name', 'description'];

    public function body_section(){
        return $this->hasMany(Body::class, 'exercise_id');
    }

    /**
     * Get unique muscle group names targeted by this exercise.
     *
     * @return array<string>
     */
    public function targetedMuscleNames(): array
    {
        return $this->body_section()
            ->with('body_section')
            ->get()
            ->map(fn($item) => $item->body_section->name ?? null)
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public function diseases()
    {
        return $this->belongsToMany(\App\Models\Disease\Disease::class, 'disease_exercise', 'exercise_id', 'disease_id')
            ->withPivot('id', 'safety_type', 'instructions', 'modifications')
            ->withTimestamps();
    }
}
