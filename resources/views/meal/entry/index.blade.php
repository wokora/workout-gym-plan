@extends('layouts.app')

@section('title', 'Food Entries')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col justify-between space-y-3 sm:flex-row sm:items-center sm:space-y-0">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl">
                Food Entries
            </h2>
            <p class="text-xs text-slate-500 dark:text-navy-300">
                All entries for {{ $day->name }} in <span class="font-semibold text-slate-700 dark:text-navy-100">{{ $meal->name }}</span>
            </p>
        </div>
        <a href="{{ route('meal.day.entry.create', [$meal->id, $day->id]) }}"
            class="btn space-x-2 rounded-xl bg-primary px-4 py-2 text-xs font-semibold text-white shadow-soft hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Add Food Entry</span>
        </a>
    </div>

    <!-- Entries Card -->
    <div class="card rounded-xl border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <div class="border-b border-slate-150 px-5 py-4 dark:border-navy-600 text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-navy-200">
            Recorded Entries
        </div>

        <div class="divide-y divide-slate-150 dark:divide-navy-600">
            @forelse($day->entry as $entry)
                <a href="{{ route('meal.day.entry.show', [$meal->id, $day->id, $entry->id]) }}"
                    class="flex items-center justify-between p-4 sm:p-5 hover:bg-slate-50 dark:hover:bg-navy-800/50 transition-colors">
                    <div>
                        <div class="text-sm font-bold text-slate-800 dark:text-navy-100">
                            {{ $entry->meal_type->name }}
                        </div>
                        <p class="mt-0.5 text-xs text-slate-400 dark:text-navy-300">
                            {{ $entry->foods->pluck('food.name')->join(', ') }}
                        </p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-slate-400 dark:text-navy-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @empty
                <div class="p-8 text-center text-xs text-slate-400 dark:text-navy-300">
                    No food entries recorded for this day yet.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
