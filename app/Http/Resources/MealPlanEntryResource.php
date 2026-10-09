<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MealPlanEntryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'meal_plan_day_id' => $this->meal_plan_day_id,
            'meal_type_id' => $this->meal_type_id,
            'meal_type_name' => $this->meal_type ? $this->meal_type->name : null,
            'number' => $this->number,
            'description' => $this->description,
            'foods' => $this->foods ? $this->foods->map(function ($entryFood) {
                return [
                    'id' => $entryFood->id,
                    'food_id' => $entryFood->food_id,
                    'name' => $entryFood->food ? $entryFood->food->name : null,
                    'calories' => $entryFood->food ? $entryFood->food->calories : null,
                    'protein' => $entryFood->food ? $entryFood->food->protein : null,
                    'carbohydrates' => $entryFood->food ? $entryFood->food->carbohydrates : null,
                    'fats' => $entryFood->food ? $entryFood->food->fats : null,
                ];
            }) : [],
        ];
    }
}
