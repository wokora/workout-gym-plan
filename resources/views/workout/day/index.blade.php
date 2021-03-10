@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="{{ route('workout.day.exercise.create', [$day->workout->id, $day->id]) }}" class="btn btn-outline-primary btn-block mb-3 text-uppercase">Add Exercise</a>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                        <tr>
                            <th>Exercise</th>
                            <th>Sets</th>
                            <th>Reps</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach( $day->workout_exercise()->orderBy('number', 'ASC')->get() as $workout_exercise )
                                <tr>
                                    <td><a href="{{ route('exercise.show', $workout_exercise->exercise->id) }}">{{ $workout_exercise->exercise->name }}</a> </td>
                                    <td>{{ $workout_exercise->sets }}</td>
                                    <td>{{ $workout_exercise->reps }}</td>
                                    <td align="right">
                                        <a href="{{ route('workout.day.exercise.show', [$day->workout->id, $day->id, $workout_exercise->id]) }}">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
