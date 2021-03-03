<?php

namespace App\Models\Exercise;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Body extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'exercise_body_section';

    protected $fillable = ['body_section_id'];
}
