@extends('layouts.app')

@section('title', 'Exercises')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl">
            Exercises for {{ $workout->name }}
        </h2>
    </div>

    <div class="card rounded-xl border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <div class="border-b border-slate-150 px-5 py-4 dark:border-navy-600 font-semibold text-xs uppercase tracking-wider text-slate-600 dark:text-navy-200">
            All Exercises
        </div>

        <div class="divide-y divide-slate-150 dark:divide-navy-600">
            @forelse($workout->workout_exercise as $workout_exercise)
                <div class="flex items-center justify-between p-4 hover:bg-slate-50 dark:hover:bg-navy-800/50">
                    <span class="font-semibold text-sm text-slate-800 dark:text-navy-100">
                        {{ $workout_exercise->exercise->name }}
                    </span>
                    <a href="{{ route('workout.exercise.show', [$workout->id, $workout_exercise->id]) }}"
                        class="btn h-8 rounded-lg border border-slate-300 px-3 text-xs font-medium text-slate-700 hover:bg-slate-100 dark:border-navy-500 dark:text-navy-100 dark:hover:bg-navy-600">
                        View
                    </a>
                </div>
            @empty
                <div class="p-8 text-center text-xs text-slate-400 dark:text-navy-300">
                    No exercises assigned yet.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
