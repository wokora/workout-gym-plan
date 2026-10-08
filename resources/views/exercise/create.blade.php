@extends('layouts.app')

@section('title', 'Create Exercise')

@section('content')
<div class="space-y-6">
    <div class="card rounded-xl border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <div class="flex items-center justify-between border-b border-slate-150 px-5 py-4 dark:border-navy-600">
            <span class="font-semibold text-xs uppercase tracking-wider text-slate-600 dark:text-navy-200">
                Create New Exercise
            </span>
            <a href="{{ route('exercise.index') }}" class="text-xs font-semibold text-primary hover:underline dark:text-accent-light">
                &larr; Back to Catalog
            </a>
        </div>

        <form action="{{ route('exercise.store') }}" method="post" class="p-5 sm:p-6 space-y-5">
            @csrf
            <div>
                <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-navy-300 mb-1.5">Exercise Name</label>
                <input type="text" name="name" value="{{ old('name', '') }}" id="name"
                    class="form-input w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2.5 text-sm text-slate-800 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent @error('name') border-error @enderror"
                    placeholder="e.g. Barbell Bench Press">
                @error('name')
                    <span class="mt-1 block text-tiny-plus text-error">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-navy-300 mb-1.5">Instruction Guide / Description</label>
                <textarea name="description" id="description" rows="4"
                    class="form-textarea w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2.5 text-sm text-slate-800 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent @error('description') border-error @enderror"
                    placeholder="Describe execution form, tips, and target zones...">{{ old('description', '') }}</textarea>
                @error('description')
                    <span class="mt-1 block text-tiny-plus text-error">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-navy-300 mb-2">Target Muscle Groups / Body Sections</label>
                <div class="grid grid-cols-2 gap-2 sm:grid-cols-4 md:grid-cols-6">
                    @foreach(\App\Models\Body\Body::get() as $body)
                        <div>
                            <input type="checkbox" value="{{ $body->id }}" name="body[{{ $body->id }}]" id="bodyCheck{{ $body->id }}"
                                class="peer hidden"
                                @if(is_array(old('body')) && in_array($body->id, old('body'))) checked @endif>
                            <label for="bodyCheck{{ $body->id }}"
                                class="block cursor-pointer select-none rounded-lg border border-slate-200 bg-slate-50 p-2.5 text-center text-xs font-semibold text-slate-600 transition-all hover:bg-slate-100 peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white dark:border-navy-600 dark:bg-navy-800 dark:text-navy-200 dark:peer-checked:border-accent dark:peer-checked:bg-accent dark:peer-checked:text-white">
                                {{ $body->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('body')
                    <span class="mt-2 block text-tiny-plus text-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-150 dark:border-navy-600">
                <a href="{{ route('exercise.index') }}" class="btn h-9 rounded-lg border border-slate-300 px-4 text-xs font-semibold text-slate-700 hover:bg-slate-100 dark:border-navy-500 dark:text-navy-200 dark:hover:bg-navy-600">
                    Cancel
                </a>
                <button type="submit" class="btn h-9 rounded-lg bg-primary px-5 text-xs font-semibold text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                    Save Exercise
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
