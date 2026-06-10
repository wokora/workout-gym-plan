@extends('layouts.app')

@section('title', 'Create Body Section')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>CREATE NEW BODY SECTION</span>
                    <a href="{{ route('body.index') }}" class="text-primary font-weight-bold" style="font-size: 0.85rem; text-decoration: none;">&larr; Back to Sections</a>
                </div>

                <form action="{{ route('body.store') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-4">
                            <label for="name">Body Section Name</label>
                            <input type="text" name="name" value="{{ old('name', '') }}" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Chest, Quads, Deltoids">
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-2">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Describe the muscles included in this section..."></textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('body.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Section</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
