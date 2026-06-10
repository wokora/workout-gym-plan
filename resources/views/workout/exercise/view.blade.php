@extends('layouts.app')

@section('title', 'Exercise Detail')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <div>
                    <h2 class="text-white mb-0">{{ $workout_exercise->exercise->name }}</h2>
                    <a href="{{ route('workout.day.show', [$workout_exercise->workout_day->workout->id, $workout_exercise->workout_day->id]) }}" class="text-muted font-weight-bold" style="font-size: 0.85rem; text-decoration: none;">&larr; Back to Routine</a>
                </div>
                <div>
                    <a href="{{ route('workout.day.exercise.edit', [$workout_exercise->workout_day->workout->id, $workout_exercise->workout_day->id, $workout_exercise->id]) }}" class="btn btn-primary btn-sm">
                        Edit Routine Setting
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">EXERCISE TARGETS</div>
                <div class="card-body">
                    <div class="row text-center mb-4">
                        <div class="col-6">
                            <div class="p-3 rounded" style="background: rgba(163, 230, 53, 0.05); border: 1px solid rgba(163, 230, 53, 0.15);">
                                <h2 class="text-primary font-weight-bold mb-0 text-glow" style="font-size: 3rem;">{{ $workout_exercise->sets }}</h2>
                                <span class="text-muted text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.05em;">Target Sets</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded" style="background: rgba(6, 182, 212, 0.05); border: 1px solid rgba(6, 182, 212, 0.15);">
                                <h2 class="text-info font-weight-bold mb-0" style="font-size: 3rem;">{{ $workout_exercise->reps }}</h2>
                                <span class="text-muted text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.05em;">Target Reps</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label class="text-muted">Exercise Description / Guide</label>
                        <div class="p-3 rounded text-white" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                            {{ $workout_exercise->exercise->description ?: 'No instruction description provided for this exercise catalog entry.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
