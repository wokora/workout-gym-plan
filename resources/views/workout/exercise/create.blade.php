@extends('layouts.app')

@section('title', 'Add Exercise')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>ADD EXERCISE TO ROUTINE</span>
                    <a href="{{ route('workout.day.show', [$workout->id, $day->id]) }}" class="text-primary font-weight-bold" style="font-size: 0.85rem; text-decoration: none;">&larr; Back to Routine</a>
                </div>

                <form action="{{ route('workout.day.exercise.store', [$workout->id, $day->id]) }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-4">
                            <label for="exercise">Exercise</label>
                            <select name="exercise" id="exercise" class="form-control @error('exercise') is-invalid @enderror">
                                <option value="">Select Exercise</option>
                                @foreach(\App\Models\Exercise\Exercise::get() as $exercise)
                                    <option value="{{ $exercise->id }}" @if( old('exercise', '') == $exercise->id  ) selected @endif>{{ $exercise->name }}</option>
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
                                    <input type="number" name="sets" value="{{ old('sets', '') }}" id="sets" class="form-control @error('sets') is-invalid @enderror" placeholder="e.g. 4">
                                    @error('sets')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="reps">Reps Target</label>
                                    <input type="number" name="reps" value="{{ old('reps', '') }}" id="reps" class="form-control @error('reps') is-invalid @enderror" placeholder="e.g. 12">
                                    @error('reps')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('workout.day.show', [$workout->id, $day->id]) }}" class="btn btn-secondary mr-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Add to Day</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
