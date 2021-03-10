@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Active Workouts</div>

                <div class="list-group list-group-flush">
                    @foreach( \App\Models\Workout\Workout::get() as $workout )
                        <a href="{{ route('workout.show', $workout->id) }}" class="list-group-item list-group-item-action">{{ $workout->name }} </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
