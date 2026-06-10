@extends('layouts.app')

@section('title', 'Manage Exercise')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>MANAGE ROUTINE EXERCISE</span>
                    <a href="{{ route('workout.day.show', [$workout_exercise->workout_day->workout->id, $workout_exercise->workout_day->id]) }}" class="text-primary font-weight-bold" style="font-size: 0.85rem; text-decoration: none;">&larr; Back to Routine</a>
                </div>

                <form action="{{ route('workout.day.exercise.update', [$workout_exercise->workout_day->workout->id, $workout_exercise->workout_day->id, $workout_exercise->id]) }}" method="post">
                    @method('PATCH')
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-4">
                            <label for="exercise">Exercise</label>
                            <select name="exercise" id="exercise" class="form-control @error('exercise') is-invalid @enderror">
                                <option value="">Select Exercise</option>
                                @foreach(\App\Models\Exercise\Exercise::get() as $exercise)
                                    <option value="{{ $exercise->id }}" @if( old('exercise', $workout_exercise->exercise->id) == $exercise->id  ) selected @endif>{{ $exercise->name }}</option>
                                @endforeach
                            </select>
                            @error('exercise')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="sets">Sets Target</label>
                                    <input type="number" name="sets" value="{{ old('sets', $workout_exercise->sets) }}" id="sets" class="form-control @error('sets') is-invalid @enderror" placeholder="Sets">
                                    @error('sets')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="reps">Reps Target</label>
                                    <input type="number" name="reps" value="{{ old('reps', $workout_exercise->reps) }}" id="reps" class="form-control @error('reps') is-invalid @enderror" placeholder="Reps">
                                    @error('reps')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <!-- Inline delete option to clean up layout -->
                        <button type="button" onclick="document.getElementById('delete-routine-exercise-form').submit();" class="btn btn-secondary btn-sm" style="border-color: rgba(239, 68, 68, 0.4); color: #ef4444;">
                            Delete Exercise
                        </button>
                        <div>
                            <a href="{{ route('workout.day.show', [$workout_exercise->workout_day->workout->id, $workout_exercise->workout_day->id]) }}" class="btn btn-secondary btn-sm mr-2">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>

            <form id="delete-routine-exercise-form" action="{{ route('workout.day.exercise.destroy', [$workout_exercise->workout_day->workout->id, $workout_exercise->workout_day->id, $workout_exercise->id]) }}" method="post" onsubmit="return confirm('Are you sure you want to remove this exercise from this day?');" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
@endsection
