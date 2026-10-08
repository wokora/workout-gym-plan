@extends('layouts.app')

@section('title', 'Add Meal Plan Day')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl">
                Add Meal Plan Day
            </h2>
            <p class="text-xs text-slate-500 dark:text-navy-300">
                Create a new day entry for <span class="font-semibold text-slate-700 dark:text-navy-100">{{ $meal->name }}</span>
            </p>
        </div>
        <a href="{{ route('meal.show', $meal->id) }}"
            class="text-xs font-semibold text-primary hover:text-primary-focus dark:text-accent-light dark:hover:text-accent">
            &larr; Back to Plan
        </a>
    </div>

    <!-- Form Card -->
    <div class="card rounded-xl border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <form action="{{ route('meal.day.store', $meal->id) }}" method="POST">
            @csrf
            <div class="p-5 sm:p-6 space-y-4">
                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-navy-200 mb-1.5">
                        Day Description <span class="text-error">*</span>
                    </label>
                    <input type="text"
                        name="name"
                        id="name"
                        value="{{ old('name') }}"
                        placeholder="e.g. Day 1, Monday, High Carb Day"
                        class="form-input w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-slate-800 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent @error('name') border-error @enderror"
                        required
                        autofocus>
                    @error('name')
                        <p class="mt-1 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 border-t border-slate-150 px-5 py-4 dark:border-navy-600">
                <a href="{{ route('meal.show', $meal->id) }}"
                    class="btn h-9 rounded-lg border border-slate-300 px-4 text-xs font-semibold text-slate-700 hover:bg-slate-100 dark:border-navy-500 dark:text-navy-200 dark:hover:bg-navy-600">
                    Cancel
                </a>
                <button type="submit"
                    class="btn h-9 rounded-lg bg-primary px-5 text-xs font-semibold text-white shadow-soft hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                    Save Day
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
