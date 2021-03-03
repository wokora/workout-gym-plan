@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="{{ route('body.create') }}" class="btn btn-outline-primary btn-block mb-3">Add Body Section</a>
                <div class="card">
                    <div class="card-header">All Body Sections</div>

                    <div class="list-group list-group-flush">
                        @foreach( \App\Models\Body\Body::get() as $body_section )
                            <a href="{{ route('body.show', $body_section->id) }}" class="list-group-item list-group-item-action">{{ $body_section->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
