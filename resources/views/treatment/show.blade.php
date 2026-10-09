@extends('layouts.app')

@section('title', $treatment->name . ' - Treatment & Remedy Details')

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
                    <a href="{{ route('treatment.index') }}" class="text-slate-400 hover:text-primary dark:text-navy-300 dark:hover:text-accent-light transition-colors">
                        &larr; Treatments &amp; Remedies
                    </a>
                    @if($treatment->treatment_type)
                        <span class="text-slate-300 dark:text-navy-500">/</span>
                        <span class="text-slate-500 dark:text-navy-300">{{ $treatment->treatment_type->name }}</span>
                    @endif
                    <span class="text-slate-300 dark:text-navy-500">/</span>
                    <span class="text-slate-600 dark:text-navy-200 font-semibold">{{ $treatment->name }}</span>
                </div>

                <!-- Title & Tags -->
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-800 dark:text-navy-50 sm:text-3xl">
                        {{ $treatment->name }}
                    </h1>
                    @if($treatment->treatment_type)
                        <span class="badge rounded-full bg-primary/10 text-primary dark:bg-accent/15 dark:text-accent-light px-3 py-1 text-tiny font-bold uppercase tracking-wider">
                            {{ $treatment->treatment_type->name }}
                        </span>
                    @endif
                </div>

                <!-- Quick Stats -->
                <div class="flex flex-wrap items-center gap-2 pt-1 text-xs text-slate-500 dark:text-navy-300">
                    <span class="inline-flex items-center space-x-1.5 rounded-lg bg-slate-100 dark:bg-navy-600 px-2.5 py-1 font-medium">
                        <span class="size-1.5 rounded-full bg-emerald-500"></span>
                        <span>Helps with {{ $treatment->diseases->count() }} condition(s)</span>
                    </span>
                    @if($treatment->images->count() > 0)
                        <span class="inline-flex items-center space-x-1.5 rounded-lg bg-slate-100 dark:bg-navy-600 px-2.5 py-1 font-medium">
                            <span class="size-1.5 rounded-full bg-indigo-500"></span>
                            <span>{{ $treatment->images->count() }} Reference Photo(s)</span>
                        </span>
                    @endif
                </div>
            </div>

            <!-- Action buttons -->
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <a href="{{ route('treatment.edit', $treatment->slug) }}"
                    class="btn space-x-1.5 rounded-xl bg-primary px-4 py-2.5 text-xs font-semibold text-white shadow-soft hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus flex items-center transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Edit Treatment</span>
                </a>

                <form action="{{ route('treatment.destroy', $treatment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this treatment?');" class="inline">
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

                <a href="{{ route('treatment.index') }}"
                    class="btn space-x-1.5 rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-150 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 flex items-center transition-colors">
                    <span>All Treatments</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Main 2-Column Layout (8 Cols Left Content + 4 Cols Right Sidebar) -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

        <!-- Left Column: Primary Directions & Clinical Details (Single Master Card, No Child Cards) -->
        <div class="lg:col-span-8">
            <div class="card rounded-2xl border border-slate-150 bg-white p-6 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-6">

                <!-- A. How to Take / Standard Protocol -->
                <div>
                    <div class="flex items-center space-x-2 text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>How to Take / Use It (Standard Protocol)</span>
                    </div>

                    <p class="mt-2.5 text-sm font-medium leading-relaxed text-slate-800 dark:text-navy-100">
                        {{ $treatment->standard_protocol ?? 'Follow the standard package directions or consult a care provider.' }}
                    </p>
                </div>

                <!-- B. About the Treatment & Mechanism of Action -->
                <div class="border-t border-slate-150 pt-6 dark:border-navy-600 space-y-4">
                    <div>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-primary dark:text-accent-light flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>What is it? / Description</span>
                        </h2>
                        <p class="mt-2 text-sm leading-relaxed text-slate-700 dark:text-navy-200">
                            {{ $treatment->description ?? 'No general description recorded yet.' }}
                        </p>
                    </div>

                    @if($treatment->mechanism_of_action)
                        <div class="pt-4 border-t border-slate-100 dark:border-navy-600 space-y-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-navy-200 flex items-center space-x-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-primary dark:text-accent-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span>How It Works in the Body</span>
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-600 dark:text-navy-300 leading-relaxed">
                                {{ $treatment->mechanism_of_action }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- C. Health Conditions It Helps With -->
                <div class="border-t border-slate-150 pt-6 dark:border-navy-600 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-150 pb-3 dark:border-navy-600">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-navy-100 flex items-center space-x-2">
                            <span class="size-2 rounded-full bg-primary dark:bg-accent-light"></span>
                            <span>Conditions Treated &amp; Cured ({{ $treatment->diseases->count() }})</span>
                        </h2>
                        <a href="{{ route('disease.index') }}" class="text-tiny font-semibold text-primary hover:underline dark:text-accent-light">
                            Browse Diseases Database &rarr;
                        </a>
                    </div>

                    <div class="divide-y divide-slate-150 dark:divide-navy-600">
                        @forelse($treatment->diseases as $disease)
                            <div class="py-3.5 first:pt-0 last:pb-0 space-y-2">
                                <div class="flex items-center justify-between gap-2">
                                    <a href="{{ route('disease.show', $disease->slug) }}" class="text-sm font-bold text-slate-800 hover:text-primary dark:text-navy-100 dark:hover:text-accent-light">
                                        {{ $disease->name }}
                                    </a>
                                    @if($disease->pivot->is_primary_cure)
                                        <span class="badge shrink-0 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 px-2.5 py-0.5 text-tiny font-bold uppercase">
                                            &check; Primary Cure
                                        </span>
                                    @else
                                        <span class="badge shrink-0 rounded bg-slate-100 text-slate-600 dark:bg-navy-600 dark:text-navy-200 px-2 py-0.5 text-tiny font-medium">
                                            Supportive Care
                                        </span>
                                    @endif
                                </div>

                                @if($disease->pivot->specific_instructions)
                                    <p class="text-xs text-slate-600 dark:text-navy-300">
                                        <strong class="font-semibold text-slate-700 dark:text-navy-200">Specific Dosage / Notes for this condition:</strong> {{ $disease->pivot->specific_instructions }}
                                    </p>
                                @endif

                                <div class="flex items-center justify-end pt-1">
                                    <a href="{{ route('disease.show', $disease->slug) }}" class="text-tiny font-semibold text-primary hover:underline dark:text-accent-light">
                                        View Condition Guide &rarr;
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 py-3 text-center">
                                This treatment is not currently attached to any diseases. You can attach it when creating or editing a disease.
                            </p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

        <!-- Right Column / Sidebar -->
        <div class="lg:col-span-4 space-y-6">

            <!-- 1. Safety, Side Effects & Warnings Panel (Single Card, No Child Cards) -->
            <div class="card rounded-2xl border border-slate-150 bg-white p-5 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-4">
                <div class="border-b border-slate-150 pb-2.5 dark:border-navy-600">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-navy-100 flex items-center space-x-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>Safety &amp; Precautions</span>
                    </h3>
                </div>

                <!-- Side Effects -->
                <div class="space-y-1">
                    <h4 class="text-tiny font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400 flex items-center space-x-1.5">
                        <span class="size-2 rounded-full bg-amber-500"></span>
                        <span>Possible Side Effects</span>
                    </h4>
                    <p class="text-xs text-slate-700 dark:text-navy-200 leading-relaxed">
                        {{ $treatment->side_effects ?? 'No common side effects noted for standard usage.' }}
                    </p>
                </div>

                <!-- Warnings -->
                @if($treatment->cautions_warnings)
                    <div class="pt-3 border-t border-slate-150 dark:border-navy-600 space-y-1">
                        <h4 class="text-tiny font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 flex items-center space-x-1.5">
                            <span class="size-2 rounded-full bg-rose-500"></span>
                            <span>Things to Watch Out For</span>
                        </h4>
                        <p class="text-xs text-rose-800 dark:text-rose-200 leading-relaxed">
                            {{ $treatment->cautions_warnings }}
                        </p>
                    </div>
                @endif

                <!-- Safe Administration Tip inside the Safety Card -->
                <div class="pt-3 border-t border-slate-150 dark:border-navy-600 space-y-1 text-tiny text-slate-500 dark:text-navy-300 leading-relaxed">
                    <div class="flex items-center space-x-1.5 font-bold text-slate-700 dark:text-navy-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Safe Usage Reminder</span>
                    </div>
                    <p>
                        Ensure correct dosage intervals, check product expiration dates, and do not combine prescription medicines without medical guidance.
                    </p>
                </div>
            </div>

            <!-- 2. Packaging & Reference Photos (Single Card, No Child Cards) -->
            <div class="card rounded-2xl border border-slate-150 bg-white p-5 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-3">
                <div class="flex items-center justify-between border-b border-slate-150 pb-2.5 dark:border-navy-600">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-navy-100 flex items-center space-x-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-primary dark:text-accent-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Packaging &amp; Photos ({{ $treatment->images->count() }})</span>
                    </h3>
                </div>

                <form action="{{ route('treatment.image.upload', $treatment->id) }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                    @csrf
                    <input type="file" name="image" accept="image/*" required
                        class="form-input w-full text-tiny file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-tiny file:font-semibold file:bg-primary/10 file:text-primary dark:file:bg-accent/20 dark:file:text-accent-light">
                    <button type="submit"
                        class="btn w-full rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-navy-600 dark:hover:bg-navy-500 py-1.5 text-tiny font-bold text-slate-700 dark:text-navy-100 transition-colors">
                        Upload Photo
                    </button>
                </form>

                @if($treatment->images->count() > 0)
                    <div class="grid grid-cols-2 gap-2 pt-1">
                        @foreach($treatment->images as $file)
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

        </div>

    </div>

</div>
@endsection
