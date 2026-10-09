<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkoutDayResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'workout_id' => $this->workout_id,
            'day_id' => $this->day_id,
            'day_name' => $this->day ? $this->day->name : null,
            'number' => $this->number,
            'targeted_muscles' => $this->targetedMuscleNames(),
            'exercises' => WorkoutExerciseResource::collection($this->whenLoaded('workout_exercise')),
        ];
    }
}
