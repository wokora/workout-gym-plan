@extends('layouts.app')

@section('title', 'Muscle Groups Anatomy Explorer')

@section('content')
<div x-data="{
        selectedMuscle: @js($selectedMuscle),
        searchQuery: '',
        exercisesByMuscle: @js($exercisesByMuscle),
        allExercises: @js($exercises->map(fn($e) => [
            'id' => $e->id,
            'name' => $e->name,
            'description' => $e->description,
            'view_url' => route('exercise.show', $e->id),
            'body_sections' => $e->targetedMuscleNames(),
        ])->values()->all()),

        get filteredExercises() {
            let list = this.selectedMuscle && this.exercisesByMuscle[this.selectedMuscle]
                ? this.exercisesByMuscle[this.selectedMuscle]
                : this.allExercises;

            if (this.searchQuery.trim() !== '') {
                const q = this.searchQuery.toLowerCase();
                list = list.filter(e => 
                    e.name.toLowerCase().includes(q) || 
                    (e.description && e.description.toLowerCase().includes(q))
                );
            }
            return list;
        },

        handleMuscleSelect(e) {
            this.selectedMuscle = e.detail ? e.detail.muscle : null;
        },

        resetFilter() {
            this.selectedMuscle = null;
            this.$dispatch('muscle-selected', { muscle: null });
        }
    }" 
    @muscle-selected.window="handleMuscleSelect($event)"
    class="space-y-6">

    <!-- Header Banner -->
    <div class="card rounded-2xl border border-slate-150 bg-gradient-to-r from-primary/10 via-primary/5 to-transparent p-6 shadow-soft dark:border-navy-600 dark:bg-navy-700 dark:from-accent/15 dark:via-accent/5">
        <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
            <div>
                <div class="flex items-center space-x-2">
                    <span class="badge rounded-full bg-primary/20 text-primary dark:bg-accent/20 dark:text-accent-light px-3 py-0.5 text-tiny font-bold uppercase tracking-wider">
                        Interactive Anatomy
                    </span>
                    <span class="text-xs text-slate-400 dark:text-navy-300">&bull; 11 Muscle Groups &bull; {{ $exercises->count() }} Exercises</span>
                </div>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-3xl">
                    Muscle Groups &amp; Anatomy Explorer
                </h1>
                <p class="mt-1 text-sm text-slate-600 dark:text-navy-200 max-w-2xl">
                    Interact directly with the anatomical model below to explore which muscle groups are stimulated by each exercise, review movement mechanics, and optimize your workout routines.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('exercise.index') }}"
                    class="btn rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-soft hover:bg-slate-50 dark:border-navy-500 dark:bg-navy-600 dark:text-navy-100 dark:hover:bg-navy-500">
                    Exercise Library
                </a>
                <a href="{{ route('workout.index') }}"
                    class="btn rounded-xl bg-primary px-4 py-2.5 text-xs font-semibold text-white shadow-soft hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                    View Workout Plans
                </a>
            </div>
        </div>
    </div>

    <!-- Main Two-Column Layout -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 items-start">
        
        <!-- Left Column: Interactive Anatomical Body Map (Sticky on large screens) -->
        <div class="lg:col-span-5 xl:col-span-5 space-y-6 lg:sticky lg:top-20">
            <x-body-muscle-map 
                :interactive="true"
                :selected="$selectedMuscle"
                viewMode="both"
                height="h-72 sm:h-80"
                title="Interactive Body Anatomy"
                subtitle="Click any muscle group to isolate exercises" />

            <!-- Active Filter Card -->
            <div class="card rounded-xl border border-slate-150 bg-white p-4 shadow-soft dark:border-navy-600 dark:bg-navy-700">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-navy-300">
                        Selected Focus
                    </span>
                    <button type="button" 
                        x-show="selectedMuscle" 
                        @click="resetFilter()"
                        class="text-xs font-semibold text-primary hover:underline dark:text-accent-light">
                        Clear Selection
                    </button>
                </div>
                <div class="mt-2 flex items-center justify-between">
                    <div>
                        <div class="text-lg font-bold text-slate-800 dark:text-navy-100">
                            <span x-text="selectedMuscle || 'All Muscle Groups'"></span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-navy-300">
                            <span x-text="filteredExercises.length"></span> exercise<span x-text="filteredExercises.length === 1 ? '' : 's'"></span> targeted
                        </p>
                    </div>
                    <div class="flex items-center space-x-1">
                        <span class="size-3 rounded-full"
                            :class="selectedMuscle ? 'bg-primary dark:bg-accent' : 'bg-slate-300 dark:bg-navy-400'"></span>
                        <span class="text-xs font-medium text-slate-500 dark:text-navy-300"
                            x-text="selectedMuscle ? 'Filtered' : 'Showing All'"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Exercise Directory & Filter -->
        <div class="lg:col-span-7 xl:col-span-7 space-y-4">
            <!-- Search & Controls Bar -->
            <div class="card rounded-xl border border-slate-150 bg-white p-4 shadow-soft dark:border-navy-600 dark:bg-navy-700">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="relative flex-1">
                        <input 
                            type="text" 
                            x-model="searchQuery"
                            placeholder="Search exercises by name or description..." 
                            class="form-input w-full rounded-lg border border-slate-300 bg-transparent px-3.5 py-2 pl-9 text-xs text-slate-700 placeholder:text-slate-400 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:placeholder:text-navy-300 dark:hover:border-navy-400 dark:focus:border-accent" />
                        <span class="pointer-events-none absolute left-3 top-2.5 flex items-center justify-center text-slate-400 dark:text-navy-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                    </div>

                    <div class="flex items-center justify-between sm:justify-end gap-2">
                        <span class="text-xs font-medium text-slate-500 dark:text-navy-300">
                            Showing <span class="font-bold text-slate-800 dark:text-navy-100" x-text="filteredExercises.length"></span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Exercises Cards Grid -->
            <div class="grid grid-cols-1 gap-3.5 sm:grid-cols-2">
                <template x-for="exercise in filteredExercises" :key="exercise.id">
                    <div class="card flex flex-col justify-between rounded-xl border border-slate-150 bg-white p-4 shadow-soft transition-all hover:border-primary/40 hover:shadow-md dark:border-navy-600 dark:bg-navy-700 dark:hover:border-accent/40">
                        <div>
                            <div class="flex items-start justify-between gap-2">
                                <h4 class="font-bold text-sm text-slate-800 dark:text-navy-100 line-clamp-1" x-text="exercise.name"></h4>
                                <a :href="exercise.view_url" class="shrink-0 text-slate-400 hover:text-primary dark:hover:text-accent-light">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </div>

                            <p class="mt-2 text-xs leading-relaxed text-slate-500 dark:text-navy-300 line-clamp-2 min-h-[32px]"
                               x-text="exercise.description || 'No detailed form instructions recorded yet.'"></p>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-navy-600/70 flex flex-col gap-2.5">
                            <div class="flex flex-wrap gap-1">
                                <template x-for="sec in exercise.body_sections" :key="sec">
                                    <span 
                                        :class="{
                                            'bg-primary text-white dark:bg-accent font-bold': selectedMuscle && selectedMuscle.toLowerCase() === sec.toLowerCase(),
                                            'bg-slate-100 text-slate-600 dark:bg-navy-800 dark:text-navy-200': !selectedMuscle || selectedMuscle.toLowerCase() !== sec.toLowerCase()
                                        }"
                                        class="badge rounded-md px-2 py-0.5 text-tiny font-semibold transition-colors"
                                        x-text="sec">
                                    </span>
                                </template>
                            </div>

                            <div class="flex items-center justify-between pt-1">
                                <a :href="exercise.view_url"
                                    class="text-xs font-semibold text-primary hover:text-primary-focus dark:text-accent-light dark:hover:text-accent flex items-center gap-1">
                                    <span>View Anatomy & Details</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Empty Search Results -->
            <div x-show="filteredExercises.length === 0"
                class="card rounded-xl border border-slate-150 bg-white p-12 text-center shadow-soft dark:border-navy-600 dark:bg-navy-700">
                <svg class="mx-auto size-12 text-slate-300 dark:text-navy-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h4 class="mt-3 text-base font-semibold text-slate-700 dark:text-navy-100">No matching exercises found</h4>
                <p class="mt-1 text-xs text-slate-400 dark:text-navy-300">Try adjusting your search query or selecting a different muscle group.</p>
                <button type="button" @click="searchQuery = ''; resetFilter();" class="mt-4 btn h-8 rounded-lg bg-primary px-3 text-xs font-semibold text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                    Reset All Filters
                </button>
            </div>
        </div>

    </div>
</div>
@endsection
