@extends('layouts.app')

@section('title', 'Exercise - ' . $exercise->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col justify-between space-y-3 sm:flex-row sm:items-center sm:space-y-0">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl">
                {{ $exercise->name }}
            </h2>
            <a href="{{ route('exercise.index') }}" class="text-xs font-semibold text-slate-500 hover:text-primary dark:text-navy-300 dark:hover:text-accent-light">
                &larr; Back to Catalog
            </a>
        </div>
        <div>
            <a href="{{ route('exercise.edit', $exercise->id) }}"
                class="btn h-9 rounded-lg bg-primary px-4 text-xs font-semibold text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                Edit Exercise
            </a>
        </div>
    </div>

    <!-- Main Content: Details + Anatomy Map -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <!-- Left: Exercise Details -->
        <div class="space-y-6 lg:col-span-6">
            <div class="card rounded-xl border border-slate-150 bg-white p-5 shadow-soft dark:border-navy-600 dark:bg-navy-700">
                <div class="border-b border-slate-150 pb-4 dark:border-navy-600 font-semibold text-xs uppercase tracking-wider text-slate-600 dark:text-navy-200">
                    Exercise Guide & Details
                </div>

                <div class="mt-5 space-y-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-navy-300 mb-2">
                            Description & Form Guide
                        </label>
                        <div class="rounded-lg bg-slate-50 p-4 text-sm leading-relaxed text-slate-700 dark:bg-navy-800 dark:text-navy-100 min-h-[110px]">
                            {{ $exercise->description ?: 'No detailed description or guide has been written for this exercise yet.' }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-navy-300 mb-2">
                            Target Body Sections
                        </label>
                        <div class="flex flex-wrap gap-2">
                            @forelse($exercise->body_section ?? [] as $body)
                                <span class="badge rounded-full bg-primary/10 text-primary dark:bg-accent/15 dark:text-accent-light px-3 py-1 text-xs font-semibold">
                                    {{ $body->name }}
                                </span>
                            @empty
                                <span class="text-xs text-slate-400 dark:text-navy-300">No body sections mapped to this exercise.</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Anatomical Body Muscle Map -->
        <div class="lg:col-span-6">
            <x-body-muscle-map 
                :muscles="$exercise->targetedMuscleNames()" 
                title="Target Muscle Groups" 
                :subtitle="'Anatomical regions stimulated by ' . $exercise->name"
                height="h-80 sm:h-96" />
        </div>
    </div>
</div>
@endsection
