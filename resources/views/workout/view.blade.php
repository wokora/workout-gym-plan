@extends('layouts.app')

@section('title', 'Workout Plan - ' . $workout->name)

@section('content')
<div class="space-y-6">
    <!-- Workout Header -->
    <div class="flex flex-col justify-between space-y-3 sm:flex-row sm:items-center sm:space-y-0">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl">
                {{ $workout->name }}
            </h2>
            <p class="text-sm text-slate-500 dark:text-navy-300">
                Time: {{ \Carbon\Carbon::parse($workout->start_time)->format('H:i') }} &ndash; {{ \Carbon\Carbon::parse($workout->end_time)->format('H:i') }}
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('workout.edit', $workout->id) }}"
                class="btn h-9 rounded-lg border border-slate-300 px-3.5 text-xs font-semibold text-slate-700 hover:bg-slate-100 dark:border-navy-500 dark:text-navy-100 dark:hover:bg-navy-600">
                Edit Plan
            </a>
            <form action="{{ route('workout.destroy', $workout->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this workout?');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="btn h-9 rounded-lg border border-error/30 px-3.5 text-xs font-semibold text-error hover:bg-error/10">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <!-- Left Column: Timings and Days List -->
        <div class="space-y-6 lg:col-span-6">
            <!-- Info Cards Grid -->
            <div class="grid grid-cols-2 gap-4">
                <div class="card rounded-xl border border-slate-150 bg-white p-4 shadow-soft dark:border-navy-600 dark:bg-navy-700">
                    <span class="text-tiny uppercase font-semibold text-slate-400 dark:text-navy-300">Start Time</span>
                    <div class="mt-1 text-xl font-bold text-slate-800 dark:text-navy-100">
                        {{ \Carbon\Carbon::parse($workout->start_time)->format('H:i') }}
                    </div>
                </div>
                <div class="card rounded-xl border border-slate-150 bg-white p-4 shadow-soft dark:border-navy-600 dark:bg-navy-700">
                    <span class="text-tiny uppercase font-semibold text-slate-400 dark:text-navy-300">End Time</span>
                    <div class="mt-1 text-xl font-bold text-slate-800 dark:text-navy-100">
                        {{ \Carbon\Carbon::parse($workout->end_time)->format('H:i') }}
                    </div>
                </div>
            </div>

            <!-- Days List -->
            <div class="card rounded-xl border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
                <div class="border-b border-slate-150 px-5 py-4 dark:border-navy-600 font-semibold text-xs uppercase tracking-wider text-slate-600 dark:text-navy-200">
                    Scheduled Days
                </div>

                <div class="divide-y divide-slate-150 dark:divide-navy-600">
                    @forelse($workout->workout_day()->orderBy('number', 'ASC')->get() as $workout_day)
                        <a href="{{ route('workout.day.show', [$workout->id, $workout_day->id]) }}"
                            class="flex items-center justify-between p-4 transition-colors hover:bg-slate-50 dark:hover:bg-navy-800/50 {{ $workout_day->day->number == date('w') ? 'bg-primary/5 dark:bg-accent/5' : '' }}">
                            <div class="flex items-center space-x-2">
                                <span class="font-bold text-sm text-slate-800 dark:text-navy-100">{{ $workout_day->day->name }}</span>
                                @if($workout_day->day->number == date('w'))
                                    <span class="badge rounded bg-success/15 text-success text-tiny font-bold px-2 py-0.5">TODAY</span>
                                @endif
                            </div>
                            <span class="flex items-center space-x-1 text-xs font-semibold text-primary dark:text-accent-light">
                                <span>View Routine</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </a>
                    @empty
                        <div class="p-6 text-center text-xs text-slate-400 dark:text-navy-300">
                            No workout days scheduled yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Workout Anatomical Body Map -->
        <div class="lg:col-span-6">
            <x-body-muscle-map 
                :muscles="$workout->targetedMuscleNames()" 
                title="Target Muscle Groups" 
                subtitle="Complete muscle coverage across this routine" 
                height="h-80 sm:h-96" />
        </div>
    </div>
</div>
@endsection
