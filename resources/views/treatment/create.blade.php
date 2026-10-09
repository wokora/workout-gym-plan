@extends('layouts.app')

@section('title', 'Add New Treatment / Remedy')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="card rounded-2xl border border-slate-150 bg-white p-6 shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <div class="flex flex-col justify-between space-y-4 sm:flex-row sm:items-center sm:space-y-0">
            <div class="space-y-1">
                <div class="flex items-center space-x-2 text-xs font-medium">
                    <a href="{{ route('treatment.index') }}" class="text-slate-400 hover:text-primary dark:text-navy-300 dark:hover:text-accent-light transition-colors">
                        &larr; Treatments &amp; Remedies
                    </a>
                    <span class="text-slate-300 dark:text-navy-500">/</span>
                    <span class="text-slate-600 dark:text-navy-200 font-semibold">New Entry</span>
                </div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-800 dark:text-navy-50 sm:text-3xl">
                    Add Treatment or Remedy
                </h1>
                <p class="text-xs text-slate-400 dark:text-navy-300">
                    Record a medicine, herbal remedy, dietary protocol, or cure for health conditions.
                </p>
            </div>
            <div class="flex items-center space-x-2 shrink-0">
                <a href="{{ route('treatment.index') }}"
                    class="btn space-x-1.5 rounded-xl border border-slate-300 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-150 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 transition-colors">
                    Cancel
                </a>
            </div>
        </div>
    </div>

    <!-- Main Form -->
    <form method="POST" action="{{ route('treatment.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

            <!-- Left Column: Main Content Fields (Single Master Card, No Child Cards) (8 cols) -->
            <div class="lg:col-span-8">
                <div class="card rounded-2xl border border-slate-150 bg-white p-6 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-6">

                    <!-- Section 1: General Information -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-150 pb-3 dark:border-navy-600">
                            <h2 class="text-xs font-bold uppercase tracking-wider text-primary dark:text-accent-light flex items-center space-x-2">
                                <span class="flex size-5 items-center justify-center rounded-full bg-primary/10 text-primary dark:bg-accent/20 dark:text-accent-light text-[11px] font-bold">1</span>
                                <span>General Information</span>
                            </h2>
                            <span class="text-tiny text-slate-400">* Required fields</span>
                        </div>

                        <!-- Name and Type -->
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-navy-200 mb-1.5">
                                    Remedy / Treatment Name <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    placeholder="e.g. Paracetamol, Honey & Ginger Tea, Saline Rinse"
                                    class="form-input w-full rounded-xl border border-slate-300 bg-transparent py-2.5 px-3.5 text-sm text-slate-800 placeholder:text-slate-400 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">
                                @error('name')
                                    <p class="text-rose-500 text-tiny mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-navy-200 mb-1.5">
                                    Category / Treatment Type <span class="text-rose-500">*</span>
                                </label>
                                <select name="treatment_type_id" required
                                    class="form-select w-full rounded-xl border border-slate-300 bg-transparent py-2.5 px-3 text-xs text-slate-700 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">
                                    <option value="">Select Category...</option>
                                    @foreach($treatmentTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('treatment_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('treatment_type_id')
                                    <p class="text-rose-500 text-tiny mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-navy-200 mb-1.5">
                                What is it? / Description
                            </label>
                            <textarea name="description" rows="3"
                                placeholder="Briefly explain what this treatment is and what it does in simple terms..."
                                class="form-textarea w-full rounded-xl border border-slate-300 bg-transparent p-3.5 text-xs text-slate-800 placeholder:text-slate-400 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <!-- Section 2: Administration & How It Works (Direct inputs, no child cards) -->
                    <div class="border-t border-slate-150 pt-6 dark:border-navy-600 space-y-4">
                        <div class="border-b border-slate-150 pb-3 dark:border-navy-600">
                            <h2 class="text-xs font-bold uppercase tracking-wider text-primary dark:text-accent-light flex items-center space-x-2">
                                <span class="flex size-5 items-center justify-center rounded-full bg-primary/10 text-primary dark:bg-accent/20 dark:text-accent-light text-[11px] font-bold">2</span>
                                <span>Administration &amp; How It Works</span>
                            </h2>
                        </div>

                        <!-- Standard Protocol (Direct input, no child card box) -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-navy-200 mb-1.5 flex items-center space-x-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <span>How to Take / Standard Protocol</span>
                            </label>
                            <textarea name="standard_protocol" rows="3"
                                placeholder="e.g. Dissolve 1/2 tsp salt in warm water, gargle 3 times daily for 3 days..."
                                class="form-textarea w-full rounded-xl border border-slate-300 bg-transparent p-3.5 text-xs text-slate-800 placeholder:text-slate-400 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('standard_protocol') }}</textarea>
                        </div>

                        <!-- How it works (Optional) -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-navy-200 mb-1.5">
                                How It Works in the Body (Optional)
                            </label>
                            <textarea name="mechanism_of_action" rows="2"
                                placeholder="e.g. Cleans bacteria from the throat, soothes irritated tissue, reduces swelling..."
                                class="form-textarea w-full rounded-xl border border-slate-300 bg-transparent p-3 text-xs text-slate-800 placeholder:text-slate-400 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('mechanism_of_action') }}</textarea>
                        </div>
                    </div>

                    <!-- Section 3: Safety & Warnings (Direct grid inputs, no child cards) -->
                    <div class="border-t border-slate-150 pt-6 dark:border-navy-600 space-y-4">
                        <div class="border-b border-slate-150 pb-3 dark:border-navy-600">
                            <h2 class="text-xs font-bold uppercase tracking-wider text-primary dark:text-accent-light flex items-center space-x-2">
                                <span class="flex size-5 items-center justify-center rounded-full bg-primary/10 text-primary dark:bg-accent/20 dark:text-accent-light text-[11px] font-bold">3</span>
                                <span>Safety &amp; Warnings</span>
                            </h2>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400 mb-1.5 flex items-center space-x-1">
                                    <span class="size-2 rounded-full bg-amber-500"></span>
                                    <span>Possible Side Effects</span>
                                </label>
                                <textarea name="side_effects" rows="3"
                                    placeholder="e.g. Mild stomach upset, dry mouth, slight drowsiness..."
                                    class="form-textarea w-full rounded-xl border border-slate-300 bg-transparent p-3 text-xs text-slate-800 placeholder:text-slate-400 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('side_effects') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 mb-1.5 flex items-center space-x-1">
                                    <span class="size-2 rounded-full bg-rose-500"></span>
                                    <span>Things to Watch Out For</span>
                                </label>
                                <textarea name="cautions_warnings" rows="3"
                                    placeholder="e.g. Do not swallow, avoid if you have severe hypertension or allergies..."
                                    class="form-textarea w-full rounded-xl border border-slate-300 bg-transparent p-3 text-xs text-slate-800 placeholder:text-slate-400 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('cautions_warnings') }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right Column: Actions, Image Upload & Guidance (4 cols) -->
            <div class="lg:col-span-4 space-y-6">

                <!-- 1. Save Action Card -->
                <div class="card rounded-2xl border border-slate-150 bg-white p-5 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-navy-100 border-b border-slate-150 pb-2.5 dark:border-navy-600">
                        Action &amp; Save
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-navy-300 leading-relaxed">
                        Save this treatment to the catalog. You can attach it to conditions at any time.
                    </p>
                    <div class="space-y-2">
                        <button type="submit"
                            class="btn w-full rounded-xl bg-primary py-3 text-xs font-bold text-white shadow-soft hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus transition-all flex items-center justify-center space-x-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Save Treatment</span>
                        </button>
                        <a href="{{ route('treatment.index') }}"
                            class="btn w-full rounded-xl border border-slate-300 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-150 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 transition-colors block text-center">
                            Cancel
                        </a>
                    </div>
                </div>

                <!-- 2. Photo / Packaging Image Upload (Positioned Below Save) -->
                <div class="card rounded-2xl border border-slate-150 bg-white p-5 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-3">
                    <div class="border-b border-slate-150 pb-2.5 dark:border-navy-600">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-navy-100 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-primary dark:text-accent-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Photo / Product Packaging</span>
                        </h3>
                    </div>
                    <p class="text-tiny text-slate-400 dark:text-navy-300">
                        Upload an image of the medicine box, herbal ingredient, or remedy guide.
                    </p>
                    <input type="file" name="image" accept="image/*"
                        class="form-input w-full text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-tiny file:font-semibold file:bg-primary/10 file:text-primary dark:file:bg-accent/20 dark:file:text-accent-light hover:file:bg-primary/20">
                </div>

                <!-- 3. Guidelines Card -->
                <div class="card rounded-2xl border border-slate-150 bg-white p-5 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-navy-100 flex items-center space-x-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Guidelines for Remedies</span>
                    </h3>
                    <ul class="space-y-2 text-tiny text-slate-500 dark:text-navy-300 leading-relaxed">
                        <li class="flex items-start space-x-2">
                            <span class="text-emerald-500 font-bold">&check;</span>
                            <span><strong>Clear Protocol:</strong> Write steps someone can easily follow at home.</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <span class="text-emerald-500 font-bold">&check;</span>
                            <span><strong>Contraindications:</strong> Note who should NOT use this remedy.</span>
                        </li>
                    </ul>
                </div>

            </div>

        </div>
    </form>

</div>
@endsection
