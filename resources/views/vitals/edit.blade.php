@extends('layouts.app')

@section('title', 'Edit Vital')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl">
                Edit {{ $vital->vital ? $vital->vital->name : 'Vital' }}
            </h2>
            <p class="text-xs text-slate-500 dark:text-navy-300">
                Update the recorded vital metric value or measurement date.
            </p>
        </div>
        <a href="{{ route('vital.show', $vital->id) }}"
            class="text-xs font-semibold text-primary hover:text-primary-focus dark:text-accent-light dark:hover:text-accent">
            &larr; Back to Detail
        </a>
    </div>

    <!-- Main Card -->
    <div class="card rounded-xl border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <form action="{{ route('vital.update', [$vital->id]) }}" method="POST">
            @method('PATCH')
            @csrf
            <div class="p-5 sm:p-6 space-y-5">
                <!-- Date -->
                <div>
                    <label for="date" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-navy-200 mb-1.5">
                        Date Measured <span class="text-error">*</span>
                    </label>
                    <input type="date"
                        name="date"
                        id="date"
                        value="{{ old('date', $vital->date_measured ? $vital->date_measured->format('Y-m-d') : '') }}"
                        class="form-input max-w-xs rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-xs sm:text-sm text-slate-800 placeholder:text-slate-400 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent @error('date') border-error @enderror"
                        required>
                    @error('date')
                        <p class="mt-1 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Value -->
                @php
                    $htmlType = $vital->vital && $vital->vital->inputType && in_array($vital->vital->inputType->code, ['decimal', 'integer']) ? 'number' : 'text';
                    $step = $vital->vital && $vital->vital->inputType ? ($vital->vital->inputType->step ?? 'any') : 'any';
                    $unitSymbol = $vital->vital && $vital->vital->unit ? $vital->vital->unit->symbol : '';
                @endphp
                <div>
                    <label for="value" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-navy-200 mb-1.5">
                        {{ $vital->vital ? $vital->vital->name : 'Value' }} <span class="text-error">*</span>
                    </label>
                    <div class="flex rounded-lg shadow-sm">
                        <input type="{{ $htmlType }}"
                            step="{{ $step }}"
                            name="value"
                            id="value"
                            value="{{ old('value', $vital->value) }}"
                            @if($vital->vital && $vital->vital->min_value !== null) min="{{ $vital->vital->min_value }}" @endif
                            @if($vital->vital && $vital->vital->max_value !== null) max="{{ $vital->vital->max_value }}" @endif
                            class="form-input flex-1 rounded-l-lg border border-r-0 border-slate-300 bg-transparent px-3 py-2 text-slate-800 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent @error('value') border-error @enderror"
                            required>
                        @if($unitSymbol)
                            <span class="inline-flex items-center rounded-r-lg border border-l-0 border-slate-300 bg-slate-100 px-3 text-xs font-bold text-slate-600 dark:border-navy-450 dark:bg-navy-600 dark:text-navy-200">
                                {{ $unitSymbol }}
                            </span>
                        @endif
                    </div>
                    @if($vital->vital && $vital->vital->description)
                        <p class="mt-1 text-tiny text-slate-400 dark:text-navy-300">{{ $vital->vital->description }}</p>
                    @endif
                    @error('value')
                        <p class="mt-1 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-between border-t border-slate-150 px-5 py-4 dark:border-navy-600">
                <button type="button"
                    onclick="if(confirm('Are you sure you want to delete this vital record?')) document.getElementById('delete-vital-form').submit();"
                    class="btn h-9 rounded-lg border border-error/30 px-4 text-xs font-semibold text-error hover:bg-error hover:text-white dark:border-error/40">
                    Delete Record
                </button>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('vital.show', $vital->id) }}"
                        class="btn h-9 rounded-lg border border-slate-300 px-4 text-xs font-semibold text-slate-700 hover:bg-slate-100 dark:border-navy-500 dark:text-navy-200 dark:hover:bg-navy-600">
                        Cancel
                    </a>
                    <button type="submit"
                        class="btn h-9 rounded-lg bg-primary px-5 text-xs font-semibold text-white shadow-soft hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Hidden Delete Form -->
    <form id="delete-vital-form"
        action="{{ route('vital.destroy', $vital->id) }}"
        method="POST"
        class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection
