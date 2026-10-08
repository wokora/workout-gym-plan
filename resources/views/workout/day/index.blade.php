@extends('layouts.app')

@section('title', 'Workout Day - ' . $day->day->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col justify-between space-y-3 sm:flex-row sm:items-center sm:space-y-0">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl">
                {{ $day->workout->name }} &bull; <span class="text-primary dark:text-accent-light">{{ $day->day->name }}</span>
            </h2>
            <a href="{{ route('workout.show', $day->workout->id) }}" class="text-xs font-semibold text-slate-500 hover:text-primary dark:text-navy-300 dark:hover:text-accent-light">
                &larr; Back to Workout Plan
            </a>
        </div>
        <div>
            <a href="{{ route('workout.day.exercise.create', [$day->workout->id, $day->id]) }}"
                class="btn space-x-2 rounded-xl bg-primary px-4 py-2 text-xs font-semibold text-white shadow-soft hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Exercise</span>
            </a>
        </div>
    </div>

    <!-- Main Content: Exercises List + Daily Muscle Focus -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <!-- Left Column: Exercises Table -->
        <div class="lg:col-span-7">
            <div class="card rounded-xl border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
                <div class="border-b border-slate-150 px-5 py-4 dark:border-navy-600 font-semibold text-xs uppercase tracking-wider text-slate-600 dark:text-navy-200">
                    Scheduled Exercises
                </div>

                @if($day->workout_exercise()->count() > 0)
                    <div class="is-scrollbar-hidden min-w-full overflow-x-auto">
                        <table class="is-hoverable w-full text-left">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-navy-500">
                                    <th class="whitespace-nowrap px-4 py-3 text-xs font-semibold uppercase text-slate-500 dark:text-navy-300">Exercise</th>
                                    <th class="whitespace-nowrap px-4 py-3 text-xs font-semibold uppercase text-slate-500 dark:text-navy-300">Sets</th>
                                    <th class="whitespace-nowrap px-4 py-3 text-xs font-semibold uppercase text-slate-500 dark:text-navy-300">Reps</th>
                                    <th class="whitespace-nowrap px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500 dark:text-navy-300">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-150 dark:divide-navy-600">
                                @foreach($day->workout_exercise()->orderBy('number', 'ASC')->get() as $workout_exercise)
                                    <tr>
                                        <td class="whitespace-nowrap px-4 py-3.5 font-semibold text-sm text-slate-800 dark:text-navy-100">
                                            <a href="{{ route('exercise.show', $workout_exercise->exercise->id) }}" class="hover:text-primary dark:hover:text-accent-light">
                                                {{ $workout_exercise->exercise->name }}
                                            </a>
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3.5">
                                            <span class="badge rounded-full bg-primary/10 text-primary dark:bg-accent/15 dark:text-accent-light px-2.5 py-0.5 text-xs font-semibold">
                                                {{ $workout_exercise->sets }} Sets
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3.5">
                                            <span class="badge rounded-full bg-info/10 text-info px-2.5 py-0.5 text-xs font-semibold">
                                                {{ $workout_exercise->reps }} Reps
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3.5 text-right">
                                            <a href="{{ route('workout.day.exercise.show', [$day->workout->id, $day->id, $workout_exercise->id]) }}"
                                                class="btn h-8 rounded-lg border border-slate-300 px-3 text-xs font-medium text-slate-700 hover:bg-slate-100 dark:border-navy-500 dark:text-navy-100 dark:hover:bg-navy-600">
                                                Manage
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-10 text-center">
                        <svg class="mx-auto size-12 text-slate-300 dark:text-navy-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <h5 class="mt-3 text-base font-semibold text-slate-700 dark:text-navy-100">No exercises scheduled for this day</h5>
                        <p class="mt-1 text-xs text-slate-400 dark:text-navy-300 mb-4">Begin adding exercises to map out your routine!</p>
                        <a href="{{ route('workout.day.exercise.create', [$day->workout->id, $day->id]) }}" class="btn h-8 rounded-lg bg-primary px-3 text-xs font-semibold text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                            Add First Exercise
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Day Anatomical Focus Map -->
        <div class="lg:col-span-5">
            <x-body-muscle-map 
                :muscles="$day->targetedMuscleNames()" 
                title="Daily Muscle Focus" 
                :subtitle="'Muscles targeted on ' . $day->day->name" 
                height="h-80 sm:h-96" />
        </div>
    </div>
</div>
@endsection
