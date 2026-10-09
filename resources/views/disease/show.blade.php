@extends('layouts.app')

@section('title', $disease->name . ' - Cures & Details')

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
                    <a href="{{ route('disease.index') }}" class="text-slate-400 hover:text-primary dark:text-navy-300 dark:hover:text-accent-light transition-colors">
                        &larr; Diseases &amp; Cures
                    </a>
                    <span class="text-slate-300 dark:text-navy-500">/</span>
                    <span class="text-slate-600 dark:text-navy-200 font-semibold">{{ $disease->name }}</span>
                </div>

                <!-- Title & Tags -->
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-800 dark:text-navy-50 sm:text-3xl">
                        {{ $disease->name }}
                    </h1>
                    @if($primaryCures->count() > 0)
                        <span class="badge rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 px-3 py-1 text-tiny font-bold uppercase tracking-wider flex items-center space-x-1">
                            <span>&check;</span>
                            <span>{{ $primaryCures->count() }} Cure(s) Documented</span>
                        </span>
                    @endif
                </div>

                <!-- Stats summary bar -->
                <div class="flex flex-wrap items-center gap-2 pt-1 text-xs text-slate-500 dark:text-navy-300">
                    <span class="inline-flex items-center space-x-1.5 rounded-lg bg-slate-100 dark:bg-navy-600 px-2.5 py-1 font-medium">
                        <span class="size-1.5 rounded-full bg-emerald-500"></span>
                        <span>{{ $primaryCures->count() }} Primary Cure(s)</span>
                    </span>
                    <span class="inline-flex items-center space-x-1.5 rounded-lg bg-slate-100 dark:bg-navy-600 px-2.5 py-1 font-medium">
                        <span class="size-1.5 rounded-full bg-amber-500"></span>
                        <span>{{ $disease->symptoms->count() }} Symptom(s)</span>
                    </span>
                    @if($disease->foods->count() > 0)
                        <span class="inline-flex items-center space-x-1.5 rounded-lg bg-slate-100 dark:bg-navy-600 px-2.5 py-1 font-medium">
                            <span class="size-1.5 rounded-full bg-primary dark:bg-accent-light"></span>
                            <span>{{ $disease->foods->count() }} Food Guideline(s)</span>
                        </span>
                    @endif
                    @if($disease->images->count() > 0)
                        <span class="inline-flex items-center space-x-1.5 rounded-lg bg-slate-100 dark:bg-navy-600 px-2.5 py-1 font-medium">
                            <span class="size-1.5 rounded-full bg-indigo-500"></span>
                            <span>{{ $disease->images->count() }} Photo(s)</span>
                        </span>
                    @endif
                </div>
            </div>

            <!-- Action buttons -->
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <a href="{{ route('disease.edit', $disease->id) }}"
                    class="btn space-x-1.5 rounded-xl bg-primary px-4 py-2.5 text-xs font-semibold text-white shadow-soft hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus flex items-center transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Edit Condition</span>
                </a>

                <form action="{{ route('disease.destroy', $disease->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this condition?');" class="inline">
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

                <a href="{{ route('disease.index') }}"
                    class="btn space-x-1.5 rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-150 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 flex items-center transition-colors">
                    <span>All Diseases</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Main Layout Grid (8 Cols Left Content + 4 Cols Right Sidebar) -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

        <!-- Left / Primary Content Column -->
        <div class="lg:col-span-8 space-y-6">

            <!-- A. Condition Overview & Clinical Context -->
            <div class="card rounded-2xl border border-slate-150 bg-white p-5 sm:p-6 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-5">
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-primary dark:text-accent-light flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Overview &amp; Understanding</span>
                    </h2>
                    <p class="mt-2 text-sm leading-relaxed text-slate-700 dark:text-navy-200">
                        {{ $disease->overview ?? 'No overview description written yet for this condition.' }}
                    </p>
                </div>

                <!-- Causes and Prevention Sub-cards -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 pt-2 border-t border-slate-100 dark:border-navy-600">
                    <!-- Causes -->
                    <div class="rounded-xl border border-amber-500/20 bg-amber-500/5 p-4 dark:border-amber-500/20 dark:bg-amber-500/5 space-y-2">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400 flex items-center space-x-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>What Causes It? / Triggers</span>
                        </h3>
                        <p class="text-xs text-slate-700 dark:text-navy-200 leading-relaxed">
                            {{ $disease->causes ?? 'Specific causes have not been recorded yet.' }}
                        </p>
                    </div>

                    <!-- Prevention -->
                    <div class="rounded-xl border border-emerald-500/20 bg-emerald-500/5 p-4 dark:border-emerald-500/20 dark:bg-emerald-500/5 space-y-2">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 flex items-center space-x-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>How to Prevent It?</span>
                        </h3>
                        <p class="text-xs text-slate-700 dark:text-navy-200 leading-relaxed">
                            {{ $disease->prevention ?? 'Prevention tips have not been recorded yet.' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- B. Cures & Treatments Section (Core Value) -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="size-2.5 rounded-full bg-emerald-500"></div>
                        <h2 class="text-sm font-bold uppercase tracking-wide text-slate-800 dark:text-navy-100">
                            Cures, Treatments &amp; Remedies ({{ $disease->treatments->count() }})
                        </h2>
                    </div>
                    <a href="{{ route('treatment.create') }}" target="_blank" class="text-tiny font-bold text-primary hover:underline dark:text-accent-light">
                        + Add New Remedy
                    </a>
                </div>

                <!-- Primary Cures (Prominently Highlighted) -->
                @if($primaryCures->count() > 0)
                    <div class="space-y-3">
                        <span class="text-tiny font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 flex items-center space-x-1">
                            <span>&check; Primary Recommended Cures</span>
                        </span>

                        <div class="space-y-3">
                            @foreach($primaryCures as $treatment)
                                <div class="card rounded-2xl border-2 border-emerald-500/30 bg-white p-5 shadow-soft dark:border-emerald-500/30 dark:bg-navy-700 space-y-3 relative overflow-hidden transition-all hover:border-emerald-500/60">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                        <div class="space-y-1">
                                            <div class="flex items-center space-x-2">
                                                <h3 class="text-base font-bold text-slate-800 dark:text-navy-50">
                                                    <a href="{{ route('treatment.show', $treatment->slug) }}" class="hover:text-primary dark:hover:text-accent-light">
                                                        {{ $treatment->name }}
                                                    </a>
                                                </h3>
                                                @if($treatment->treatment_type)
                                                    <span class="badge rounded bg-slate-100 text-slate-600 dark:bg-navy-600 dark:text-navy-200 px-2 py-0.5 text-tiny font-medium">
                                                        {{ $treatment->treatment_type->name }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if($treatment->description)
                                                <p class="text-xs text-slate-600 dark:text-navy-200 leading-relaxed">
                                                    {{ $treatment->description }}
                                                </p>
                                            @endif
                                        </div>

                                        <span class="badge shrink-0 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 px-3 py-1 text-tiny font-extrabold uppercase tracking-wider flex items-center space-x-1 self-start sm:self-center">
                                            <span>&check; Primary Cure</span>
                                        </span>
                                    </div>

                                    <!-- Specific Protocol / Instructions Box -->
                                    <div class="rounded-xl bg-emerald-500/10 p-3.5 text-xs text-slate-700 dark:text-navy-200 border border-emerald-500/20 space-y-1">
                                        <div class="flex items-center space-x-1.5 font-bold text-emerald-800 dark:text-emerald-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            <span>How to Take / Specific Instructions:</span>
                                        </div>
                                        <p class="leading-relaxed pl-5">
                                            {{ $treatment->pivot->specific_instructions ?? ($treatment->standard_protocol ?? 'Follow general administration guidance on the treatment profile.') }}
                                        </p>
                                    </div>

                                    <div class="flex items-center justify-end pt-1">
                                        <a href="{{ route('treatment.show', $treatment->slug) }}"
                                            class="text-tiny font-bold text-primary hover:underline dark:text-accent-light flex items-center space-x-1">
                                            <span>View Treatment Profile</span>
                                            <span>&rarr;</span>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Other Supportive Treatments & Care -->
                @if($otherTreatments->count() > 0)
                    <div class="space-y-3 pt-2">
                        <span class="text-tiny font-bold uppercase tracking-wider text-slate-500 dark:text-navy-300 flex items-center space-x-1">
                            <span>Other Helpful Remedies &amp; Supportive Care</span>
                        </span>

                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            @foreach($otherTreatments as $treatment)
                                <div class="card rounded-xl border border-slate-150 bg-white p-4 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-2 flex flex-col justify-between">
                                    <div class="space-y-1.5">
                                        <div class="flex items-center justify-between">
                                            <h4 class="text-sm font-bold text-slate-800 dark:text-navy-50">
                                                <a href="{{ route('treatment.show', $treatment->slug) }}" class="hover:text-primary dark:hover:text-accent-light">
                                                    {{ $treatment->name }}
                                                </a>
                                            </h4>
                                            @if($treatment->treatment_type)
                                                <span class="badge rounded bg-slate-100 text-slate-600 dark:bg-navy-600 dark:text-navy-200 px-2 py-0.5 text-tiny font-medium">
                                                    {{ $treatment->treatment_type->name }}
                                                </span>
                                            @endif
                                        </div>

                                        @if($treatment->description)
                                            <p class="text-xs text-slate-600 dark:text-navy-200 line-clamp-2">
                                                {{ $treatment->description }}
                                            </p>
                                        @endif

                                        @if($treatment->pivot->specific_instructions)
                                            <div class="rounded-lg bg-slate-50 p-2 text-tiny text-slate-600 dark:bg-navy-800 dark:text-navy-200 border border-slate-100 dark:border-navy-600">
                                                <strong>Notes:</strong> {{ $treatment->pivot->specific_instructions }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="pt-2 border-t border-slate-100 dark:border-navy-600 flex items-center justify-end text-tiny">
                                        <a href="{{ route('treatment.show', $treatment->slug) }}" class="font-semibold text-primary hover:underline dark:text-accent-light">
                                            View Details &rarr;
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($disease->treatments->count() === 0)
                    <div class="card p-6 text-center text-xs text-slate-400 rounded-2xl border border-slate-150 bg-white dark:border-navy-600 dark:bg-navy-700 space-y-2">
                        <p>No treatments or remedies have been attached to this condition yet.</p>
                        <a href="{{ route('disease.edit', $disease->id) }}" class="btn rounded-xl bg-primary/10 text-primary hover:bg-primary/20 dark:bg-accent/15 dark:text-accent-light px-3 py-1.5 text-xs font-semibold inline-block">
                            + Attach Remedies
                        </a>
                    </div>
                @endif
            </div>

            <!-- C. Dietary & Nutrition Advice (If foods present) -->
            @if($disease->foods->count() > 0)
                <div class="card rounded-2xl border border-slate-150 bg-white p-5 sm:p-6 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-4">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-primary dark:text-accent-light flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <span>Diet &amp; Nutrition Guidelines</span>
                    </h2>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Foods that Help -->
                        @if($helpfulFoods->count() > 0)
                            <div class="rounded-xl border border-emerald-500/20 bg-emerald-500/5 p-4 space-y-2.5">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 flex items-center space-x-1">
                                    <span>&check; Recommended Foods</span>
                                </h3>
                                <div class="space-y-2">
                                    @foreach($helpfulFoods as $food)
                                        <div class="border-b border-emerald-500/10 pb-2 last:border-0 last:pb-0">
                                            <a href="{{ route('food.show', $food->id) }}" class="text-xs font-bold text-slate-800 dark:text-navy-100 hover:text-emerald-600 dark:hover:text-emerald-400">
                                                {{ $food->name }}
                                            </a>
                                            @if($food->pivot->rationale)
                                                <p class="text-tiny text-slate-600 dark:text-navy-300 mt-0.5">{{ $food->pivot->rationale }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Foods to Avoid -->
                        @if($avoidFoods->count() > 0)
                            <div class="rounded-xl border border-rose-500/20 bg-rose-500/5 p-4 space-y-2.5">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 flex items-center space-x-1">
                                    <span>&times; Foods to Avoid / Limit</span>
                                </h3>
                                <div class="space-y-2">
                                    @foreach($avoidFoods as $food)
                                        <div class="border-b border-rose-500/10 pb-2 last:border-0 last:pb-0">
                                            <a href="{{ route('food.show', $food->id) }}" class="text-xs font-bold text-slate-800 dark:text-navy-100 hover:text-rose-600 dark:hover:text-rose-400">
                                                {{ $food->name }}
                                            </a>
                                            @if($food->pivot->rationale)
                                                <p class="text-tiny text-slate-600 dark:text-navy-300 mt-0.5">{{ $food->pivot->rationale }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>

        <!-- Right / Sidebar Column -->
        <div class="lg:col-span-4 space-y-6">

            <!-- 1. Symptoms Checklist Card -->
            <div class="card rounded-2xl border border-slate-150 bg-white p-5 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-150 pb-3 dark:border-navy-600">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-navy-100 flex items-center space-x-2">
                        <span class="size-2 rounded-full bg-amber-500"></span>
                        <span>Common Signs &amp; Symptoms ({{ $disease->symptoms->count() }})</span>
                    </h3>
                    <a href="{{ route('symptom.create') }}" target="_blank" class="text-tiny font-bold text-primary hover:underline dark:text-accent-light">
                        + New
                    </a>
                </div>

                <div class="space-y-2.5">
                    @forelse($disease->symptoms as $symptom)
                        <div class="rounded-xl border border-slate-150 bg-slate-50/70 p-3 hover:border-amber-500/40 dark:border-navy-600 dark:bg-navy-800/60 transition-colors">
                            <div class="flex items-start justify-between gap-1">
                                <a href="{{ route('symptom.show', $symptom->slug) }}" class="text-xs font-bold text-slate-800 hover:text-primary dark:text-navy-100 dark:hover:text-accent-light">
                                    {{ $symptom->name }}
                                </a>
                            </div>
                            @if($symptom->description)
                                <p class="text-tiny text-slate-500 dark:text-navy-300 mt-1 leading-relaxed">
                                    {{ $symptom->description }}
                                </p>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-3 text-center">
                            No symptoms linked to this condition yet.
                        </p>
                    @endforelse
                </div>

                <div class="pt-2 border-t border-slate-100 dark:border-navy-600 text-center">
                    <a href="{{ route('symptom.index') }}" class="text-tiny font-semibold text-primary hover:underline dark:text-accent-light">
                        Browse Full Symptoms Directory &rarr;
                    </a>
                </div>
            </div>

            <!-- 2. Attached Photos & Images Gallery -->
            <div class="card rounded-2xl border border-slate-150 bg-white p-5 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-3">
                <div class="flex items-center justify-between border-b border-slate-150 pb-2.5 dark:border-navy-600">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-navy-100 flex items-center space-x-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-primary dark:text-accent-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Photos &amp; Documents ({{ $disease->images->count() }})</span>
                    </h3>
                </div>

                <!-- Upload Widget -->
                <form action="{{ route('disease.image.upload', $disease->id) }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                    @csrf
                    <input type="file" name="image" accept="image/*" required
                        class="form-input w-full text-tiny file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-tiny file:font-semibold file:bg-primary/10 file:text-primary dark:file:bg-accent/20 dark:file:text-accent-light">
                    <button type="submit"
                        class="btn w-full rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-navy-600 dark:hover:bg-navy-500 py-1.5 text-tiny font-bold text-slate-700 dark:text-navy-100 transition-colors">
                        Upload Image
                    </button>
                </form>

                <!-- Thumbnails Grid -->
                @if($disease->images->count() > 0)
                    <div class="grid grid-cols-2 gap-2 pt-1">
                        @foreach($disease->images as $file)
                            <a href="{{ $file->get_url() }}" target="_blank" class="group rounded-xl overflow-hidden border border-slate-150 dark:border-navy-600 bg-slate-50 dark:bg-navy-800 block relative">
                                <img src="{{ $file->get_url() }}" alt="{{ $file->original_name }}" class="aspect-square w-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <span class="absolute bottom-0 inset-x-0 bg-black/60 text-white text-[10px] p-1 truncate">
                                    {{ $file->original_name }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- 3. Friendly Wellness Notice -->
            <div class="rounded-2xl border border-slate-200/80 bg-slate-50/80 p-4 dark:border-navy-600 dark:bg-navy-800/40 text-tiny text-slate-500 dark:text-navy-300 space-y-1.5 leading-relaxed">
                <div class="flex items-center space-x-1.5 font-bold text-slate-700 dark:text-navy-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-primary dark:text-accent-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Everyday Reference Note</span>
                </div>
                <p>
                    This documentation is designed for personal health tracking. For acute symptoms, severe pain, or conditions that do not improve, always consult a licensed healthcare professional.
                </p>
            </div>

        </div>

    </div>

</div>
@endsection
