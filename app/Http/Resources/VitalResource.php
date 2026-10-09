<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VitalResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'unit' => $this->unit ? [
                'id' => $this->unit->id,
                'name' => $this->unit->name,
                'symbol' => $this->unit->symbol,
            ] : null,
            'input_type' => $this->inputType ? [
                'id' => $this->inputType->id,
                'name' => $this->inputType->name,
            ] : null,
            'description' => $this->description,
            'min_value' => $this->min_value,
            'max_value' => $this->max_value,
            'is_active' => $this->is_active,
            'order' => $this->order,
        ];
    }
}
