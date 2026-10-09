@extends('layouts.app')

@section('title', 'Edit Treatment - ' . $treatment->name)

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
                    <a href="{{ route('treatment.show', $treatment->slug) }}" class="text-slate-500 dark:text-navy-300 hover:underline">
                        {{ $treatment->name }}
                    </a>
                    <span class="text-slate-300 dark:text-navy-500">/</span>
                    <span class="text-slate-600 dark:text-navy-200 font-semibold">Edit</span>
                </div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-800 dark:text-navy-50 sm:text-3xl">
                    Edit Treatment: {{ $treatment->name }}
                </h1>
                <p class="text-xs text-slate-400 dark:text-navy-300">
                    Update instructions, category, protocol, or safety cautions.
                </p>
            </div>
            <div class="flex items-center space-x-2 shrink-0">
                <a href="{{ route('treatment.show', $treatment->slug) }}"
                    class="btn space-x-1.5 rounded-xl border border-slate-300 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-150 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 transition-colors">
                    &larr; Back to Details
                </a>
                <form action="{{ route('treatment.destroy', $treatment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this treatment?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn rounded-xl bg-rose-500/10 text-rose-500 hover:bg-rose-500/20 px-3.5 py-2.5 text-xs font-semibold transition-colors">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Form -->
    <form method="POST" action="{{ route('treatment.update', $treatment->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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
                                <input type="text" name="name" value="{{ old('name', $treatment->name) }}" required
                                    class="form-input w-full rounded-xl border border-slate-300 bg-transparent py-2.5 px-3.5 text-sm text-slate-800 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">
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
                                        <option value="{{ $type->id }}" {{ old('treatment_type_id', $treatment->treatment_type_id) == $type->id ? 'selected' : '' }}>
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
                                class="form-textarea w-full rounded-xl border border-slate-300 bg-transparent p-3.5 text-xs text-slate-800 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('description', $treatment->description) }}</textarea>
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
                                class="form-textarea w-full rounded-xl border border-slate-300 bg-transparent p-3.5 text-xs text-slate-800 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('standard_protocol', $treatment->standard_protocol) }}</textarea>
                        </div>

                        <!-- How it works (Optional) -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-navy-200 mb-1.5">
                                How It Works in the Body (Optional)
                            </label>
                            <textarea name="mechanism_of_action" rows="2"
                                class="form-textarea w-full rounded-xl border border-slate-300 bg-transparent p-3 text-xs text-slate-800 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('mechanism_of_action', $treatment->mechanism_of_action) }}</textarea>
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
                                    class="form-textarea w-full rounded-xl border border-slate-300 bg-transparent p-3 text-xs text-slate-800 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('side_effects', $treatment->side_effects) }}</textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 mb-1.5 flex items-center space-x-1">
                                    <span class="size-2 rounded-full bg-rose-500"></span>
                                    <span>Things to Watch Out For</span>
                                </label>
                                <textarea name="cautions_warnings" rows="3"
                                    class="form-textarea w-full rounded-xl border border-slate-300 bg-transparent p-3 text-xs text-slate-800 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('cautions_warnings', $treatment->cautions_warnings) }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right Column: Actions, Image Upload & Snapshot (4 cols) -->
            <div class="lg:col-span-4 space-y-6">

                <!-- 1. Update / Save Action Card -->
                <div class="card rounded-2xl border border-slate-150 bg-white p-5 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-navy-100 border-b border-slate-150 pb-2.5 dark:border-navy-600">
                        Update &amp; Save
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-navy-300 leading-relaxed">
                        Save changes made to this treatment's description and guidance.
                    </p>
                    <div class="space-y-2">
                        <button type="submit"
                            class="btn w-full rounded-xl bg-primary py-3 text-xs font-bold text-white shadow-soft hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus transition-all flex items-center justify-center space-x-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Save Changes</span>
                        </button>
                        <a href="{{ route('treatment.show', $treatment->slug) }}"
                            class="btn w-full rounded-xl border border-slate-300 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-150 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 transition-colors block text-center">
                            Cancel
                        </a>
                    </div>
                </div>

                <!-- 2. Photo / Product Packaging Upload (Positioned Below Save) -->
                <div class="card rounded-2xl border border-slate-150 bg-white p-5 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-3">
                    <div class="border-b border-slate-150 pb-2.5 dark:border-navy-600">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-navy-100 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-primary dark:text-accent-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Product Photo / Image</span>
                        </h3>
                    </div>

                    <!-- Current Image Preview if available -->
                    @if($treatment->images->count() > 0)
                        <div class="rounded-xl overflow-hidden border border-slate-150 dark:border-navy-600 aspect-video bg-slate-50 dark:bg-navy-800 relative">
                            <img src="{{ $treatment->images->first()->get_url() }}" alt="{{ $treatment->name }}" class="w-full h-full object-cover">
                            <span class="absolute bottom-1 right-1 bg-black/60 text-white text-[10px] px-1.5 py-0.5 rounded">
                                Current Photo
                            </span>
                        </div>
                    @endif

                    <p class="text-tiny text-slate-400 dark:text-navy-300">
                        {{ $treatment->images->count() > 0 ? 'Upload a new photo to replace or add to the current image:' : 'Upload an image of the medicine, remedy, or packaging:' }}
                    </p>
                    <input type="file" name="image" accept="image/*"
                        class="form-input w-full text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-tiny file:font-semibold file:bg-primary/10 file:text-primary dark:file:bg-accent/20 dark:file:text-accent-light hover:file:bg-primary/20">
                </div>

                <!-- 3. Record Snapshot Card -->
                <div class="card rounded-2xl border border-slate-150 bg-white p-5 shadow-soft dark:border-navy-600 dark:bg-navy-700 space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-navy-100 border-b border-slate-150 pb-2.5 dark:border-navy-600">
                        Record Snapshot
                    </h3>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Created:</span>
                            <span class="font-medium text-slate-700 dark:text-navy-200">{{ $treatment->created_at ? $treatment->created_at->format('M d, Y') : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Last Updated:</span>
                            <span class="font-medium text-slate-700 dark:text-navy-200">{{ $treatment->updated_at ? $treatment->updated_at->diffForHumans() : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Linked Conditions:</span>
                            <span class="font-medium text-slate-700 dark:text-navy-200">{{ $treatment->diseases->count() }}</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </form>

</div>
@endsection
