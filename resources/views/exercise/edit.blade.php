@extends('layouts.app')

@section('title', 'Edit Exercise')

@section('content')
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>EDIT EXERCISE</span>
                        <a href="{{ route('exercise.show', $exercise->id) }}" class="text-primary font-weight-bold" style="font-size: 0.85rem; text-decoration: none;">&larr; Back to Exercise</a>
                    </div>

                    <form action="{{ route('exercise.update', $exercise->id) }}" method="post">
                        @method('PATCH')
                        @csrf
                        <div class="card-body">
                            <div class="form-group mb-4">
                                <label for="name">Exercise Name</label>
                                <input type="text" name="name" value="{{ old('name', $exercise->name) }}" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Exercise Name">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label for="description">Instruction Guide / Description</label>
                                <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $exercise->description) }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-2">
                                <label>Target Muscle Groups / Body Sections</label>
                                <div class="day-selector-grid">
                                    @foreach(\App\Models\Body\Body::get() as $body)
                                        <div class="day-selector-item">
                                            <input type="checkbox" value="{{ $body->id }}" name="body[{{ $body->id }}]" id="bodyCheck{{ $body->id }}" @if( $exercise->body_section()->where('body_section_id', $body->id)->count() > 0 ) checked @endif>
                                            <label for="bodyCheck{{ $body->id }}">{{ $body->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('body')
                                    <div class="text-danger mt-2" style="font-size: 80%">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <button type="button" onclick="if(confirm('Are you sure you want to delete this exercise?')) { document.getElementById('delete-exercise-form').submit(); }" class="btn btn-secondary btn-sm" style="border-color: rgba(239, 68, 68, 0.4); color: #ef4444;">
                                Delete Exercise
                            </button>
                            <div>
                                <a href="{{ route('exercise.show', $exercise->id) }}" class="btn btn-secondary btn-sm mr-2">Cancel</a>
                                <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>

                <form id="delete-exercise-form" action="{{ route('exercise.destroy', $exercise->id) }}" method="post" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
@endsection
