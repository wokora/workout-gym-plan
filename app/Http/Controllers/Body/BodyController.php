<?php

namespace App\Http\Controllers\Body;

use App\Http\Controllers\Controller;
use App\Http\Resources\BodySectionResource;
use App\Models\Body\Body;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BodyController extends Controller
{
    /**
     * Display a listing of body sections.
     */
    public function index(): JsonResponse
    {
        $sections = Body::orderBy('name')->get();

        return response()->json([
            'records' => BodySectionResource::collection($sections),
        ]);
    }

    /**
     * Store a newly created body section in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:body_section,name',
            'description' => 'nullable|string|max:255',
        ]);

        $body = Body::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Body section created successfully.',
            'body_section' => new BodySectionResource($body),
        ], 201);
    }

    /**
     * Display the specified body section.
     */
    public function show($id): JsonResponse
    {
        $body = Body::findOrFail($id);

        return response()->json([
            'body_section' => new BodySectionResource($body),
        ]);
    }

    /**
     * Update the specified body section in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $body = Body::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:body_section,name,' . $id,
            'description' => 'nullable|string|max:255',
        ]);

        $body->update($request->only('name', 'description'));

        return response()->json([
            'message' => 'Body section updated successfully.',
            'body_section' => new BodySectionResource($body),
        ]);
    }

    /**
     * Remove the specified body section from storage.
     */
    public function destroy($id): JsonResponse
    {
        $body = Body::findOrFail($id);
        $body->delete();

        return response()->json([
            'message' => 'Body section deleted successfully.',
        ]);
    }
}
