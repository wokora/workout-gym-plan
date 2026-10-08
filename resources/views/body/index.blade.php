@extends('layouts.app')

@section('title', 'Body Sections')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col justify-between space-y-3 sm:flex-row sm:items-center sm:space-y-0">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl uppercase">
                Body Sections
            </h2>
            <p class="text-sm text-slate-500 dark:text-navy-300">
                Muscle groups targeted by catalog exercises.
            </p>
        </div>
        <div>
            <a href="{{ route('body.create') }}"
                class="btn space-x-2 rounded-xl bg-primary px-5 py-2.5 font-semibold text-white shadow-soft hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Body Section</span>
            </a>
        </div>
    </div>

    <!-- Section List Card -->
    <div class="card rounded-xl border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <div class="border-b border-slate-150 px-5 py-4 dark:border-navy-600 font-semibold text-xs uppercase tracking-wider text-slate-600 dark:text-navy-200">
            All Registered Sections
        </div>

        <div class="divide-y divide-slate-150 dark:divide-navy-600">
            @forelse(\App\Models\Body\Body::get() as $body_section)
                <div class="flex flex-col justify-between p-5 transition-colors hover:bg-slate-50 sm:flex-row sm:items-center dark:hover:bg-navy-800/50">
                    <div class="mb-3 sm:mb-0">
                        <a href="{{ route('body.show', $body_section->id) }}" class="text-base font-bold text-slate-800 hover:text-primary dark:text-navy-100 dark:hover:text-accent-light">
                            {{ $body_section->name }}
                        </a>
                        <p class="mt-1 text-xs text-slate-400 dark:text-navy-300 line-clamp-1">
                            {{ Str::limit($body_section->description, 70, '...') ?: 'No description provided.' }}
                        </p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('body.show', $body_section->id) }}"
                            class="btn h-8 rounded-lg border border-primary/30 px-3 text-xs font-semibold text-primary hover:bg-primary/10 dark:border-accent/30 dark:text-accent-light dark:hover:bg-accent/10">
                            Configure
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <svg class="mx-auto size-12 text-slate-300 dark:text-navy-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <h4 class="mt-3 text-base font-semibold text-slate-700 dark:text-navy-100">No body sections found</h4>
                    <p class="mt-1 text-xs text-slate-400 dark:text-navy-300">Add muscle target groups to populate the list.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
