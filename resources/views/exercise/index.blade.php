@extends('layouts.app')

@section('title', 'Exercises')

@section('content')
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                    <div>
                        <h2 class="text-white mb-0">EXERCISE CATALOG</h2>
                        <p class="text-muted mb-0">Manage global exercise definitions and guides.</p>
                    </div>
                    <div>
                        <a href="{{ route('exercise.create') }}" class="btn btn-primary d-inline-flex align-items-center mt-2 mt-sm-0">
                            <svg class="mr-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                            </svg>
                            Create Exercise
                        </a>
                    </div>
                </div>

                <!-- Search & Filters -->
                <div class="mb-4">
                    <div class="form-group mb-3">
                        <input type="text" id="exerciseSearch" class="form-control font-weight-normal" placeholder="Search exercises by name or description..." style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255,255,255,0.08); color: #fff; padding: 0.75rem 1.25rem; font-size: 1rem; border-radius: 8px;">
                    </div>
                    <div class="d-flex flex-wrap" style="gap: 0.5rem;" id="muscleFilters">
                        <button class="btn btn-sm btn-outline-primary active" data-filter="all" style="border-radius: 20px; font-weight: 600; padding: 0.4rem 1rem;">All Muscles</button>
                        @foreach(\App\Models\Body\Body::orderBy('name', 'asc')->get() as $body)
                            <button class="btn btn-sm btn-outline-secondary" data-filter="{{ strtolower($body->name) }}" style="border-radius: 20px; font-weight: 600; padding: 0.4rem 1rem; border-color: rgba(255,255,255,0.08); color: #94a3b8;">
                                {{ $body->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">ALL EXERCISES</div>

                    <div class="list-group list-group-flush">
                        @forelse( \App\Models\Exercise\Exercise::get() as $exercise )
                            <a href="{{ route('exercise.show', $exercise->id) }}"
                               class="list-group-item list-group-item-action py-3 d-flex justify-content-between align-items-center exercise-item"
                               data-name="{{ strtolower($exercise->name) }}"
                               data-desc="{{ strtolower($exercise->description) }}"
                               data-muscles="{{ strtolower($exercise->body_section->pluck('name')->implode(',')) }}">
                                <div>
                                    <h5 class="text-white font-weight-bold mb-1">{{ $exercise->name }}</h5>
                                    <small class="text-muted">{{ Str::limit($exercise->description, 60, '...') ?: 'No description provided.' }}</small>
                                </div>
                                <span class="btn btn-outline-primary btn-sm py-1 px-3">
                                    Configure
                                </span>
                            </a>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <svg class="mb-3" width="40" height="40" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
                                </svg>
                                <h5>No exercises found</h5>
                                <p class="mb-0">Add exercises to populate the global catalog.</p>
                            </div>
                        @endforelse
                    </div>
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
                        item.classList.remove('d-none');
                        item.style.setProperty('display', 'flex', 'important');
                    } else {
                        item.classList.add('d-none');
                        item.style.setProperty('display', 'none', 'important');
                    }
                });
            }

            // Search event
            if (searchInput) {
                searchInput.addEventListener('input', function (e) {
                    searchQuery = e.target.value.toLowerCase().trim();
                    filterExercises();
                });
            }

            // Filter button events
            filterButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    filterButtons.forEach(b => {
                        b.classList.remove('active', 'btn-outline-primary');
                        b.classList.add('btn-outline-secondary');
                        b.style.color = '#94a3b8';
                        b.style.borderColor = 'rgba(255,255,255,0.08)';
                    });

                    this.classList.remove('btn-outline-secondary');
                    this.classList.add('active', 'btn-outline-primary');
                    this.style.color = '';
                    this.style.borderColor = '';

                    activeFilter = this.getAttribute('data-filter');
                    filterExercises();
                });
            });
        });
    </script>
@endsection
