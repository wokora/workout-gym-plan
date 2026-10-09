<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DiseaseResource extends JsonResource
{
    public function toArray($request)
    {
        $allRecommendations = $this->recommendations ? $this->recommendations->map(function ($rec) {
            $recommendable = $rec->recommendable;
            return [
                'id' => $rec->id,
                'recommendable_type' => $rec->recommendable_type,
                'recommendable_id' => $rec->recommendable_id,
                'name' => $recommendable ? $recommendable->name : ('Item #' . $rec->recommendable_id),
                'details' => $recommendable ? ($recommendable->description ?? (($recommendable->energy_kcal ?? null) ? $recommendable->energy_kcal . ' kcal' : null)) : null,
                'recommendation_type_id' => $rec->recommendation_type_id,
                'recommendation_type' => $rec->recommendation_type ? [
                    'id' => $rec->recommendation_type->id,
                    'name' => $rec->recommendation_type->name,
                    'slug' => $rec->recommendation_type->slug,
                    'color' => $rec->recommendation_type->color,
                ] : null,
                'rationale' => $rec->rationale,
                'instructions' => $rec->instructions,
            ];
        }) : collect();

        $foods = $allRecommendations->where('recommendable_type', 'food')->values();
        $workouts = $allRecommendations->where('recommendable_type', 'workout')->values();
        $exercises = $allRecommendations->where('recommendable_type', 'exercise')->values();

        $foodsToEat = $foods->filter(function ($item) {
            $slug = $item['recommendation_type']['slug'] ?? '';
            return in_array($slug, ['recommended', 'therapeutic']) || ($item['recommendation_type_id'] == 1);
        })->values();

        $foodsToAvoid = $foods->filter(function ($item) {
            $slug = $item['recommendation_type']['slug'] ?? '';
            return in_array($slug, ['avoid', 'limit']) || ($item['recommendation_type_id'] == 2);
        })->values();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'symptoms' => $this->symptoms ? $this->symptoms->map(fn($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'slug' => $s->slug,
                'description' => $s->description,
                'body_system' => $s->body_system,
                'is_primary' => $s->pivot ? (bool)$s->pivot->is_primary : false,
                'frequency' => $s->pivot ? $s->pivot->frequency : null,
                'notes' => $s->pivot ? $s->pivot->notes : null,
            ]) : [],
            'treatments' => $this->treatments ? $this->treatments->map(fn($t) => [
                'id' => $t->id,
                'name' => $t->name,
                'slug' => $t->slug,
                'description' => $t->description,
                'standard_protocol' => $t->standard_protocol,
                'treatment_type' => $t->treatment_type ? [
                    'id' => $t->treatment_type->id,
                    'name' => $t->treatment_type->name,
                ] : null,
                'is_primary_cure' => $t->pivot ? (bool)$t->pivot->is_primary_cure : false,
                'line_of_treatment' => $t->pivot ? $t->pivot->line_of_treatment : null,
                'specific_instructions' => $t->pivot ? $t->pivot->specific_instructions : null,
            ]) : [],
            'recommendations' => $allRecommendations->values(),
            'foods' => $foods,
            'foods_to_eat' => $foodsToEat,
            'foods_to_avoid' => $foodsToAvoid,
            'workouts' => $workouts,
            'exercises' => $exercises,
            'images' => $this->images ? $this->images->map(fn($img) => [
                'id' => $img->id,
                'name' => $img->original_name ?: $img->name,
                'url' => $img->get_url(),
            ]) : [],
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : null,
        ];
    }
}
