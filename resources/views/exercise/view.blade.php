@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="{{ route('exercise.edit', $exercise->id) }}" class="btn btn-outline-primary btn-block mb-3">Update Exercise</a>
                <div class="card">
                    <div class="card-header"> {{ $exercise->name }} Exercise</div>
                </div>
            </div>
        </div>
    </div>
@endsection
