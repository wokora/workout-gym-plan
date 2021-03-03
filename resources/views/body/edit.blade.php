@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="{{ route('body.create') }}" class="btn btn-outline-primary btn-block mb-3">Add Body Section</a>
                <div class="card">
                    <div class="card-header">Create Body Section</div>

                    <form action="{{ route('body.edit', $body->id) }}" method="post">
                        @csrf
                        <div class="card-body">

                            <div class="form-group">
                                <label>Body Section Name</label>
                                <input type="text" name="name" value="{{ old('name', $body->name) }}" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Body Section Name">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $body->description) }}</textarea>
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
