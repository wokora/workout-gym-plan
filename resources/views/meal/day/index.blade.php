@extends('layouts.app')

@section('title', 'Meal Day')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col justify-between space-y-3 sm:flex-row sm:items-center sm:space-y-0">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl">
                {{ $day->meal_plan->name }} &bull; <span class="text-primary dark:text-accent-light">{{ $day->day->name }}</span>
            </h2>
            <a href="{{ route('meal.show', $day->meal_plan->id) }}" class="text-xs font-semibold text-slate-500 hover:text-primary dark:text-navy-300 dark:hover:text-accent-light">
                &larr; Back to Meal Plan
            </a>
        </div>
        <div>
            <a href="{{ route('meal.day.entry.create', [$day->meal_plan->id, $day->id]) }}"
                class="btn space-x-2 rounded-xl bg-primary px-4 py-2 text-xs font-semibold text-white shadow-soft hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Food Entry</span>
            </a>
        </div>
    </div>

    @php
        $entries = $day->entry()->orderBy('number', 'ASC')->get();
        $grouped = $entries->groupBy('meal_type_id');
        $mealTypes = \App\Models\Meal\Type::orderBy('number')->get();
        $firstActiveTypeId = null;
        foreach($mealTypes as $t) {
            if ($grouped->has($t->id)) {
                $firstActiveTypeId = $t->id;
                break;
            }
        }
    @endphp

    @if($entries->count() > 0)
        <div x-data="{ activeMealTab: 'tab-{{ $firstActiveTypeId }}' }" class="space-y-4">
            <!-- Meal Type Tabs -->
            <div class="flex flex-wrap gap-2 border-b border-slate-200 pb-2 dark:border-navy-600">
                @foreach($mealTypes as $type)
                    @php $hasEntries = $grouped->has($type->id); @endphp
                    @if($hasEntries)
                        <button type="button"
                            @click="activeMealTab = 'tab-{{ $type->id }}'"
                            :class="activeMealTab === 'tab-{{ $type->id }}' ? 'bg-primary text-white dark:bg-accent' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-navy-800 dark:text-navy-200 dark:hover:bg-navy-700'"
                            class="btn h-8 rounded-full px-4 text-xs font-semibold transition-colors">
                            {{ $type->name }}
                        </button>
                    @endif
                @endforeach
            </div>

            <!-- Tab Panels -->
            @foreach($mealTypes as $type)
                @if($grouped->has($type->id))
                    <div x-show="activeMealTab === 'tab-{{ $type->id }}'" x-transition class="space-y-4">
                        <div class="card rounded-xl border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
                            <div class="divide-y divide-slate-150 dark:divide-navy-600">
                                @foreach($grouped[$type->id] as $entry)
                                    <div class="p-5">
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-sm font-bold text-slate-800 dark:text-navy-100">
                                                {{ $entry->meal_type->name }}
                                            </span>
                                            <a href="{{ route('meal.day.entry.edit', [$day->meal_plan->id, $day->id, $entry->id]) }}"
                                                class="btn h-8 rounded-lg border border-slate-300 px-3 text-xs font-medium text-slate-700 hover:bg-slate-100 dark:border-navy-500 dark:text-navy-100 dark:hover:bg-navy-600">
                                                Manage
                                            </a>
                                        </div>

                                        <div class="flex flex-wrap gap-2">
                                            @foreach($entry->foods as $entryFood)
                                                <a href="{{ route('meal.day.entry.show', [$day->meal_plan->id, $day->id, $entry->id]) }}"
                                                    class="inline-flex items-center space-x-1.5 rounded-lg border border-primary/20 bg-primary/5 px-3 py-1.5 text-xs font-semibold text-primary hover:bg-primary/10 dark:border-accent/25 dark:bg-accent/10 dark:text-accent-light dark:hover:bg-accent/15 transition-colors">
                                                    <span>{{ $entryFood->food->name }}</span>
                                                    <span class="text-tiny font-normal text-slate-400 dark:text-navy-300">{{ $entryFood->food->energy_kcal }}kcal</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Macro Totals -->
                        @php
                            $tabKcal = $grouped[$type->id]->sum(fn($e) => $e->foods->sum(fn($f) => $f->food->energy_kcal ?? 0));
                            $tabProtein = $grouped[$type->id]->sum(fn($e) => $e->foods->sum(fn($f) => $f->food->protein ?? 0));
                            $tabCarbs = $grouped[$type->id]->sum(fn($e) => $e->foods->sum(fn($f) => $f->food->carbs ?? 0));
                            $tabFat = $grouped[$type->id]->sum(fn($e) => $e->foods->sum(fn($f) => $f->food->fat ?? 0));
                        @endphp
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                            <div class="card rounded-xl border border-slate-150 bg-white p-4 text-center shadow-soft dark:border-navy-600 dark:bg-navy-700">
                                <div class="text-xl font-bold text-slate-800 dark:text-navy-100">{{ round($tabKcal) }}</div>
                                <span class="text-tiny uppercase font-semibold text-slate-400 dark:text-navy-300">Calories</span>
                            </div>
                            <div class="card rounded-xl border border-slate-150 bg-white p-4 text-center shadow-soft dark:border-navy-600 dark:bg-navy-700">
                                <div class="text-xl font-bold text-info">{{ round($tabProtein, 1) }}g</div>
                                <span class="text-tiny uppercase font-semibold text-slate-400 dark:text-navy-300">Protein</span>
                            </div>
                            <div class="card rounded-xl border border-slate-150 bg-white p-4 text-center shadow-soft dark:border-navy-600 dark:bg-navy-700">
                                <div class="text-xl font-bold text-warning">{{ round($tabCarbs, 1) }}g</div>
                                <span class="text-tiny uppercase font-semibold text-slate-400 dark:text-navy-300">Carbs</span>
                            </div>
                            <div class="card rounded-xl border border-slate-150 bg-white p-4 text-center shadow-soft dark:border-navy-600 dark:bg-navy-700">
                                <div class="text-xl font-bold text-error">{{ round($tabFat, 1) }}g</div>
                                <span class="text-tiny uppercase font-semibold text-slate-400 dark:text-navy-300">Fat</span>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <div class="card rounded-xl border border-slate-150 bg-white p-12 text-center shadow-soft dark:border-navy-600 dark:bg-navy-700">
            <svg class="mx-auto size-12 text-slate-300 dark:text-navy-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <h5 class="mt-3 text-base font-semibold text-slate-700 dark:text-navy-100">No food entries for this day</h5>
            <p class="mt-1 text-xs text-slate-400 dark:text-navy-300 mb-4">Start adding foods to build your meal plan!</p>
            <a href="{{ route('meal.day.entry.create', [$day->meal_plan->id, $day->id]) }}" class="btn h-8 rounded-lg bg-primary px-3 text-xs font-semibold text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                Add First Food
            </a>
        </div>
    @endif
</div>
@endsection
