<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MealPlanDayResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'meal_plan_id' => $this->meal_plan_id,
            'day_id' => $this->day_id,
            'day_name' => $this->day ? $this->day->name : null,
            'number' => $this->number,
            'entries' => MealPlanEntryResource::collection($this->whenLoaded('entry')),
        ];
    }
}
