<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SymptomResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'body_system' => $this->body_system,
            'diseases' => $this->diseases ? $this->diseases->map(fn($d) => [
                'id' => $d->id,
                'name' => $d->name,
                'slug' => $d->slug,
                'overview' => $d->overview,
                'treatments_count' => $d->treatments ? $d->treatments->count() : 0,
                'treatments' => $d->treatments ? $d->treatments->map(fn($t) => [
                    'id' => $t->id,
                    'name' => $t->name,
                    'slug' => $t->slug,
                    'is_primary_cure' => $t->pivot ? (bool)$t->pivot->is_primary_cure : false,
                    'treatment_type' => $t->treatment_type ? [
                        'id' => $t->treatment_type->id,
                        'name' => $t->treatment_type->name,
                    ] : null,
                ]) : [],
            ]) : [],
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
        ];
    }
}
