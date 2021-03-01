@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="{{ route('exercise.create') }}" class="btn btn-outline-primary btn-block mb-3">Create Exercise</a>
                <div class="card">
                    <div class="card-header">All Exercises</div>

                    <div class="list-group list-group-flush">
                        @foreach( \App\Models\Exercise\Exercise::get() as $exercise )
                            <a href="{{ route('exercise.show', $exercise->id) }}" class="list-group-item list-group-item-action">{{ $exercise->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
