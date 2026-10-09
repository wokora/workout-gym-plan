@extends('layouts.app')

@section('title', $symptom->name . ' - Symptom Details')

@section('content')
<div class="space-y-6">

    <!-- Flash message -->
    @if(session('success'))
        <div class="alert flex rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-emerald-500 text-xs font-semibold shadow-soft">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- 1. Premium Header Banner -->
    <div class="card rounded-2xl border border-slate-150 bg-white p-6 shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <div class="flex flex-col justify-between space-y-4 md:flex-row md:items-center md:space-y-0">
            <div class="space-y-2">
                <!-- Breadcrumbs -->
                <div class="flex items-center space-x-2 text-xs font-medium">
                    <a href="{{ route('symptom.index') }}" class="text-slate-400 hover:text-primary dark:text-navy-300 dark:hover:text-accent-light transition-colors">
                        &larr; Symptoms List
                    </a>
                    @if($symptom->body_system)
                        <span class="text-slate-300 dark:text-navy-500">/</span>
                        <span class="text-slate-500 dark:text-navy-300">{{ $symptom->body_system }}</span>
                    @endif
                    <span class="text-slate-300 dark:text-navy-500">/</span>
                    <span class="text-slate-600 dark:text-navy-200 font-semibold">{{ $symptom->name }}</span>
                </div>

                <!-- Title & Tags -->
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-800 dark:text-navy-50 sm:text-3xl">
                        {{ $symptom->name }}
                    </h1>
                    @if($symptom->body_system)
                        <span class="badge rounded-full bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 px-3 py-1 text-tiny font-bold uppercase tracking-wider">
                            {{ $symptom->body_system }}
                        </span>
                    @endif
                </div>

                <!-- Quick Stats -->
                <div class="flex flex-wrap items-center gap-2 pt-1 text-xs text-slate-500 dark:text-navy-300">
                    <span class="inline-flex items-center space-x-1.5 rounded-lg bg-slate-100 dark:bg-navy-600 px-2.5 py-1 font-medium">
                        <span class="size-1.5 rounded-full bg-amber-500"></span>
                        <span>Present in {{ $symptom->diseases->count() }} documented condition(s)</span>
                    </span>
                    @php
                        $relatedTreatments = $symptom->diseases->flatMap->treatments->unique('id');
                    @endphp
                    @if($relatedTreatments->count() > 0)
                        <span class="inline-flex items-center space-x-1.5 rounded-lg bg-slate-100 dark:bg-navy-600 px-2.5 py-1 font-medium">
                            <span class="size-1.5 rounded-full bg-emerald-500"></span>
                            <span>{{ $relatedTreatments->count() }} Potential Cures / Remedies</span>
                        </span>
                    @endif
                </div>
            </div>

            <!-- Action buttons -->
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <a href="{{ route('symptom.edit', $symptom->slug) }}"
                    class="btn space-x-1.5 rounded-xl bg-primary px-4 py-2.5 text-xs font-semibold text-white shadow-soft hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus flex items-center transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Edit Symptom</span>
                </a>

                <form action="{{ route('symptom.destroy', $symptom->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this symptom?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="btn space-x-1 rounded-xl bg-rose-500/10 text-rose-500 hover:bg-rose-500/20 px-3.5 py-2.5 text-xs font-semibold transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Delete</span>
                    </button>
                </form>

                <a href="{{ route('symptom.index') }}"
                    class="btn space-x-1.5 rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-150 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 flex items-center transition-colors">
                    <span>All Symptoms</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Main 2-Column Layout (8 Cols Left Content + 4 Cols Right Sidebar) -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

        <!-- Left Column: Primary Clinical Profile & Conditions -->
        <div class="lg:col-span-8 space-y-6">

            <!-- A. What Does This Symptom Feel Like? -->
            <div class="card rounded-2xl border border-slate-150 bg-white p-5 sm:p-6 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-3">
                <h2 class="text-xs font-bold uppercase tracking-wider text-primary dark:text-accent-light flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Description &amp; Sensations (What does it feel like?)</span>
                </h2>

                <div class="rounded-xl bg-slate-50 p-4 text-sm font-medium leading-relaxed text-slate-800 dark:bg-navy-800 dark:text-navy-100 border border-slate-100 dark:border-navy-600">
                    {{ $symptom->description ?? 'No specific sensation description provided yet.' }}
                </div>
            </div>

            <!-- B. Associated Health Conditions (Where This Symptom Is Common) -->
            <div class="card rounded-2xl border border-slate-150 bg-white p-5 sm:p-6 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-150 pb-3 dark:border-navy-600">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-navy-100 flex items-center space-x-2">
                        <span class="size-2 rounded-full bg-amber-500"></span>
                        <span>Associated Health Conditions ({{ $symptom->diseases->count() }})</span>
                    </h2>
                    <a href="{{ route('disease.index') }}" class="text-tiny font-semibold text-primary hover:underline dark:text-accent-light">
                        All Conditions &rarr;
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($symptom->diseases as $disease)
                        <div class="rounded-xl border border-slate-150 p-4 hover:border-primary/40 dark:border-navy-600 dark:hover:border-accent space-y-2 transition-colors">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="text-sm font-bold text-slate-800 hover:text-primary dark:text-navy-50 dark:hover:text-accent-light">
                                    <a href="{{ route('disease.show', $disease->slug) }}">
                                        {{ $disease->name }}
                                    </a>
                                </h3>
                                <span class="badge rounded bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 px-2 py-0.5 text-tiny font-bold">
                                    {{ $disease->treatments->count() }} cure(s) available
                                </span>
                            </div>

                            @if($disease->overview)
                                <p class="text-xs text-slate-600 dark:text-navy-200 line-clamp-2 leading-relaxed">
                                    {{ $disease->overview }}
                                </p>
                            @endif

                            <div class="flex items-center justify-end pt-1">
                                <a href="{{ route('disease.show', $disease->slug) }}" class="text-tiny font-semibold text-primary hover:underline dark:text-accent-light">
                                    View Condition Guide &amp; Cures &rarr;
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-4 text-center">
                            This symptom is not currently linked to any conditions in the catalog. You can link symptoms when creating or editing a disease.
                        </p>
                    @endforelse
                </div>
            </div>

            <!-- C. Helpful Cures & Remedies Documented for Associated Conditions -->
            @if($relatedTreatments->count() > 0)
                <div class="card rounded-2xl border border-slate-150 bg-white p-5 sm:p-6 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-150 pb-3 dark:border-navy-600">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-navy-100 flex items-center space-x-2">
                            <span class="size-2 rounded-full bg-emerald-500"></span>
                            <span>Documented Remedies for Related Conditions ({{ $relatedTreatments->count() }})</span>
                        </h2>
                        <a href="{{ route('treatment.index') }}" class="text-tiny font-semibold text-primary hover:underline dark:text-accent-light">
                            All Treatments &rarr;
                        </a>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        @foreach($relatedTreatments as $treatment)
                            <div class="rounded-xl border border-slate-150 p-3.5 hover:border-emerald-500/40 dark:border-navy-600 dark:hover:border-emerald-500/40 flex flex-col justify-between space-y-2 transition-colors">
                                <div>
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-xs font-bold text-slate-800 dark:text-navy-100">
                                            <a href="{{ route('treatment.show', $treatment->slug) }}" class="hover:text-primary dark:hover:text-accent-light">
                                                {{ $treatment->name }}
                                            </a>
                                        </h4>
                                        @if($treatment->treatment_type)
                                            <span class="badge rounded bg-slate-100 text-slate-600 dark:bg-navy-600 dark:text-navy-200 px-1.5 py-0.5 text-tiny font-medium">
                                                {{ $treatment->treatment_type->name }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($treatment->description)
                                        <p class="text-tiny text-slate-500 dark:text-navy-300 mt-1 line-clamp-2">
                                            {{ $treatment->description }}
                                        </p>
                                    @endif
                                </div>
                                <div class="pt-2 border-t border-slate-100 dark:border-navy-600 flex justify-end">
                                    <a href="{{ route('treatment.show', $treatment->slug) }}" class="text-tiny font-semibold text-primary hover:underline dark:text-accent-light">
                                        Read Remedy &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        <!-- Right Column / Sidebar -->
        <div class="lg:col-span-4 space-y-6">

            <!-- 1. Symptom Snapshot & Severity Assessment Card -->
            <div class="card rounded-2xl border border-slate-150 bg-white p-5 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-4">
                <div class="border-b border-slate-150 pb-2.5 dark:border-navy-600">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-navy-100 flex items-center space-x-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Symptom Snapshot</span>
                    </h3>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-400">Body Area:</span>
                        <span class="font-bold text-slate-700 dark:text-navy-100">{{ $symptom->body_system ?? 'General / Not specified' }}</span>
                    </div>

                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-400">Associated Conditions:</span>
                        <span class="font-bold text-slate-700 dark:text-navy-100">{{ $symptom->diseases->count() }} documented</span>
                    </div>
                </div>
            </div>

            <!-- 2. When to Seek Medical Help / Red Flags -->
            <div class="card rounded-2xl border border-rose-500/20 bg-rose-500/5 p-5 shadow-soft dark:border-rose-500/20 dark:bg-rose-500/5 space-y-2.5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 flex items-center space-x-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Emergency Warning Signs</span>
                </h3>
                <p class="text-xs text-rose-800 dark:text-rose-200 leading-relaxed">
                    Seek immediate emergency medical care if this symptom is accompanied by sudden severe pain, high fever, shortness of breath, confusion, or lasts longer than 7 days without improvement.
                </p>
            </div>

            <!-- 3. Quick Actions -->
            <div class="card rounded-2xl border border-slate-150 bg-white p-5 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-2">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-navy-100 mb-2">
                    Quick Navigation
                </h3>
                <a href="{{ route('symptom.create') }}" class="btn w-full rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-navy-600 dark:hover:bg-navy-500 py-2 text-xs font-semibold text-slate-700 dark:text-navy-100 flex items-center justify-center space-x-1.5 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Add Another Symptom</span>
                </a>
                <a href="{{ route('disease.create') }}" class="btn w-full rounded-xl border border-slate-200 hover:bg-slate-50 dark:border-navy-500 dark:hover:bg-navy-600 py-2 text-xs font-semibold text-slate-700 dark:text-navy-100 flex items-center justify-center space-x-1.5 transition-colors">
                    <span>Record New Condition</span>
                </a>
            </div>

        </div>

    </div>

</div>
@endsection
