<?php

namespace App\Models\File;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileType extends Model
{
    use SoftDeletes;

    protected $table = 'file_type';

    protected $fillable = ['name'];

    public function files()
    {
        return $this->hasMany(File::class, 'file_type_id');
    }
}
