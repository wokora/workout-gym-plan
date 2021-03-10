@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="{{ route('exercise.create') }}" class="btn btn-outline-primary btn-block mb-3">Create Exercise</a>
                <div class="card mb-3">
                    <div class="card-header">Update Exercise</div>

                    <form action="{{ route('exercise.update', $exercise->id) }}" method="post">
                        <input type="hidden" name="_method" value="patch" />
                        @csrf
                        <div class="card-body">

                            <div class="form-group">
                                <label>Exercise Name</label>
                                <input type="text" name="name" value="{{ old('name', $exercise->name) }}" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Exercise Name">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $exercise->description) }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Target body sections</label>
                                @foreach(\App\Models\Body\Body::get() as $body)
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" value="{{ $body->id }}" name="body[{{ $body->id }}]" @if( $exercise->body_section()->where('body_section_id', $body->id)->count() > 0 ) checked @endif class="custom-control-input" id="customCheck{{ $body->id }}">
                                        <label class="custom-control-label" for="customCheck{{ $body->id }}">{{ $body->name }}</label>
                                    </div>
                                @endforeach
                            </div>


                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Save Exercise</button>
                        </div>
                    </form>


                </div>

                <form action="{{ route('exercise.destroy', $exercise->id) }}" method="post">
                    @csrf
                    <input type="hidden" name="_method" value="delete" />
                    <button type="submit" class="btn btn-outline-danger btn-block">Delete</button>
                </form>
            </div>
        </div>
    </div>
@endsection
