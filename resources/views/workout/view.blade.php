@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="{{ route('workout.edit', $workout->id) }}" class="btn btn-outline-primary btn-block mb-3 text-uppercase">Edit Workout</a>
                <div class="card">
                    <div class="card-header">Day Exercise</div>

                    <div class="list-group list-group-flush">
                        @foreach( $workout->workout_day()->orderBy('number', 'ASC')->get() as $workout_day )
                            <a href="{{ route('workout.day.show', [$workout->id, $workout_day->id]) }}" class="list-group-item list-group-item-action @if($workout_day->day->number == date('w')) active @endif">{{ $workout_day->day->name }}</a>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
