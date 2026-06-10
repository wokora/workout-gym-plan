@extends('layouts.app')

@section('title', 'Workouts')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <div>
                    <h2 class="text-white mb-0">WORKOUT PLANS</h2>
                    <p class="text-muted mb-0">Select or manage your structured fitness plans.</p>
                </div>
                <div>
                    <a href="{{ route('workout.create') }}" class="btn btn-primary d-inline-flex align-items-center mt-2 mt-sm-0">
                        <svg class="mr-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        Create Workout
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">ALL WORKOUTS</div>

                <div class="list-group list-group-flush">
                    @forelse( \App\Models\Workout\Workout::get() as $workout )
                        <a href="{{ route('workout.show', $workout->id) }}" class="list-group-item list-group-item-action py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="text-white font-weight-bold mb-1">{{ $workout->name }}</h5>
                                <span class="text-muted" style="font-size: 0.85rem;">
                                    {{ \Carbon\Carbon::parse($workout->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($workout->end_date)->format('M d, Y') }}
                                    &bull;
                                    {{ \Carbon\Carbon::parse($workout->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($workout->end_time)->format('H:i') }}
                                </span>
                            </div>
                            <span class="btn btn-outline-primary btn-sm py-1 px-3 d-inline-flex align-items-center">
                                Configure
                                <svg class="ml-2" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                                </svg>
                            </span>
                        </a>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <svg class="mb-3" width="40" height="40" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
                            </svg>
                            <h5>No workouts registered</h5>
                            <p class="mb-0">Get started by creating your first workout plan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
