@extends('layouts.app')

@section('title', 'Exercise Catalog')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col justify-between space-y-3 sm:flex-row sm:items-center sm:space-y-0">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl uppercase">
                Exercise Catalog
            </h2>
            <p class="text-sm text-slate-500 dark:text-navy-300">
                Manage global exercise definitions and guides.
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
            <a href="{{ route('exercise.create') }}"
                class="btn space-x-2 rounded-xl bg-primary px-4 py-2.5 text-xs font-semibold text-white shadow-soft hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Create Exercise</span>
            </a>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="space-y-3">
        <div class="relative">
            <input type="text" id="exerciseSearch"
                class="form-input w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-sm text-slate-800 shadow-soft placeholder:text-slate-400 hover:border-slate-300 focus:border-primary dark:border-navy-600 dark:bg-navy-700 dark:text-navy-100 dark:hover:border-navy-500 dark:focus:border-accent"
                placeholder="Search exercises by name or description...">
            <span class="pointer-events-none absolute left-3.5 top-3 text-slate-400 dark:text-navy-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
        </div>

        <div class="flex flex-wrap gap-2" id="muscleFilters">
            <button class="filter-btn btn h-8 rounded-full bg-primary text-white text-xs font-semibold px-4 active" data-filter="all">
                All Muscles
            </button>
            @foreach(\App\Models\Body\Body::orderBy('name', 'asc')->get() as $body)
                <button class="filter-btn btn h-8 rounded-full border border-slate-200 bg-white text-slate-600 hover:bg-slate-100 text-xs font-semibold px-4 dark:border-navy-600 dark:bg-navy-700 dark:text-navy-200 dark:hover:bg-navy-600"
                    data-filter="{{ strtolower($body->name) }}">
                    {{ $body->name }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Exercise List Card -->
    <div class="card rounded-xl border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <div class="border-b border-slate-150 px-5 py-4 dark:border-navy-600 font-semibold text-xs uppercase tracking-wider text-slate-600 dark:text-navy-200">
            All Exercises
        </div>

        <div class="divide-y divide-slate-150 dark:divide-navy-600" id="exerciseContainer">
            @forelse(\App\Models\Exercise\Exercise::get() as $exercise)
                <div class="exercise-item flex flex-col justify-between p-5 transition-colors hover:bg-slate-50 sm:flex-row sm:items-center dark:hover:bg-navy-800/50"
                    data-name="{{ strtolower($exercise->name) }}"
                    data-desc="{{ strtolower($exercise->description) }}"
                    data-muscles="{{ strtolower($exercise->body_section->pluck('name')->implode(',')) }}">
                    <div class="mb-3 sm:mb-0">
                        <a href="{{ route('exercise.show', $exercise->id) }}" class="text-base font-bold text-slate-800 hover:text-primary dark:text-navy-100 dark:hover:text-accent-light">
                            {{ $exercise->name }}
                        </a>
                        <p class="mt-1 text-xs text-slate-400 dark:text-navy-300 line-clamp-1">
                            {{ Str::limit($exercise->description, 70, '...') ?: 'No description provided.' }}
                        </p>
                        @if($exercise->body_section->isNotEmpty())
                            <div class="mt-2 flex flex-wrap gap-1">
                                @foreach($exercise->body_section as $b)
                                    <span class="badge rounded bg-slate-100 px-2 py-0.5 text-tiny font-semibold text-slate-600 dark:bg-navy-800 dark:text-navy-300">
                                        {{ $b->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('exercise.show', $exercise->id) }}"
                            class="btn h-8 rounded-lg border border-primary/30 px-3 text-xs font-semibold text-primary hover:bg-primary/10 dark:border-accent/30 dark:text-accent-light dark:hover:bg-accent/10">
                            Configure
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <svg class="mx-auto size-12 text-slate-300 dark:text-navy-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <h4 class="mt-3 text-base font-semibold text-slate-700 dark:text-navy-100">No exercises found</h4>
                    <p class="mt-1 text-xs text-slate-400 dark:text-navy-300">Add exercises to populate the global catalog.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('exerciseSearch');
        const filterButtons = document.querySelectorAll('#muscleFilters button');
        const exerciseItems = document.querySelectorAll('.exercise-item');

        let activeFilter = 'all';
        let searchQuery = '';

        function filterExercises() {
            exerciseItems.forEach(item => {
                const name = item.getAttribute('data-name') || '';
                const desc = item.getAttribute('data-desc') || '';
                const muscles = (item.getAttribute('data-muscles') || '').split(',');

                const matchesSearch = name.includes(searchQuery) || desc.includes(searchQuery);
                const matchesFilter = activeFilter === 'all' || muscles.includes(activeFilter);

                if (matchesSearch && matchesFilter) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', function (e) {
                searchQuery = e.target.value.toLowerCase().trim();
                filterExercises();
            });
        }

        filterButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                filterButtons.forEach(b => {
                    b.classList.remove('bg-primary', 'text-white', 'dark:bg-accent');
                    b.classList.add('bg-white', 'text-slate-600', 'dark:bg-navy-700', 'dark:text-navy-200');
                });

                this.classList.remove('bg-white', 'text-slate-600', 'dark:bg-navy-700', 'dark:text-navy-200');
                this.classList.add('bg-primary', 'text-white', 'dark:bg-accent');

                activeFilter = this.getAttribute('data-filter');
                filterExercises();
            });
        });
    });
</script>
@endsection
