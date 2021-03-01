@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create Exercise</div>

                    <form action="{{ route('exercise.store') }}" method="post">
                        @csrf
                        <div class="card-body">

                            <div class="form-group">
                                <label>Exercise Name</label>
                                <input type="text" name="name" value="{{ old('name', '') }}" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Exercise Name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', '') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Save Exercise</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
@endsection
