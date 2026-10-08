<?php

namespace App\Models\Exercise;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Body extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'exercise_body_section';

    protected $fillable = ['body_section_id', 'exercise_id'];

    public function body_section()
    {
        return $this->belongsTo(\App\Models\Body\Body::class, 'body_section_id');
    }

    public function getNameAttribute()
    {
        return $this->body_section->name ?? '';
    }
}
