@extends('layouts.app')

@section('title', 'Meal Plans')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col justify-between space-y-3 sm:flex-row sm:items-center sm:space-y-0">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl uppercase">
                Meal Plans
            </h2>
            <p class="text-sm text-slate-500 dark:text-navy-300">
                Select or manage your structured nutrition plans.
            </p>
        </div>
        <div>
            <a href="{{ route('meal.create') }}"
                class="btn space-x-2 rounded-xl bg-primary px-5 py-2.5 font-semibold text-white shadow-soft hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Create Meal Plan</span>
            </a>
        </div>
    </div>

    <!-- Plan List Card -->
    <div class="card rounded-xl border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <div class="border-b border-slate-150 px-5 py-4 dark:border-navy-600 font-semibold text-xs uppercase tracking-wider text-slate-600 dark:text-navy-200">
            All Meal Plans
        </div>

        <div class="divide-y divide-slate-150 dark:divide-navy-600">
            @forelse(\App\Models\Meal\Plan::get() as $plan)
                <div class="flex flex-col justify-between p-5 transition-colors hover:bg-slate-50 sm:flex-row sm:items-center dark:hover:bg-navy-800/50">
                    <div class="mb-3 sm:mb-0">
                        <a href="{{ route('meal.show', $plan->id) }}" class="text-base font-bold text-slate-800 hover:text-primary dark:text-navy-100 dark:hover:text-accent-light">
                            {{ $plan->name }}
                        </a>
                        <p class="mt-1 text-xs text-slate-400 dark:text-navy-300">
                            {{ $plan->meal_plan_day()->count() }} scheduled active days
                        </p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('meal.show', $plan->id) }}"
                            class="btn h-8 rounded-lg border border-primary/30 px-3 text-xs font-semibold text-primary hover:bg-primary/10 dark:border-accent/30 dark:text-accent-light dark:hover:bg-accent/10 flex items-center space-x-1">
                            <span>Configure</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <svg class="mx-auto size-12 text-slate-300 dark:text-navy-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <h4 class="mt-3 text-base font-semibold text-slate-700 dark:text-navy-100">No meal plans registered</h4>
                    <p class="mt-1 text-xs text-slate-400 dark:text-navy-300">Get started by creating your first meal plan.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
