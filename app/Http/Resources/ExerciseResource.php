<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'targeted_muscles' => $this->targetedMuscleNames(),
            'body_sections' => $this->body_section->map(function ($bs) {
                return [
                    'id' => $bs->id,
                    'body_section_id' => $bs->body_section_id,
                    'name' => $bs->body_section ? $bs->body_section->name : null,
                ];
            }),
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
        ];
    }
}
