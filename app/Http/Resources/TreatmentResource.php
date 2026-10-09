<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'mechanism_of_action' => $this->mechanism_of_action,
            'standard_protocol' => $this->standard_protocol,
            'side_effects' => $this->side_effects,
            'cautions_warnings' => $this->cautions_warnings,
            'treatment_type_id' => $this->treatment_type_id,
            'evidence_level_id' => $this->evidence_level_id,
            'treatment_type' => $this->treatment_type ? [
                'id' => $this->treatment_type->id,
                'name' => $this->treatment_type->name,
            ] : null,
            'evidence_level' => $this->evidence_level ? [
                'id' => $this->evidence_level->id,
                'name' => $this->evidence_level->name,
            ] : null,
            'diseases' => $this->diseases ? $this->diseases->map(fn($d) => [
                'id' => $d->id,
                'name' => $d->name,
                'slug' => $d->slug,
                'overview' => $d->overview,
                'is_primary_cure' => $d->pivot ? (bool)$d->pivot->is_primary_cure : false,
                'specific_instructions' => $d->pivot ? $d->pivot->specific_instructions : null,
            ]) : [],
            'images' => $this->images ? $this->images->map(fn($img) => [
                'id' => $img->id,
                'name' => $img->original_name,
                'url' => $img->get_url(),
            ]) : [],
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
        ];
    }
}
