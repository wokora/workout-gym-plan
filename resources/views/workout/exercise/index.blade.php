@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="{{ route('workout.exercise.create', [$workout->id, $day->id]) }}" class="btn btn-outline-primary btn-block mb-3">Create <strong>{{ $workout->name }}</strong> Exercise</a>
                <div class="card">
                    <div class="card-header">All Exercises</div>



                    <div class="list-group list-group-flush">
                        @foreach( $workout->workout_exercise as $workout_exercise )
                            <a href="{{ route('workout.exercise.show', [$workout->id, $workout_exercise->id]) }}" class="list-group-item list-group-item-action">{{ $workout_exercise->exercise->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
