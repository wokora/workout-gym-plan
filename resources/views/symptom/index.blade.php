@extends('layouts.app')

@section('title', 'Symptoms Directory')

@section('content')
<div class="space-y-6">

    <!-- Flash message -->
    @if(session('success'))
        <div class="alert flex rounded-lg border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-emerald-500 text-xs font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col justify-between space-y-3 sm:flex-row sm:items-center sm:space-y-0">
        <div>
            <div class="flex items-center space-x-2">
                <h1 class="text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl uppercase">
                    Symptoms List
                </h1>
                <span class="badge rounded-full bg-amber-500/10 text-amber-500 px-2.5 py-0.5 text-tiny font-bold">
                    {{ $symptoms->count() }} Symptoms
                </span>
            </div>
            <p class="mt-1 text-xs text-slate-400 dark:text-navy-300">
                Browse common symptoms and find which conditions they might be connected with.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('symptom.create') }}"
                class="btn space-x-1.5 rounded-xl bg-primary px-3.5 py-2 text-xs font-semibold text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Symptom</span>
            </a>
            <a href="{{ route('disease.index') }}"
                class="btn space-x-1.5 rounded-xl border border-slate-300 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-150 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Diseases Database</span>
            </a>
            <a href="{{ route('treatment.index') }}"
                class="btn space-x-1.5 rounded-xl border border-slate-300 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-150 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-primary dark:text-accent-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
                <span>Treatments Catalog</span>
            </a>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="card p-4 sm:p-5 rounded-xl border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <form method="GET" action="{{ route('symptom.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-12 sm:gap-4 items-center">
            <div class="sm:col-span-8 relative">
                <input type="text" name="q" value="{{ request('q') }}"
                    placeholder="Search symptom name (e.g. cough, headache, bloating)..."
                    class="form-input w-full rounded-lg border border-slate-300 bg-transparent py-2.5 pl-10 pr-12 text-sm text-slate-800 placeholder:text-slate-400 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">
                <span class="pointer-events-none absolute left-3 top-2.5 text-slate-400 dark:text-navy-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                @if(request('q'))
                    <a href="{{ route('symptom.index', request()->except('q')) }}" class="absolute right-3 top-3 text-xs text-slate-400 hover:text-slate-600 dark:hover:text-navy-200">
                        &times; Clear
                    </a>
                @endif
            </div>

            <div class="sm:col-span-4">
                <select name="body_system" onchange="this.form.submit()"
                    class="form-select w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2.5 text-xs text-slate-700 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">
                    <option value="">All Body Systems</option>
                    @foreach($bodySystems as $system)
                        <option value="{{ $system }}" {{ request('body_system') === $system ? 'selected' : '' }}>
                            {{ $system }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- Symptoms Grouped By Body System -->
    @php
        $grouped = $symptoms->groupBy('body_system');
    @endphp

    <div class="space-y-6">
        @forelse($grouped as $system => $items)
            <div class="space-y-3">
                <div class="flex items-center space-x-2 border-b border-slate-150 pb-2 dark:border-navy-600">
                    <div class="size-2 rounded-full bg-amber-500"></div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-navy-100">
                        {{ $system ?? 'General' }} ({{ $items->count() }})
                    </h3>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($items as $symptom)
                        <div class="card rounded-xl border border-slate-150 bg-white p-4 shadow-soft dark:border-navy-600 dark:bg-navy-700 flex flex-col justify-between space-y-3">
                            <div class="space-y-2">
                                <div class="flex items-start justify-between gap-2">
                                    <h4 class="text-sm font-bold text-slate-800 dark:text-navy-50">
                                        <a href="{{ route('symptom.show', $symptom->slug) }}" class="hover:text-primary dark:hover:text-accent-light transition-colors">
                                            {{ $symptom->name }}
                                        </a>
                                    </h4>
                                </div>

                                @if($symptom->description)
                                    <p class="text-xs text-slate-600 dark:text-navy-200 leading-relaxed">
                                        {{ $symptom->description }}
                                    </p>
                                @endif

                                @if($symptom->diseases->count() > 0)
                                    <div class="space-y-1 pt-1 border-t border-slate-150 dark:border-navy-600">
                                        <span class="text-tiny font-bold uppercase tracking-wider text-slate-400 dark:text-navy-300">
                                            Common in:
                                        </span>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($symptom->diseases as $disease)
                                                <a href="{{ route('disease.show', $disease->slug) }}"
                                                    class="badge rounded bg-primary/10 text-primary dark:bg-accent/15 dark:text-accent-light px-2 py-0.5 text-tiny font-medium hover:underline">
                                                    {{ $disease->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Card Footer Actions -->
                            <div class="flex items-center justify-between pt-2.5 border-t border-slate-100 dark:border-navy-600 text-tiny">
                                <a href="{{ route('symptom.show', $symptom->slug) }}"
                                    class="font-semibold text-primary hover:underline dark:text-accent-light flex items-center space-x-1">
                                    <span>Details</span>
                                    <span>&rarr;</span>
                                </a>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('symptom.edit', $symptom->slug) }}"
                                        class="btn rounded-lg bg-slate-100 px-2.5 py-1 font-semibold text-slate-700 hover:bg-slate-200 dark:bg-navy-600 dark:text-navy-100 dark:hover:bg-navy-500">
                                        Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="card p-8 text-center text-xs text-slate-400 dark:text-navy-300 rounded-xl border border-slate-150 bg-white dark:border-navy-600 dark:bg-navy-700">
                No symptoms match the current query.
            </div>
        @endforelse
    </div>

</div>
@endsection
