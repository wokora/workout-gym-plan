<?php

namespace App\Models\Body;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Body extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'body_section';
}
