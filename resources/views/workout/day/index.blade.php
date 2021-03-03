@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="{{ route('workout.day.exercise.create', [$day->workout->id, $day->id]) }}" class="btn btn-outline-primary btn-block mb-3 text-uppercase">Add Exercise</a>
                <div class="card">
                    <div class="card-header">Day Exercises</div>

                    <div class="list-group list-group-flush">
                        @foreach( $day->workout_exercise()->orderBy('number', 'ASC')->get() as $workout_exercise )
                            <a href="{{ route('workout.day.exercise.show', [$day->workout->id, $day->id, $workout_exercise->id]) }}" class="list-group-item list-group-item-action">{{ $workout_exercise->exercise->name }} {{$workout_exercise->sets }} * {{ $workout_exercise->reps }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
