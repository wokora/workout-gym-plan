@extends('layouts.app')

@section('title', 'Meal Plan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col justify-between space-y-3 sm:flex-row sm:items-center sm:space-y-0">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl">
                {{ $meal->name }}
            </h2>
            <p class="text-sm text-slate-500 dark:text-navy-300">
                Weekly scheduled nutrition plan and macro targets.
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('meal.edit', $meal->id) }}"
                class="btn h-9 rounded-lg border border-slate-300 px-3.5 text-xs font-semibold text-slate-700 hover:bg-slate-100 dark:border-navy-500 dark:text-navy-100 dark:hover:bg-navy-600">
                Edit Plan
            </a>
            <form action="{{ route('meal.destroy', $meal->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this meal plan?');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="btn h-9 rounded-lg border border-error/30 px-3.5 text-xs font-semibold text-error hover:bg-error/10">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Scheduled Days List -->
    <div class="card rounded-xl border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <div class="border-b border-slate-150 px-5 py-4 dark:border-navy-600 font-semibold text-xs uppercase tracking-wider text-slate-600 dark:text-navy-200">
            Scheduled Days
        </div>

        <div class="divide-y divide-slate-150 dark:divide-navy-600">
            @foreach($meal->meal_plan_day()->orderBy('number', 'ASC')->get() as $meal_plan_day)
                <a href="{{ route('meal.day.show', [$meal->id, $meal_plan_day->id]) }}"
                    class="flex items-center justify-between p-4 transition-colors hover:bg-slate-50 dark:hover:bg-navy-800/50 {{ $meal_plan_day->day->number == date('w') ? 'bg-primary/5 dark:bg-accent/5' : '' }}">
                    <div class="flex items-center space-x-2">
                        <span class="font-bold text-sm text-slate-800 dark:text-navy-100">{{ $meal_plan_day->day->name }}</span>
                        @if($meal_plan_day->day->number == date('w'))
                            <span class="badge rounded bg-success/15 text-success text-tiny font-bold px-2 py-0.5">TODAY</span>
                        @endif
                    </div>
                    <span class="flex items-center space-x-1 text-xs font-semibold text-primary dark:text-accent-light">
                        <span>View Meals</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
