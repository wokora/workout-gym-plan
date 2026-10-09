@extends('layouts.app')

@section('title', 'Treatments & Cures Catalog')

@section('content')
<div class="space-y-6">

    <!-- 1. Header -->
    <div class="flex flex-col justify-between space-y-3 sm:flex-row sm:items-center sm:space-y-0">
        <div>
            <div class="flex items-center space-x-2">
                <h1 class="text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl uppercase">
                    Treatments &amp; Cures Catalog
                </h1>
                <span class="badge rounded-full bg-primary/10 text-primary dark:bg-accent/15 dark:text-accent-light px-2.5 py-0.5 text-tiny font-bold">
                    {{ $totalTreatmentsCount }} Available
                </span>
            </div>
            <p class="mt-1 text-xs text-slate-400 dark:text-navy-300">
                Medicines, home remedies, dietary changes, and lifestyle cures for documented health conditions.
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('disease.index') }}"
                class="btn space-x-1.5 rounded-xl border border-slate-300 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-150 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Diseases Database</span>
            </a>
            <a href="{{ route('symptom.index') }}"
                class="btn space-x-1.5 rounded-xl border border-slate-300 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-150 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <span>Symptoms List</span>
            </a>
            <a href="{{ route('treatment.create') }}"
                class="btn space-x-1.5 rounded-xl bg-primary px-3.5 py-2 text-xs font-semibold text-white shadow-soft hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Treatment / Cure</span>
            </a>
        </div>
    </div>

    <!-- 2. Filters -->
    <div class="card p-4 sm:p-5 rounded-xl border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <form method="GET" action="{{ route('treatment.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-12 sm:gap-4 items-center">
            <!-- Search -->
            <div class="sm:col-span-8 relative">
                <input type="text" name="q" value="{{ request('q') }}"
                    placeholder="Search treatment name or notes..."
                    class="form-input w-full rounded-lg border border-slate-300 bg-transparent py-2.5 pl-10 pr-12 text-sm text-slate-800 placeholder:text-slate-400 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">
                <span class="pointer-events-none absolute left-3 top-2.5 text-slate-400 dark:text-navy-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                @if(request('q'))
                    <a href="{{ route('treatment.index', request()->except('q', 'page')) }}" class="absolute right-3 top-3 text-xs text-slate-400 hover:text-slate-600 dark:hover:text-navy-200">
                        &times; Clear
                    </a>
                @endif
            </div>

            <!-- Treatment Type -->
            <div class="sm:col-span-4">
                <select name="treatment_type_id" onchange="this.form.submit()"
                    class="form-select w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2.5 text-xs text-slate-700 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">
                    <option value="">All Treatment Types</option>
                    @foreach($treatmentTypes as $type)
                        <option value="{{ $type->id }}" {{ request('treatment_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }} ({{ $type->treatments_count }})
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- 3. Treatments Grid -->
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
        @forelse($treatments as $treatment)
            <div class="card flex flex-col justify-between rounded-xl border border-slate-150 bg-white p-5 shadow-soft transition-all duration-200 hover:-translate-y-1 hover:shadow-lg dark:border-navy-600 dark:bg-navy-700">
                <div class="space-y-3">
                    @if($treatment->treatment_type)
                        <span class="badge rounded bg-slate-100 text-slate-700 dark:bg-navy-600 dark:text-navy-100 px-2 py-0.5 text-tiny font-semibold">
                            {{ $treatment->treatment_type->name }}
                        </span>
                    @endif

                    <h3 class="text-base font-bold text-slate-800 dark:text-navy-100">
                        <a href="{{ route('treatment.show', $treatment->slug) }}" class="hover:text-primary dark:hover:text-accent-light">
                            {{ $treatment->name }}
                        </a>
                    </h3>

                    <p class="text-xs text-slate-600 dark:text-navy-200 line-clamp-3 leading-relaxed">
                        {{ $treatment->description }}
                    </p>

                    @if($treatment->diseases->count() > 0)
                        <div class="space-y-1 pt-1">
                            <span class="text-tiny font-bold uppercase tracking-wider text-slate-400 dark:text-navy-300">
                                Helps With:
                            </span>
                            <div class="flex flex-wrap gap-1">
                                @foreach($treatment->diseases as $disease)
                                    <a href="{{ route('disease.show', $disease->slug) }}"
                                        class="badge rounded bg-primary/10 text-primary dark:bg-accent/15 dark:text-accent-light px-2 py-0.5 text-tiny font-medium hover:underline">
                                        {{ $disease->name }}
                                        @if($disease->pivot->is_primary_cure)
                                            <span class="ml-1 text-emerald-500 font-bold">&check;</span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mt-4 pt-3 border-t border-slate-150 dark:border-navy-600 flex justify-between items-center">
                    <a href="{{ route('treatment.show', $treatment->slug) }}"
                        class="text-xs font-semibold text-primary hover:underline dark:text-accent-light">
                        View Details &rarr;
                    </a>
                    <a href="{{ route('treatment.edit', $treatment->slug) }}"
                        class="btn size-7 rounded p-0 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-navy-600 dark:hover:text-navy-100"
                        title="Edit Treatment">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full card p-8 text-center text-xs text-slate-400 dark:text-navy-300 rounded-xl border border-slate-150 bg-white dark:border-navy-600 dark:bg-navy-700">
                No treatments match your search filter.
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($treatments->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $treatments->links() }}
        </div>
    @endif

</div>
@endsection
