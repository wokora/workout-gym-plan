<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Models\File\File;
use App\Models\File\FileType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Display a listing of media files.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 36);

        $query = File::whereHas('file_type', function ($q) {
            $q->where('name', 'Image');
        })->latest();

        if ($request->filled('q')) {
            $search = trim($request->input('q'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('original_name', 'like', "%{$search}%");
            });
        }

        $paginator = $query->paginate($perPage);

        $items = collect($paginator->items())->map(function (File $file) {
            return [
                'id' => $file->id,
                'uuid' => $file->uuid,
                'name' => $file->original_name ?: $file->name,
                'original_name' => $file->original_name ?: $file->name,
                'url' => $file->get_url(),
                'size' => $file->size,
                'type' => $file->type,
                'created_at' => $file->created_at ? $file->created_at->toIso8601String() : null,
            ];
        });

        return response()->json([
            'data' => $items,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
        ]);
    }

    /**
     * Upload an image file to the media library.
     */
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|image|max:12288', // 12MB max
        ]);

        $imageType = FileType::where('name', 'Image')->first() ?: FileType::first();

        $user = $request->user();
        $file = File::upload($user, $request->file('file'), $imageType);

        return response()->json([
            'data' => [
                'id' => $file->id,
                'uuid' => $file->uuid,
                'name' => $file->original_name ?: $file->name,
                'original_name' => $file->original_name ?: $file->name,
                'url' => $file->get_url(),
                'size' => $file->size,
                'type' => $file->type,
                'created_at' => $file->created_at ? $file->created_at->toIso8601String() : null,
            ],
            'message' => 'File uploaded successfully.',
        ], 201);
    }

    /**
     * Delete a media file.
     */
    public function destroy($id): JsonResponse
    {
        $file = File::findOrFail($id);
        $file->delete();

        return response()->json([
            'message' => 'File deleted successfully.',
        ]);
    }
}
