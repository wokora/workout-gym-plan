@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">Manage Workout Exercise</div>

                    <form action="{{ route('workout.day.exercise.update', [$workout_exercise->workout_day->workout->id, $workout_exercise->workout_day->id, $workout_exercise->id]) }}" method="post">
                        <input type="hidden" name="_method" value="patch" />
                        @csrf
                        <div class="card-body">

                            <div class="form-group">
                                <label>Exercise</label>
                                <select name="exercise" id="exercise" class="form-control @error('exercise') is-invalid @enderror">
                                    <option value>Select Exercise</option>
                                    @foreach(\App\Models\Exercise\Exercise::get() as $exercise)
                                        <option value="{{ $exercise->id }}" @if( old('exercise', $workout_exercise->exercise->id) == $exercise->id  ) selected @endif>{{ $exercise->name }}</option>
                                    @endforeach
                                </select>
                                @error('exercise')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Sets</label>
                                        <input type="number" name="sets" value="{{ old('sets', $workout_exercise->sets) }}" id="sets" class="form-control @error('sets') is-invalid @enderror" placeholder="Sets">
                                        @error('sets')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Reps</label>
                                        <input type="number" name="reps" value="{{ old('reps', $workout_exercise->reps) }}" id="reps" class="form-control @error('reps') is-invalid @enderror" placeholder="Reps">
                                        @error('reps')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Update Workout Exercise</button>
                        </div>
                    </form>


                </div>

                <form action="{{ route('workout.day.exercise.destroy', [$workout_exercise->workout_day->workout->id, $workout_exercise->workout_day->id, $workout_exercise->id]) }}" method="post">
                    @csrf
                    <input type="hidden" name="_method" value="delete" />
                    <button type="submit" class="btn btn-outline-danger btn-block">Delete</button>
                </form>
            </div>
        </div>
    </div>
@endsection
