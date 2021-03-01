@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="{{ route('workout.exercise.index', $workout->id) }}" class="btn btn-outline-primary btn-block mb-3">Workout Exercises</a>
                <div class="card">
                    <div class="card-header">Update Workout</div>

                    <form action="{{ route('workout.update', $workout->id) }}" method="post">
                        <input type="hidden" name="_method" value="patch" />
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label>Workout Name</label>
                                <input type="text" name="name" value="{{ old('name', $workout->name) }}" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Workout Name">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <input type="date" name="start_date" value="{{ old('start_date', $workout->start_date) }}" id="start_date" class="form-control @error('start_date') is-invalid @enderror" placeholder="Start date">
                                        @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <input type="date" name="end_date" value="{{ old('end_date', $workout->end_date) }}" id="end_date" class="form-control @error('start_date') is-invalid @enderror" placeholder="End date">
                                        @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Day</label>
                                <select name="day" id="day" class="form-control @error('day') is-invalid @enderror">
                                    <option value>Select Workout Day</option>
                                    @foreach(\App\Models\Day\Day::get() as $day)
                                        <option value="{{ $day->id }}" @if( old('day', $workout->day_id) == $day->id  ) selected @endif>{{ $day->name }}</option>
                                    @endforeach
                                </select>
                                @error('day')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Start time</label>
                                        <input type="time" name="start_time" value="{{ old('start_time', $workout->start_time) }}" id="start_time" class="form-control @error('start_date') is-invalid @enderror" placeholder="Start time">
                                        @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>End time</label>
                                        <input type="time" name="end_time" value="{{ old('end_time', $workout->end_time) }}" id="end_time" class="form-control @error('start_date') is-invalid @enderror" placeholder="End time">
                                        @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Save Workout</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
@endsection
