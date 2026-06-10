@extends('layouts.app')

@section('title', 'Workout Day')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <div>
                    <h2 class="text-white mb-0">{{ $day->workout->name }} &bull; <span class="text-primary text-glow">{{ $day->day->name }}</span></h2>
                    <a href="{{ route('workout.show', $day->workout->id) }}" class="text-muted font-weight-bold" style="font-size: 0.85rem; text-decoration: none;">&larr; Back to Workout Plan</a>
                </div>
                <div>
                    <a href="{{ route('workout.day.exercise.create', [$day->workout->id, $day->id]) }}" class="btn btn-primary d-inline-flex align-items-center mt-2 mt-sm-0">
                        <svg class="mr-2" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        Add Exercise
                    </a>
                </div>
            </div>

            <!-- Exercises Table Card -->
            <div class="card">
                <div class="card-header">EXERCISE LIST</div>

                @if($day->workout_exercise()->count() > 0)
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                            <tr>
                                <th>Exercise</th>
                                <th>Sets</th>
                                <th>Reps</th>
                                <th class="text-right">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach( $day->workout_exercise()->orderBy('number', 'ASC')->get() as $workout_exercise )
                                    <tr>
                                        <td class="font-weight-bold">
                                            <a href="{{ route('exercise.show', $workout_exercise->exercise->id) }}" class="text-white text-decoration-none hover-glow">
                                                {{ $workout_exercise->exercise->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge badge-dark py-2 px-3 text-primary font-weight-bold" style="background: rgba(163, 230, 53, 0.1); border: 1px solid rgba(163, 230, 53, 0.2);">
                                                {{ $workout_exercise->sets }} Sets
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-dark py-2 px-3 text-info font-weight-bold" style="background: rgba(6, 182, 212, 0.1); border: 1px solid rgba(6, 182, 212, 0.2);">
                                                {{ $workout_exercise->reps }} Reps
                                            </span>
                                        </td>
                                        <td align="right">
                                            <a href="{{ route('workout.day.exercise.show', [$day->workout->id, $day->id, $workout_exercise->id]) }}" class="btn btn-secondary btn-sm py-1 px-3">
                                                Manage
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted card-body">
                        <svg class="mb-3" width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        <h5>No exercises scheduled for this day</h5>
                        <p class="mb-4">Begin adding workouts to map out your routine!</p>
                        <a href="{{ route('workout.day.exercise.create', [$day->workout->id, $day->id]) }}" class="btn btn-outline-primary btn-sm">Add First Exercise</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
