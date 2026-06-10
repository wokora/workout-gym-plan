@extends('layouts.app')

@section('title', 'Create Exercise')

@section('content')
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>CREATE NEW EXERCISE</span>
                        <a href="{{ route('exercise.index') }}" class="text-primary font-weight-bold" style="font-size: 0.85rem; text-decoration: none;">&larr; Back to Catalog</a>
                    </div>

                    <form action="{{ route('exercise.store') }}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="form-group mb-4">
                                <label for="name">Exercise Name</label>
                                <input type="text" name="name" value="{{ old('name', '') }}" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Barbell Bench Press">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label for="description">Instruction Guide / Description</label>
                                <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Describe the execution form, tips, and target zones..."></textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-2">
                                <label>Target Muscle Groups / Body Sections</label>
                                <div class="day-selector-grid">
                                    @foreach(\App\Models\Body\Body::get() as $body)
                                        <div class="day-selector-item">
                                            <input type="checkbox" value="{{ $body->id }}" name="body[{{ $body->id }}]" id="bodyCheck{{ $body->id }}" @if(is_array(old('body')) && in_array($body->id, old('body'))) checked @endif>
                                            <label for="bodyCheck{{ $body->id }}">{{ $body->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('body')
                                    <div class="text-danger mt-2" style="font-size: 80%">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('exercise.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Exercise</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
