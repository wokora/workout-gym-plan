<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FoodResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'energy_kcal' => $this->energy_kcal !== null ? (float) $this->energy_kcal : null,
            'energy_kj' => $this->energy_kj !== null ? (float) $this->energy_kj : null,
            'calories' => $this->energy_kcal !== null ? (float) $this->energy_kcal : null,
            'water' => $this->water !== null ? (float) $this->water : null,
            'protein' => $this->protein !== null ? (float) $this->protein : null,
            'fat' => $this->fat !== null ? (float) $this->fat : null,
            'fats' => $this->fat !== null ? (float) $this->fat : null,
            'carbs' => $this->carbs !== null ? (float) $this->carbs : null,
            'carbohydrates' => $this->carbs !== null ? (float) $this->carbs : null,
            'fibre' => $this->fibre !== null ? (float) $this->fibre : null,
            'ash' => $this->ash !== null ? (float) $this->ash : null,
            'ca' => $this->ca !== null ? (float) $this->ca : null,
            'mg' => $this->mg !== null ? (float) $this->mg : null,
            'fe' => $this->fe !== null ? (float) $this->fe : null,
            'na' => $this->na !== null ? (float) $this->na : null,
            'vit_a' => $this->vit_a !== null ? (float) $this->vit_a : null,
            'folate' => $this->folate !== null ? (float) $this->folate : null,
            'vit_c' => $this->vit_c !== null ? (float) $this->vit_c : null,
            'standard_portion_description' => $this->standard_portion_description,
            'standard_portion_grams' => $this->standard_portion_grams !== null ? (float) $this->standard_portion_grams : null,
            'serving_size' => $this->standard_portion_description ?: ($this->standard_portion_grams ? ($this->standard_portion_grams . 'g') : '100g'),
            'categories' => $this->categories ? $this->categories->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                ];
            }) : [],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
