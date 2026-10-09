<?php

namespace App\Models\File;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class File extends Model
{
    use SoftDeletes;

    protected $table = 'file';

    protected $fillable = [
        'uuid',
        'file_type_id',
        'file_status_id',
        'fileable_type',
        'fileable_id',
        'name',
        'original_name',
        'size',
        'type',
        'file_hash',
        'position',
        'payload',
    ];

    public function fileable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function file_type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(FileType::class, 'file_type_id');
    }

    public function file_status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(FileStatus::class, 'file_status_id');
    }

    public static function upload(Model $fileable, $uploadFile, FileType $fileType, $uuid = null): File
    {
        $uuid = $uuid ?: (string) Str::uuid();
        $baseDirectory = strtolower($fileType->name);
        $originalDir = "{$baseDirectory}/original";

        $randomName = strtoupper(Str::random(5) . '_' . Str::random(5) . '_' . time());
        $extension = $uploadFile->getClientOriginalExtension() ?: 'png';
        $filename = "{$randomName}.{$extension}";

        $disk = config('filesystems.disks.uploads') ? 'uploads' : 'public';
        $path = Storage::disk($disk)->putFileAs($originalDir, $uploadFile, $filename);

        if (!$path) {
            throw new \Exception('File could not be uploaded.');
        }

        $file = new static([
            'uuid'           => $uuid,
            'file_type_id'   => $fileType->id,
            'file_status_id' => 1,
            'name'           => $filename,
            'original_name'  => $uploadFile->getClientOriginalName(),
            'size'           => $uploadFile->getSize(),
            'type'           => $uploadFile->getMimeType() ?: 'application/octet-stream',
            'file_hash'      => hash_file('sha256', $uploadFile->getRealPath()),
        ]);

        $file->fileable()->associate($fileable);
        $file->save();

        return $file;
    }

    public function get_url($size = null, $get_path = false): string
    {
        $disk = config('filesystems.disks.uploads') ? 'uploads' : 'public';
        $directory = $this->file_type ? strtolower($this->file_type->name) : 'image';
        $sub = $size ? "{$size}/" : 'original/';
        $filePath = "{$directory}/{$sub}{$this->name}";

        if ($get_path) {
            return Storage::disk($disk)->path($filePath);
        }

        return Storage::disk($disk)->url($filePath);
    }

    public function get_media($size = 'original', $media_type = 'image'): string
    {
        return $this->get_url($size === 'original' ? null : $size);
    }
}
