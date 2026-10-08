@extends('layouts.app')

@section('title', 'Exercise Detail - ' . $workout_exercise->exercise->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col justify-between space-y-3 sm:flex-row sm:items-center sm:space-y-0">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl">
                {{ $workout_exercise->exercise->name }}
            </h2>
            <a href="{{ route('workout.day.show', [$workout_exercise->workout_day->workout->id, $workout_exercise->workout_day->id]) }}" class="text-xs font-semibold text-slate-500 hover:text-primary dark:text-navy-300 dark:hover:text-accent-light">
                &larr; Back to Routine
            </a>
        </div>
        <div>
            <a href="{{ route('workout.day.exercise.edit', [$workout_exercise->workout_day->workout->id, $workout_exercise->workout_day->id, $workout_exercise->id]) }}"
                class="btn h-9 rounded-lg bg-primary px-4 text-xs font-semibold text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                Edit Routine Setting
            </a>
        </div>
    </div>

    <!-- Main Content: Targets + Anatomy Map -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <!-- Left: Targets & Guide -->
        <div class="space-y-6 lg:col-span-6">
            <div class="card rounded-xl border border-slate-150 bg-white p-5 shadow-soft dark:border-navy-600 dark:bg-navy-700">
                <div class="border-b border-slate-150 pb-4 dark:border-navy-600 font-semibold text-xs uppercase tracking-wider text-slate-600 dark:text-navy-200">
                    Exercise Targets & Guide
                </div>

                <div class="mt-5 grid grid-cols-2 gap-4 text-center">
                    <div class="rounded-xl border border-primary/20 bg-primary/5 p-4 dark:border-accent/30 dark:bg-accent/10">
                        <div class="text-4xl font-extrabold text-primary dark:text-accent-light">
                            {{ $workout_exercise->sets }}
                        </div>
                        <div class="mt-1 text-xs uppercase font-semibold text-slate-500 dark:text-navy-300">
                            Target Sets
                        </div>
                    </div>
                    <div class="rounded-xl border border-info/20 bg-info/5 p-4">
                        <div class="text-4xl font-extrabold text-info">
                            {{ $workout_exercise->reps }}
                        </div>
                        <div class="mt-1 text-xs uppercase font-semibold text-slate-500 dark:text-navy-300">
                            Target Reps
                        </div>
                    </div>
                </div>

                <div class="mt-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-navy-300 mb-2">
                            Description / Instructions
                        </label>
                        <div class="rounded-lg bg-slate-50 p-4 text-sm text-slate-700 dark:bg-navy-800 dark:text-navy-100">
                            {{ $workout_exercise->exercise->description ?: 'No instruction description provided for this exercise catalog entry.' }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-navy-300 mb-2">
                            Target Body Sections
                        </label>
                        <div class="flex flex-wrap gap-2">
                            @forelse($workout_exercise->exercise->body_section ?? [] as $body)
                                <span class="badge rounded-full bg-primary/10 text-primary dark:bg-accent/15 dark:text-accent-light px-3 py-1 text-xs font-semibold">
                                    {{ $body->name }}
                                </span>
                            @empty
                                <span class="text-xs text-slate-400 dark:text-navy-300">No body sections mapped.</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Anatomy Map -->
        <div class="lg:col-span-6">
            <x-body-muscle-map 
                :muscles="$workout_exercise->exercise->targetedMuscleNames()" 
                title="Target Muscle Groups" 
                :subtitle="'Anatomical regions stimulated by ' . $workout_exercise->exercise->name"
                height="h-80 sm:h-96" />
        </div>
    </div>
</div>
@endsection
