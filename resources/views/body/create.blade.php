@extends('layouts.app')

@section('title', 'Create Body Section')

@section('content')
<div class="space-y-6">
    <div class="card rounded-xl border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <div class="flex items-center justify-between border-b border-slate-150 px-5 py-4 dark:border-navy-600">
            <span class="font-semibold text-xs uppercase tracking-wider text-slate-600 dark:text-navy-200">
                Create New Body Section
            </span>
            <a href="{{ route('body.index') }}" class="text-xs font-semibold text-primary hover:underline dark:text-accent-light">
                &larr; Back to Sections
            </a>
        </div>

        <form action="{{ route('body.store') }}" method="post" class="p-5 sm:p-6 space-y-5">
            @csrf
            <div>
                <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-navy-300 mb-1.5">Body Section Name</label>
                <input type="text" name="name" value="{{ old('name', '') }}" id="name"
                    class="form-input w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2.5 text-sm text-slate-800 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent @error('name') border-error @enderror"
                    placeholder="e.g. Chest, Quads, Deltoids">
                @error('name')
                    <span class="mt-1 block text-tiny-plus text-error">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-navy-300 mb-1.5">Description</label>
                <textarea name="description" id="description" rows="4"
                    class="form-textarea w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2.5 text-sm text-slate-800 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent @error('description') border-error @enderror"
                    placeholder="Describe the muscles included in this section...">{{ old('description', '') }}</textarea>
                @error('description')
                    <span class="mt-1 block text-tiny-plus text-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-150 dark:border-navy-600">
                <a href="{{ route('body.index') }}" class="btn h-9 rounded-lg border border-slate-300 px-4 text-xs font-semibold text-slate-700 hover:bg-slate-100 dark:border-navy-500 dark:text-navy-200 dark:hover:bg-navy-600">
                    Cancel
                </a>
                <button type="submit" class="btn h-9 rounded-lg bg-primary px-5 text-xs font-semibold text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                    Save Section
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
