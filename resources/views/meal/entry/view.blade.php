@extends('layouts.app')

@section('title', 'Food Entry Detail')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col justify-between space-y-3 sm:flex-row sm:items-center sm:space-y-0">
        <div>
            <div class="flex items-center space-x-2">
                <span class="badge rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-semibold text-primary dark:bg-accent/15 dark:text-accent-light">
                    {{ $entry->meal_type->name }}
                </span>
                <span class="text-xs text-slate-400 dark:text-navy-300">&bull;</span>
                <a href="{{ route('meal.day.show', [$entry->meal_plan_day->meal_plan->id, $entry->meal_plan_day->id]) }}"
                    class="text-xs font-semibold text-slate-500 hover:text-primary dark:text-navy-300 dark:hover:text-accent-light">
                    {{ $entry->meal_plan_day->day->name }} ({{ $entry->meal_plan_day->meal_plan->name }})
                </a>
            </div>
            <h2 class="mt-1 text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl">
                {{ $entry->meal_type->name }} Details
            </h2>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('meal.day.entry.edit', [$entry->meal_plan_day->meal_plan->id, $entry->meal_plan_day->id, $entry->id]) }}"
                class="btn h-8 rounded-lg border border-slate-300 px-3 text-xs font-medium text-slate-700 hover:bg-slate-100 dark:border-navy-500 dark:text-navy-100 dark:hover:bg-navy-600">
                Edit Entry
            </a>
            <form action="{{ route('meal.day.entry.destroy', [$entry->meal_plan_day->meal_plan->id, $entry->meal_plan_day->id, $entry->id]) }}"
                method="POST"
                onsubmit="return confirm('Delete this entry?');"
                class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="btn h-8 rounded-lg border border-error/30 px-3 text-xs font-medium text-error hover:bg-error hover:text-white dark:border-error/40">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Macro Metrics Cards -->
    @php
        $totalKcal = $entry->foods->sum(fn($f) => $f->food->energy_kcal ?? 0);
        $totalProtein = $entry->foods->sum(fn($f) => $f->food->protein ?? 0);
        $totalCarbs = $entry->foods->sum(fn($f) => $f->food->carbs ?? 0);
        $totalFat = $entry->foods->sum(fn($f) => $f->food->fat ?? 0);
    @endphp
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4">
        <div class="card rounded-xl border border-slate-150 bg-white p-4 text-center shadow-soft dark:border-navy-600 dark:bg-navy-700">
            <div class="text-2xl font-bold text-slate-800 dark:text-navy-100">{{ round($totalKcal) }}</div>
            <span class="text-tiny uppercase font-semibold text-slate-400 dark:text-navy-300">Calories (kcal)</span>
        </div>
        <div class="card rounded-xl border border-slate-150 bg-white p-4 text-center shadow-soft dark:border-navy-600 dark:bg-navy-700">
            <div class="text-2xl font-bold text-info">{{ round($totalProtein, 1) }}g</div>
            <span class="text-tiny uppercase font-semibold text-slate-400 dark:text-navy-300">Protein</span>
        </div>
        <div class="card rounded-xl border border-slate-150 bg-white p-4 text-center shadow-soft dark:border-navy-600 dark:bg-navy-700">
            <div class="text-2xl font-bold text-warning">{{ round($totalCarbs, 1) }}g</div>
            <span class="text-tiny uppercase font-semibold text-slate-400 dark:text-navy-300">Carbohydrates</span>
        </div>
        <div class="card rounded-xl border border-slate-150 bg-white p-4 text-center shadow-soft dark:border-navy-600 dark:bg-navy-700">
            <div class="text-2xl font-bold text-error">{{ round($totalFat, 1) }}g</div>
            <span class="text-tiny uppercase font-semibold text-slate-400 dark:text-navy-300">Fat</span>
        </div>
    </div>

    <!-- Notes if any -->
    @if($entry->description)
        <div class="card rounded-xl border border-slate-150 bg-white p-5 shadow-soft dark:border-navy-600 dark:bg-navy-700">
            <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-navy-300 mb-2">Notes</h4>
            <p class="text-sm text-slate-700 dark:text-navy-100">{{ $entry->description }}</p>
        </div>
    @endif

    <!-- Foods List -->
    <div class="card rounded-xl border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <div class="flex items-center justify-between border-b border-slate-150 px-5 py-4 dark:border-navy-600">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-navy-200">
                Foods in this Meal
            </span>
            <span class="badge rounded bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600 dark:bg-navy-600 dark:text-navy-200">
                {{ $entry->foods->count() }} {{ \Illuminate\Support\Str::plural('item', $entry->foods->count()) }}
            </span>
        </div>

        <div class="divide-y divide-slate-150 dark:divide-navy-600">
            @forelse($entry->foods as $entryFood)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 sm:p-5 hover:bg-slate-50 dark:hover:bg-navy-800/50 transition-colors">
                    <div class="space-y-1 mb-2 sm:mb-0">
                        <div class="text-sm font-bold text-slate-800 dark:text-navy-100">
                            {{ $entryFood->food->name }}
                        </div>
                        <div class="flex flex-wrap items-center gap-2 text-xs text-slate-400 dark:text-navy-300">
                            <span>{{ $entryFood->food->energy_kcal }} kcal/100g</span>
                            @if($entryFood->food->protein)
                                <span class="text-info">&bull; {{ $entryFood->food->protein }}g protein</span>
                            @endif
                            @if($entryFood->food->carbs)
                                <span class="text-warning">&bull; {{ $entryFood->food->carbs }}g carbs</span>
                            @endif
                            @if($entryFood->food->fat)
                                <span class="text-error">&bull; {{ $entryFood->food->fat }}g fat</span>
                            @endif
                        </div>
                        @if($entryFood->food->description)
                            <p class="text-xs text-slate-500 dark:text-navy-300">{{ $entryFood->food->description }}</p>
                        @endif
                    </div>
                    @if($entryFood->food->standard_portion_description)
                        <div class="text-left sm:text-right shrink-0">
                            <span class="text-xs font-medium text-slate-600 dark:text-navy-200">{{ $entryFood->food->standard_portion_description }}</span>
                            <div class="text-tiny text-slate-400 dark:text-navy-300">{{ $entryFood->food->standard_portion_grams }}g</div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="p-8 text-center text-xs text-slate-400 dark:text-navy-300">
                    No food items associated with this entry.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
