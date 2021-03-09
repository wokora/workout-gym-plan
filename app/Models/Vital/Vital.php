<?php

namespace App\Models\Vital;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vital extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_vitals';

    protected $fillable = ['weight', 'height', 'date_measured'];

}
