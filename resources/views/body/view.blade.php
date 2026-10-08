@extends('layouts.app')

@section('title', 'Body Section')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col justify-between space-y-3 sm:flex-row sm:items-center sm:space-y-0">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl">
                {{ $body->name }}
            </h2>
            <a href="{{ route('body.index') }}" class="text-xs font-semibold text-slate-500 hover:text-primary dark:text-navy-300 dark:hover:text-accent-light">
                &larr; Back to Sections
            </a>
        </div>
        <div>
            <a href="{{ route('body.edit', $body->id) }}"
                class="btn h-9 rounded-lg bg-primary px-4 text-xs font-semibold text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                Edit Section
            </a>
        </div>
    </div>

    <!-- Detail Card -->
    <div class="card rounded-xl border border-slate-150 bg-white p-5 shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <div class="border-b border-slate-150 pb-4 dark:border-navy-600 font-semibold text-xs uppercase tracking-wider text-slate-600 dark:text-navy-200">
            Section Description & Info
        </div>

        <div class="mt-5">
            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-navy-300 mb-2">
                Description
            </label>
            <div class="rounded-lg bg-slate-50 p-4 text-sm leading-relaxed text-slate-700 dark:bg-navy-800 dark:text-navy-100 min-h-[90px]">
                {{ $body->description ?: 'No description recorded for this body section.' }}
            </div>
        </div>
    </div>
</div>
@endsection
