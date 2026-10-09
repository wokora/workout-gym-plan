<?php

namespace App\Models\File;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileStatus extends Model
{
    use SoftDeletes;

    protected $table = 'file_status';

    protected $fillable = ['name'];

    public function files()
    {
        return $this->hasMany(File::class, 'file_status_id');
    }
}
