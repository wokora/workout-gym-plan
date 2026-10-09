<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserVitalResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'vital_id' => $this->vital_id,
            'vital_name' => $this->vital ? $this->vital->name : null,
            'vital_slug' => $this->vital ? $this->vital->slug : null,
            'unit' => $this->vital && $this->vital->unit ? $this->vital->unit->symbol : null,
            'value' => $this->value,
            'date_measured' => $this->date_measured ? $this->date_measured->format('Y-m-d') : null,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
        ];
    }
}
