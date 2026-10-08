@extends('layouts.app')

@section('title', 'Workouts')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col justify-between space-y-3 sm:flex-row sm:items-center sm:space-y-0">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl uppercase">
                Workout Plans
            </h2>
            <p class="text-sm text-slate-500 dark:text-navy-300">
                Select or manage your structured fitness routines.
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('muscle-groups.index') }}"
                class="btn space-x-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-soft hover:bg-slate-50 dark:border-navy-500 dark:bg-navy-600 dark:text-navy-100 dark:hover:bg-navy-500 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-primary dark:text-accent-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Anatomy Explorer</span>
            </a>
            <a href="{{ route('workout.create') }}"
                class="btn space-x-2 rounded-xl bg-primary px-4 py-2.5 text-xs font-semibold text-white shadow-soft hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Create Workout</span>
            </a>
        </div>
    </div>

    <!-- Workout List Card -->
    <div class="card rounded-xl border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <div class="border-b border-slate-150 px-5 py-4 dark:border-navy-600 font-semibold text-xs uppercase tracking-wider text-slate-600 dark:text-navy-200">
            All Workouts
        </div>

        <div class="divide-y divide-slate-150 dark:divide-navy-600">
            @forelse(\App\Models\Workout\Workout::get() as $workout)
                <div class="flex flex-col justify-between p-5 transition-colors hover:bg-slate-50 sm:flex-row sm:items-center dark:hover:bg-navy-800/50">
                    <div class="mb-3 sm:mb-0 space-y-1.5">
                        <a href="{{ route('workout.show', $workout->id) }}" class="text-base font-bold text-slate-800 hover:text-primary dark:text-navy-100 dark:hover:text-accent-light">
                            {{ $workout->name }}
                        </a>
                        <p class="text-xs text-slate-400 dark:text-navy-300">
                            Scheduled: {{ \Carbon\Carbon::parse($workout->start_time)->format('H:i') }} &ndash; {{ \Carbon\Carbon::parse($workout->end_time)->format('H:i') }}
                        </p>
                        @php $targets = $workout->targetedMuscleNames(); @endphp
                        @if(count($targets) > 0)
                            <div class="flex flex-wrap gap-1 pt-1">
                                @foreach(array_slice($targets, 0, 5) as $m)
                                    <span class="badge rounded bg-primary/10 text-primary dark:bg-accent/15 dark:text-accent-light px-2 py-0.5 text-tiny font-semibold">
                                        {{ $m }}
                                    </span>
                                @endforeach
                                @if(count($targets) > 5)
                                    <span class="badge rounded bg-slate-100 text-slate-500 dark:bg-navy-800 dark:text-navy-300 px-2 py-0.5 text-tiny font-medium">
                                        +{{ count($targets) - 5 }} more
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('workout.show', $workout->id) }}"
                            class="btn h-8 rounded-lg border border-primary/30 px-3 text-xs font-semibold text-primary hover:bg-primary/10 dark:border-accent/30 dark:text-accent-light dark:hover:bg-accent/10 flex items-center space-x-1">
                            <span>View Routine & Anatomy</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <svg class="mx-auto size-12 text-slate-300 dark:text-navy-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <h4 class="mt-3 text-base font-semibold text-slate-700 dark:text-navy-100">No workouts registered</h4>
                    <p class="mt-1 text-xs text-slate-400 dark:text-navy-300">Get started by creating your first workout plan.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
