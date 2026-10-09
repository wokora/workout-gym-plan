<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkoutExerciseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'workout_day_id' => $this->workout_day_id,
            'exercise_id' => $this->exercise_id,
            'exercise_name' => $this->exercise ? $this->exercise->name : null,
            'exercise_description' => $this->exercise ? $this->exercise->description : null,
            'targeted_muscles' => $this->exercise ? $this->exercise->targetedMuscleNames() : [],
            'number' => $this->number,
            'sets' => $this->sets,
            'reps' => $this->reps,
        ];
    }
}
