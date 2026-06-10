@extends('layouts.app')

@section('title', 'Workout Plan')

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Workout Header -->
            <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap">
                <div>
                    <h2 class="text-white mb-1 font-weight-bold">{{ $workout->name }}</h2>
                    <p class="text-muted mb-0">
                        Schedule: {{ \Carbon\Carbon::parse($workout->start_date)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($workout->end_date)->format('M d, Y') }}
                    </p>
                </div>
                <div class="d-flex mt-2 mt-sm-0" style="gap: 0.5rem;">
                    <a href="{{ route('workout.edit', $workout->id) }}" class="btn btn-outline-primary btn-sm">Edit Plan</a>
                    <form action="{{ route('workout.destroy', $workout->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this workout?');" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-secondary btn-sm" style="border-color: rgba(239, 68, 68, 0.4); color: #ef4444;">Delete</button>
                    </form>
                </div>
            </div>

            <!-- Info Cards Grid -->
            <div class="row mb-4">
                <div class="col-6 col-sm-6">
                    <div class="card p-3">
                        <small class="text-muted text-uppercase font-weight-bold" style="font-size: 0.75rem;">Start Time</small>
                        <h4 class="text-white mb-0 mt-1 font-weight-bold">{{ \Carbon\Carbon::parse($workout->start_time)->format('H:i') }}</h4>
                    </div>
                </div>
                <div class="col-6 col-sm-6">
                    <div class="card p-3">
                        <small class="text-muted text-uppercase font-weight-bold" style="font-size: 0.75rem;">End Time</small>
                        <h4 class="text-white mb-0 mt-1 font-weight-bold">{{ \Carbon\Carbon::parse($workout->end_time)->format('H:i') }}</h4>
                    </div>
                </div>
            </div>

            <!-- Days List -->
            <div class="card">
                <div class="card-header">SCHEDULED DAYS</div>

                <div class="list-group list-group-flush">
                    @foreach( $workout->workout_day()->orderBy('number', 'ASC')->get() as $workout_day )
                        <a href="{{ route('workout.day.show', [$workout->id, $workout_day->id]) }}" class="list-group-item list-group-item-action @if($workout_day->day->number == date('w')) active @endif py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="font-weight-bold">{{ $workout_day->day->name }}</span>
                                @if($workout_day->day->number == date('w'))
                                    <span class="badge badge-success ml-2 py-1 px-2" style="font-size: 0.7rem; background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);">TODAY</span>
                                @endif
                            </div>
                            <span class="d-inline-flex align-items-center text-primary" style="font-size: 0.85rem; font-weight: 600;">
                                View Routine
                                <svg class="ml-2" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                                </svg>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
