@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create Workout</div>

                    <form action="{{ route('workout.day.store', $workout->id) }}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label>Workout Name</label>
                                <input type="text" name="name" value="{{ old('name', '') }}" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Workout Name">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
