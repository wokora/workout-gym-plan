@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="{{ route('body.edit', $body->id) }}" class="btn btn-outline-primary btn-block mb-3">Edit Body Section</a>
                <div class="card">
                    <div class="card-header"> {{ $body->name }} Body Section</div>
                </div>
            </div>
        </div>
    </div>
@endsection
