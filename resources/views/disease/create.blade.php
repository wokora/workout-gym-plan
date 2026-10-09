@extends('layouts.app')

@section('title', 'Document New Condition / Disease')

@php
    $initialTreatments = old('treatments', []);
    if (!empty($initialTreatments) && is_array($initialTreatments)) {
        $initialTreatments = array_values(array_map(function($t) {
            return [
                'uid' => uniqid(),
                'id' => (string) ($t['id'] ?? ''),
                'is_primary_cure' => !empty($t['is_primary_cure']),
                'instructions' => $t['instructions'] ?? '',
            ];
        }, $initialTreatments));
    } else {
        $initialTreatments = [];
    }
@endphp

@section('content')
<div class="space-y-6" x-data="{
    treatmentsList: @json($initialTreatments),
    addTreatment() {
        this.treatmentsList.push({ uid: Date.now() + Math.random(), id: '', is_primary_cure: false, instructions: '' });
    },
    removeTreatment(index) {
        this.treatmentsList.splice(index, 1);
    }
}">

    <!-- Header Banner -->
    <div class="card rounded-2xl border border-slate-150 bg-white p-6 shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <div class="flex flex-col justify-between space-y-4 sm:flex-row sm:items-center sm:space-y-0">
            <div class="space-y-1">
                <div class="flex items-center space-x-2 text-xs font-medium">
                    <a href="{{ route('disease.index') }}" class="text-slate-400 hover:text-primary dark:text-navy-300 dark:hover:text-accent-light transition-colors">
                        &larr; Diseases &amp; Cures
                    </a>
                    <span class="text-slate-300 dark:text-navy-500">/</span>
                    <span class="text-slate-600 dark:text-navy-200 font-semibold">New Entry</span>
                </div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-800 dark:text-navy-50 sm:text-3xl">
                    Document Condition or Disease
                </h1>
                <p class="text-xs text-slate-400 dark:text-navy-300">
                    Record symptoms, causes, prevention, and effective treatments or cures in simple language.
                </p>
            </div>
            <div class="flex items-center space-x-2 shrink-0">
                <a href="{{ route('disease.index') }}"
                    class="btn space-x-1.5 rounded-xl border border-slate-300 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-150 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 transition-colors">
                    Cancel
                </a>
            </div>
        </div>
    </div>

    <!-- Main Form -->
    <form method="POST" action="{{ route('disease.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

            <!-- Left Column: Main Content Fields (8 cols) -->
            <div class="lg:col-span-8 space-y-6">

                <!-- 1. Basic Information Card -->
                <div class="card rounded-2xl border border-slate-150 bg-white p-5 sm:p-6 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-150 pb-3 dark:border-navy-600">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-primary dark:text-accent-light flex items-center space-x-2">
                            <span class="flex size-5 items-center justify-center rounded-full bg-primary/10 text-primary dark:bg-accent/20 dark:text-accent-light text-[11px] font-bold">1</span>
                            <span>Basic Information</span>
                        </h2>
                        <span class="text-tiny text-slate-400">* Required field</span>
                    </div>

                    <!-- Name -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-navy-200 mb-1.5">
                            Condition Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            placeholder="e.g. Common Cold, Migraine, Acid Reflux, High Blood Pressure"
                            class="form-input w-full rounded-xl border border-slate-300 bg-transparent py-2.5 px-3.5 text-sm text-slate-800 placeholder:text-slate-400 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">
                        @error('name')
                            <p class="text-rose-500 text-tiny mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Overview -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-navy-200 mb-1.5">
                            What is it? (Overview)
                        </label>
                        <textarea name="overview" rows="3"
                            placeholder="Briefly describe what this condition is in plain, understandable words..."
                            class="form-textarea w-full rounded-xl border border-slate-300 bg-transparent p-3.5 text-xs text-slate-800 placeholder:text-slate-400 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('overview') }}</textarea>
                    </div>

                    <!-- Causes and Prevention -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-navy-200 mb-1.5 flex items-center space-x-1">
                                <span class="size-2 rounded-full bg-amber-500"></span>
                                <span>What Causes It? / Triggers</span>
                            </label>
                            <textarea name="causes" rows="3"
                                placeholder="e.g. Virus infection, lack of sleep, dehydration, stress, poor diet..."
                                class="form-textarea w-full rounded-xl border border-slate-300 bg-transparent p-3 text-xs text-slate-800 placeholder:text-slate-400 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('causes') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-navy-200 mb-1.5 flex items-center space-x-1">
                                <span class="size-2 rounded-full bg-emerald-500"></span>
                                <span>How to Prevent It?</span>
                            </label>
                            <textarea name="prevention" rows="3"
                                placeholder="e.g. Wash hands frequently, drink 2L water daily, get 8 hours sleep..."
                                class="form-textarea w-full rounded-xl border border-slate-300 bg-transparent p-3 text-xs text-slate-800 placeholder:text-slate-400 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('prevention') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- 2. Symptoms Checklist Card -->
                <div class="card rounded-2xl border border-slate-150 bg-white p-5 sm:p-6 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-150 pb-3 dark:border-navy-600">
                        <div class="space-y-0.5">
                            <h2 class="text-xs font-bold uppercase tracking-wider text-primary dark:text-accent-light flex items-center space-x-2">
                                <span class="flex size-5 items-center justify-center rounded-full bg-primary/10 text-primary dark:bg-accent/20 dark:text-accent-light text-[11px] font-bold">2</span>
                                <span>Common Signs &amp; Symptoms</span>
                            </h2>
                            <p class="text-tiny text-slate-400">Select what people feel or look out for</p>
                        </div>
                        <a href="{{ route('symptom.create') }}" target="_blank"
                            class="badge rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 px-2.5 py-1 text-tiny font-bold hover:bg-amber-500/20 transition-colors flex items-center space-x-1">
                            <span>+</span>
                            <span>New Symptom</span>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2 lg:grid-cols-3 max-h-60 overflow-y-auto p-3.5 rounded-xl border border-slate-200 bg-slate-50/70 dark:border-navy-600 dark:bg-navy-800/50">
                        @forelse($symptoms as $symptom)
                            <label class="flex items-center space-x-2.5 text-xs text-slate-700 dark:text-navy-200 cursor-pointer hover:bg-white dark:hover:bg-navy-700 p-2 rounded-lg border border-transparent hover:border-slate-200 dark:hover:border-navy-600 transition-all">
                                <input type="checkbox" name="symptoms[]" value="{{ $symptom->id }}"
                                    {{ in_array($symptom->id, old('symptoms', [])) ? 'checked' : '' }}
                                    class="form-checkbox is-basic size-4 rounded border-slate-300 text-primary dark:border-navy-450 dark:checked:bg-accent">
                                <span class="font-medium truncate">{{ $symptom->name }}</span>
                            </label>
                        @empty
                            <p class="col-span-full text-xs text-slate-400 py-4 text-center">
                                No symptoms available in directory. <a href="{{ route('symptom.create') }}" target="_blank" class="text-primary underline">Add one now</a>.
                            </p>
                        @endforelse
                    </div>
                </div>

                <!-- 3. Cures & Treatments Card -->
                <div class="card rounded-2xl border border-slate-150 bg-white p-5 sm:p-6 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-150 pb-3 dark:border-navy-600">
                        <div class="space-y-0.5">
                            <h2 class="text-xs font-bold uppercase tracking-wider text-primary dark:text-accent-light flex items-center space-x-2">
                                <span class="flex size-5 items-center justify-center rounded-full bg-primary/10 text-primary dark:bg-accent/20 dark:text-accent-light text-[11px] font-bold">3</span>
                                <span>Cures, Treatments &amp; Remedies</span>
                            </h2>
                            <p class="text-tiny text-slate-400">Attach medicines, remedies, or lifestyle cures</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('treatment.create') }}" target="_blank"
                                class="badge rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-navy-600 dark:text-navy-100 dark:hover:bg-navy-500 px-2.5 py-1 text-tiny font-bold transition-colors">
                                + Create New
                            </a>
                            <button type="button" @click="addTreatment()"
                                class="btn space-x-1 rounded-xl bg-primary px-3 py-1.5 text-xs font-bold text-white shadow-soft hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus transition-all">
                                <span>+ Attach Remedy</span>
                            </button>
                        </div>
                    </div>

                    <!-- Dynamic Treatments Repeater -->
                    <div class="space-y-3">
                        <template x-for="(item, index) in treatmentsList" :key="item.uid || index">
                            <div class="rounded-xl border p-4 transition-all space-y-3"
                                :class="item.is_primary_cure ? 'border-emerald-500/40 bg-emerald-50/20 dark:border-emerald-500/30 dark:bg-emerald-950/10' : 'border-slate-200 bg-slate-50/70 dark:border-navy-600 dark:bg-navy-800/50'">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold uppercase tracking-wider"
                                        :class="item.is_primary_cure ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-700 dark:text-navy-200'"
                                        x-text="'Remedy #' + (index + 1)"></span>
                                    <button type="button" @click="removeTreatment(index)" class="text-tiny text-rose-500 hover:text-rose-700 font-bold transition-colors">
                                        &times; Remove
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-12 items-center">
                                    <div class="sm:col-span-7">
                                        <label class="block text-tiny uppercase tracking-wider font-bold text-slate-500 mb-1">
                                            Select Remedy / Cure <span class="text-rose-500">*</span>
                                        </label>
                                        <select :name="'treatments[' + index + '][id]'" x-model="item.id" required
                                            class="form-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs text-slate-700 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:text-navy-100">
                                            <option value="">-- Choose Remedy from Catalog --</option>
                                            @foreach($treatments as $treat)
                                                <option value="{{ $treat->id }}">{{ $treat->name }} ({{ $treat->treatment_type ? $treat->treatment_type->name : 'General' }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="sm:col-span-5 flex items-center pt-3 sm:pt-4">
                                        <label class="flex items-center space-x-2 text-xs cursor-pointer select-none">
                                            <input type="checkbox" :name="'treatments[' + index + '][is_primary_cure]'" value="1" x-model="item.is_primary_cure"
                                                class="form-checkbox is-basic size-4.5 rounded border-slate-300 text-emerald-500 dark:border-navy-450">
                                            <span class="font-bold text-emerald-600 dark:text-emerald-400">Mark as Primary Cure</span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-tiny uppercase tracking-wider font-bold text-slate-500 mb-1">
                                        Specific Instructions / How to Take
                                    </label>
                                    <input type="text" :name="'treatments[' + index + '][instructions]'" x-model="item.instructions"
                                        placeholder="e.g. Take 1 tablet after meals twice daily, gargle warm salt water morning and night..."
                                        class="form-input w-full rounded-lg border border-slate-300 bg-white py-2 px-3 text-xs text-slate-800 dark:border-navy-450 dark:bg-navy-700 dark:text-navy-100">
                                </div>
                            </div>
                        </template>

                        <div x-show="treatmentsList.length === 0" class="text-center py-6 rounded-xl border border-dashed border-slate-200 dark:border-navy-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-8 mx-auto text-slate-300 dark:text-navy-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                            <p class="mt-1 text-xs text-slate-400 dark:text-navy-300">No treatments attached yet.</p>
                            <button type="button" @click="addTreatment()" class="mt-2 text-xs text-primary dark:text-accent-light font-bold hover:underline">
                                + Click here to attach a cure or remedy
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Actions, Image Upload & Tips (4 cols) -->
            <div class="lg:col-span-4 space-y-6">

                <!-- 1. Save Action Card -->
                <div class="card rounded-2xl border border-slate-150 bg-white p-5 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-navy-100 border-b border-slate-150 pb-2.5 dark:border-navy-600">
                        Action &amp; Save
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-navy-300 leading-relaxed">
                        Ready to document this condition? It will appear in the directory and link to associated remedies.
                    </p>
                    <div class="space-y-2">
                        <button type="submit"
                            class="btn w-full rounded-xl bg-primary py-3 text-xs font-bold text-white shadow-soft hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus transition-all flex items-center justify-center space-x-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Save Condition</span>
                        </button>
                        <a href="{{ route('disease.index') }}"
                            class="btn w-full rounded-xl border border-slate-300 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-150 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 transition-colors block text-center">
                            Cancel
                        </a>
                    </div>
                </div>

                <!-- 2. Photo / Image Upload (Positioned Below Save on Right Side) -->
                <div class="card rounded-2xl border border-slate-150 bg-white p-5 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-3">
                    <div class="border-b border-slate-150 pb-2.5 dark:border-navy-600">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-navy-100 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-primary dark:text-accent-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Photo or Medical Document</span>
                        </h3>
                    </div>
                    <p class="text-tiny text-slate-400 dark:text-navy-300">
                        Upload an illustration, reference chart, or document photo for this condition.
                    </p>
                    <input type="file" name="image" accept="image/*"
                        class="form-input w-full text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-tiny file:font-semibold file:bg-primary/10 file:text-primary dark:file:bg-accent/20 dark:file:text-accent-light hover:file:bg-primary/20">
                </div>

                <!-- 3. Documentation Tips Card -->
                <div class="card rounded-2xl border border-slate-150 bg-white p-5 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-navy-100 flex items-center space-x-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                        <span>Documentation Tips</span>
                    </h3>
                    <ul class="space-y-2 text-tiny text-slate-500 dark:text-navy-300 leading-relaxed">
                        <li class="flex items-start space-x-2">
                            <span class="text-emerald-500 font-bold">&check;</span>
                            <span><strong>Common Names:</strong> Use everyday terms (e.g., Flu instead of Influenza).</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <span class="text-emerald-500 font-bold">&check;</span>
                            <span><strong>Primary Cure:</strong> Mark remedies that directly resolve the condition.</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <span class="text-emerald-500 font-bold">&check;</span>
                            <span><strong>Dosage Notes:</strong> Specify how often and in what amount to take each remedy.</span>
                        </li>
                    </ul>
                </div>

            </div>

        </div>
    </form>

</div>
@endsection
