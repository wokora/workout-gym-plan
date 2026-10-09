@extends('layouts.app')

@section('title', 'Add New Symptom')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="card rounded-2xl border border-slate-150 bg-white p-6 shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <div class="flex flex-col justify-between space-y-4 sm:flex-row sm:items-center sm:space-y-0">
            <div class="space-y-1">
                <div class="flex items-center space-x-2 text-xs font-medium">
                    <a href="{{ route('symptom.index') }}" class="text-slate-400 hover:text-primary dark:text-navy-300 dark:hover:text-accent-light transition-colors">
                        &larr; Symptoms List
                    </a>
                    <span class="text-slate-300 dark:text-navy-500">/</span>
                    <span class="text-slate-600 dark:text-navy-200 font-semibold">New Entry</span>
                </div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-800 dark:text-navy-50 sm:text-3xl">
                    Add New Symptom
                </h1>
                <p class="text-xs text-slate-400 dark:text-navy-300">
                    Record a bodily sensation, sign, or symptom that connects to conditions.
                </p>
            </div>
            <div class="flex items-center space-x-2 shrink-0">
                <a href="{{ route('symptom.index') }}"
                    class="btn space-x-1.5 rounded-xl border border-slate-300 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-150 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 transition-colors">
                    Cancel
                </a>
            </div>
        </div>
    </div>

    <!-- Main Form -->
    <form method="POST" action="{{ route('symptom.store') }}">
        @csrf

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

            <!-- Left / Main Form Column (8 cols) -->
            <div class="lg:col-span-8 space-y-6">

                <!-- 1. Symptom Identity Card -->
                <div class="card rounded-2xl border border-slate-150 bg-white p-5 sm:p-6 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-150 pb-3 dark:border-navy-600">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-primary dark:text-accent-light flex items-center space-x-2">
                            <span class="flex size-5 items-center justify-center rounded-full bg-primary/10 text-primary dark:bg-accent/20 dark:text-accent-light text-[11px] font-bold">1</span>
                            <span>Symptom Identification</span>
                        </h2>
                        <span class="text-tiny text-slate-400">* Required field</span>
                    </div>

                    <!-- Name -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-navy-200 mb-1.5">
                            Symptom Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            placeholder="e.g. Dry Cough, Persistent Headache, Joint Stiffness, Acid Reflux"
                            class="form-input w-full rounded-xl border border-slate-300 bg-transparent py-2.5 px-3.5 text-sm text-slate-800 placeholder:text-slate-400 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">
                        @error('name')
                            <p class="text-rose-500 text-tiny mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Body Area / System -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-navy-200 mb-1.5">
                            Body Area / System
                        </label>
                        <input list="bodySystemsList" name="body_system" value="{{ old('body_system') }}"
                            placeholder="e.g. Respiratory, Stomach, Head, General..."
                            class="form-input w-full rounded-xl border border-slate-300 bg-transparent py-2.5 px-3.5 text-xs text-slate-800 placeholder:text-slate-400 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">
                        <datalist id="bodySystemsList">
                            @foreach($bodySystems as $system)
                                <option value="{{ $system }}">
                            @endforeach
                            <option value="General">
                            <option value="Head & Neck">
                            <option value="Chest & Lungs">
                            <option value="Heart & Circulation">
                            <option value="Stomach & Digestion">
                            <option value="Muscles & Joints">
                            <option value="Skin">
                        </datalist>
                        @error('body_system')
                            <p class="text-rose-500 text-tiny mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- 2. Sensation & Description Card -->
                <div class="card rounded-2xl border border-slate-150 bg-white p-5 sm:p-6 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-4">
                    <div class="border-b border-slate-150 pb-3 dark:border-navy-600">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-primary dark:text-accent-light flex items-center space-x-2">
                            <span class="flex size-5 items-center justify-center rounded-full bg-primary/10 text-primary dark:bg-accent/20 dark:text-accent-light text-[11px] font-bold">2</span>
                            <span>Description &amp; Sensations</span>
                        </h2>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-navy-200 mb-1.5">
                            What does it feel like? / Triggers &amp; Timing
                        </label>
                        <textarea name="description" rows="4"
                            placeholder="Describe how this symptom feels (e.g. sharp, burning, dull ache), when it occurs (e.g. after meals, morning), or what triggers it..."
                            class="form-textarea w-full rounded-xl border border-slate-300 bg-transparent p-3.5 text-xs text-slate-800 placeholder:text-slate-400 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-rose-500 text-tiny mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>

            <!-- Right Column / Sidebar Form Controls (4 cols) -->
            <div class="lg:col-span-4 space-y-6">

                <!-- Save Action Card -->
                <div class="card rounded-2xl border border-slate-150 bg-white p-5 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-4 sticky top-20">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-navy-100 border-b border-slate-150 pb-2.5 dark:border-navy-600">
                        Action &amp; Save
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-navy-300 leading-relaxed">
                        Add this symptom to the catalog. You can select it when documenting any condition.
                    </p>
                    <div class="space-y-2">
                        <button type="submit"
                            class="btn w-full rounded-xl bg-primary py-3 text-xs font-bold text-white shadow-soft hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus transition-all flex items-center justify-center space-x-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Save Symptom</span>
                        </button>
                        <a href="{{ route('symptom.index') }}"
                            class="btn w-full rounded-xl border border-slate-300 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-150 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 transition-colors block text-center">
                            Cancel
                        </a>
                    </div>
                </div>

                <!-- Guidance Tips Card -->
                <div class="card rounded-2xl border border-slate-150 bg-white p-5 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-navy-100 flex items-center space-x-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                        <span>Symptom Entry Tips</span>
                    </h3>
                    <ul class="space-y-2 text-tiny text-slate-500 dark:text-navy-300 leading-relaxed">
                        <li class="flex items-start space-x-2">
                            <span class="text-emerald-500 font-bold">&check;</span>
                            <span><strong>Sensations:</strong> Describe if it is sharp, aching, burning, or throbbing.</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <span class="text-emerald-500 font-bold">&check;</span>
                            <span><strong>Timing &amp; Triggers:</strong> Note when it occurs (e.g. morning, after meals, constant).</span>
                        </li>
                    </ul>
                </div>

            </div>

        </div>
    </form>

</div>
@endsection
