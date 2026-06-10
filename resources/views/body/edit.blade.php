@extends('layouts.app')

@section('title', 'Edit Body Section')

@section('content')
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>EDIT BODY SECTION</span>
                        <a href="{{ route('body.show', $body->id) }}" class="text-primary font-weight-bold" style="font-size: 0.85rem; text-decoration: none;">&larr; Back to Section</a>
                    </div>

                    <form action="{{ route('body.update', $body->id) }}" method="post">
                        @method('PATCH')
                        @csrf
                        <div class="card-body">
                            <div class="form-group mb-4">
                                <label for="name">Body Section Name</label>
                                <input type="text" name="name" value="{{ old('name', $body->name) }}" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Body Section Name">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-2">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $body->description) }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <button type="button" onclick="if(confirm('Are you sure you want to delete this body section?')) { document.getElementById('delete-body-section-form').submit(); }" class="btn btn-secondary btn-sm" style="border-color: rgba(239, 68, 68, 0.4); color: #ef4444;">
                                Delete Section
                            </button>
                            <div>
                                <a href="{{ route('body.show', $body->id) }}" class="btn btn-secondary btn-sm mr-2">Cancel</a>
                                <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>

                <form id="delete-body-section-form" action="{{ route('body.destroy', $body->id) }}" method="post" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
@endsection
