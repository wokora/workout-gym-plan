@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="{{ route('workout.day.exercise.edit', [$workout_exercise->workout_day->workout->id, $workout_exercise->workout_day->id, $workout_exercise->id]) }}" class="btn btn-outline-primary btn-block mb-3">Edit Workout Exercise</a>
                <div class="card">
                    <div class="card-header">Workout Exercise</div>




                </div>
            </div>
        </div>
    </div>
@endsection
