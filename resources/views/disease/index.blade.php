@extends('layouts.app')

@section('title', 'Diseases & Cures')

@section('content')
<div class="space-y-6">

    <!-- 1. Header & Actions -->
    <div class="flex flex-col justify-between space-y-3 sm:flex-row sm:items-center sm:space-y-0">
        <div>
            <div class="flex items-center space-x-2">
                <h1 class="text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl uppercase">
                    Diseases &amp; Cures
                </h1>
                <span class="badge rounded-full bg-primary/10 text-primary dark:bg-accent/15 dark:text-accent-light px-2.5 py-0.5 text-tiny font-bold">
                    {{ $totalDiseasesCount }} Documented
                </span>
            </div>
            <p class="mt-1 text-xs text-slate-400 dark:text-navy-300">
                A simple guide to diseases, what causes them, common symptoms, and what treatments or remedies cure them.
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('treatment.index') }}"
                class="btn space-x-1.5 rounded-xl border border-slate-300 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-150 focus:bg-slate-150 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-primary dark:text-accent-light" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
                <span>All Treatments &amp; Cures</span>
            </a>
            <a href="{{ route('symptom.index') }}"
                class="btn space-x-1.5 rounded-xl border border-slate-300 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-150 focus:bg-slate-150 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <span>Symptoms List</span>
            </a>
            <a href="{{ route('disease.create') }}"
                class="btn space-x-1.5 rounded-xl bg-primary px-3.5 py-2 text-xs font-semibold text-white shadow-soft hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Document Condition</span>
            </a>
        </div>
    </div>

    <!-- 2. Simple Stats -->
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3 sm:gap-4">
        <div class="card p-4 rounded-xl border border-slate-150 bg-white dark:border-navy-600 dark:bg-navy-700 flex items-center space-x-3">
            <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary dark:bg-accent/20 dark:text-accent-light">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-3-3v6m-7 4h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-400 dark:text-navy-300">Conditions Documented</p>
                <h3 class="text-lg font-bold text-slate-800 dark:text-navy-100">{{ $totalDiseasesCount }}</h3>
            </div>
        </div>

        <div class="card p-4 rounded-xl border border-slate-150 bg-white dark:border-navy-600 dark:bg-navy-700 flex items-center space-x-3">
            <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-emerald-500/10 text-emerald-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-400 dark:text-navy-300">Cures &amp; Treatments</p>
                <h3 class="text-lg font-bold text-slate-800 dark:text-navy-100">{{ $totalTreatmentsCount }}</h3>
            </div>
        </div>

        <div class="card p-4 rounded-xl border border-slate-150 bg-white dark:border-navy-600 dark:bg-navy-700 flex items-center space-x-3">
            <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-amber-500/10 text-amber-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-400 dark:text-navy-300">Known Symptoms</p>
                <h3 class="text-lg font-bold text-slate-800 dark:text-navy-100">{{ $totalSymptomsCount }}</h3>
            </div>
        </div>
    </div>

    <!-- 3. Search Bar -->
    <div class="card p-4 rounded-xl border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <form method="GET" action="{{ route('disease.index') }}" class="flex flex-col sm:flex-row gap-3 items-center">
            <div class="relative w-full">
                <input type="text" name="q" value="{{ request('q') }}"
                    placeholder="Search condition name, symptom (e.g. headache), or cure..."
                    class="form-input w-full rounded-lg border border-slate-300 bg-transparent py-2.5 pl-10 pr-12 text-sm text-slate-800 placeholder:text-slate-400 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">
                <span class="pointer-events-none absolute left-3 top-2.5 text-slate-400 dark:text-navy-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                @if(request('q'))
                    <a href="{{ route('disease.index') }}" class="absolute right-3 top-3 text-xs text-slate-400 hover:text-slate-600 dark:hover:text-navy-200">
                        &times; Clear
                    </a>
                @endif
            </div>

            <div class="flex items-center space-x-2 shrink-0 w-full sm:w-auto">
                <select name="sort" onchange="this.form.submit()"
                    class="form-select rounded-lg border border-slate-300 bg-transparent px-3 py-2.5 text-xs text-slate-700 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent w-full sm:w-auto">
                    <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name (A &rarr; Z)</option>
                    <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Name (Z &rarr; A)</option>
                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Recently Added</option>
                </select>

                <button type="submit"
                    class="btn rounded-lg bg-primary px-4 py-2.5 text-xs font-semibold text-white shadow-soft hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus shrink-0">
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- 4. Disease Cards Grid -->
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
        @forelse($diseases as $disease)
            <div class="card flex flex-col justify-between rounded-xl border border-slate-150 bg-white p-5 shadow-soft transition-all duration-200 hover:-translate-y-1 hover:shadow-lg dark:border-navy-600 dark:bg-navy-700">
                <div class="space-y-3">
                    <!-- Title -->
                    <div>
                        <a href="{{ route('disease.show', $disease->slug) }}"
                            class="text-lg font-bold text-slate-800 hover:text-primary dark:text-navy-100 dark:hover:text-accent-light transition-colors">
                            {{ $disease->name }}
                        </a>
                    </div>

                    <!-- Overview snippet -->
                    <p class="text-xs text-slate-600 dark:text-navy-200 line-clamp-3 leading-relaxed">
                        {{ $disease->overview ?? 'No description added yet.' }}
                    </p>

                    <!-- Symptoms Preview -->
                    @if($disease->symptoms->count() > 0)
                        <div class="space-y-1 pt-1">
                            <span class="text-tiny font-bold uppercase tracking-wider text-slate-400 dark:text-navy-300">
                                Common Symptoms:
                            </span>
                            <div class="flex flex-wrap gap-1">
                                @foreach($disease->symptoms->take(4) as $symptom)
                                    <span class="badge rounded bg-slate-100 text-slate-700 dark:bg-navy-800 dark:text-navy-200 px-2 py-0.5 text-tiny font-medium">
                                        {{ $symptom->name }}
                                    </span>
                                @endforeach
                                @if($disease->symptoms->count() > 4)
                                    <span class="badge rounded bg-slate-100 text-slate-400 dark:bg-navy-800 px-1.5 py-0.5 text-tiny">
                                        +{{ $disease->symptoms->count() - 4 }} more
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Cures & Treatments summary -->
                    @if($disease->treatments->count() > 0)
                        <div class="space-y-1 pt-1">
                            <span class="text-tiny font-bold uppercase tracking-wider text-slate-400 dark:text-navy-300">
                                Cures &amp; Treatments:
                            </span>
                            <div class="flex flex-wrap gap-1">
                                @foreach($disease->treatments->take(2) as $treatment)
                                    <span class="badge rounded px-2 py-0.5 text-tiny font-medium {{ $treatment->pivot->is_primary_cure ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-primary/10 text-primary dark:bg-accent/15 dark:text-accent-light' }}">
                                        {{ $treatment->name }}
                                        @if($treatment->pivot->is_primary_cure)
                                            <span class="font-bold ml-0.5">&check; Cure</span>
                                        @endif
                                    </span>
                                @endforeach
                                @if($disease->treatments->count() > 2)
                                    <span class="text-tiny text-slate-400">
                                        +{{ $disease->treatments->count() - 2 }} more
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Footer -->
                <div class="mt-4 pt-3 border-t border-slate-150 dark:border-navy-600 flex items-center justify-between">
                    <a href="{{ route('disease.show', $disease->slug) }}"
                        class="text-xs font-bold text-primary hover:underline dark:text-accent-light flex items-center space-x-1">
                        <span>View Details &amp; Cures</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                    <a href="{{ route('disease.edit', $disease->id) }}"
                        class="btn size-7 rounded p-0 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-navy-600 dark:hover:text-navy-100"
                        title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full card p-8 text-center text-xs text-slate-400 dark:text-navy-300 rounded-xl border border-slate-150 bg-white dark:border-navy-600 dark:bg-navy-700">
                No matching conditions found. <a href="{{ route('disease.create') }}" class="text-primary dark:text-accent-light font-bold hover:underline">Add one now</a>.
            </div>
        @endforelse
    </div>

    <!-- 5. Pagination -->
    @if($diseases->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $diseases->links() }}
        </div>
    @endif

</div>
@endsection
