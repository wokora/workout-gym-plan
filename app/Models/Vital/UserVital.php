<?php

namespace App\Models\Vital;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserVital extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_vitals';

    protected $fillable = [
        'user_id',
        'vital_id',
        'value',
        'date_measured',
    ];

    protected $casts = [
        'value' => 'float',
        'date_measured' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vital()
    {
        return $this->belongsTo(Vital::class, 'vital_id');
    }
}
