@extends('layouts.app')

@section('title', 'Food & Nutrition Database')

@section('content')
<div class="space-y-5 sm:space-y-6">

    <!-- 1. Page Header -->
    <div class="flex flex-col justify-between space-y-3 sm:flex-row sm:items-center sm:space-y-0">
        <div>
            <div class="flex items-center space-x-2">
                <h1 class="text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl font-inter uppercase">
                    Food &amp; Nutrition Database
                </h1>
                <span class="badge rounded-full bg-primary/10 text-primary dark:bg-accent/15 dark:text-accent-light px-2.5 py-0.5 text-tiny font-bold">
                    {{ $totalFoodsCount }} Verified Foods
                </span>
            </div>
            <p class="mt-1 text-xs text-slate-400 dark:text-navy-300">
                Explore nutritional compositions, portion sizes, macronutrient balances, and micronutrient profiles.
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('meal.index') }}"
                class="btn space-x-1.5 rounded-lg border border-slate-300 px-3.5 py-2 text-xs font-medium text-slate-700 hover:bg-slate-150 focus:bg-slate-150 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span>Meal Plans</span>
            </a>
            <a href="{{ route('meal.create') }}"
                class="btn space-x-1.5 rounded-lg bg-primary px-3.5 py-2 text-xs font-medium text-white shadow-soft hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Create Meal Plan</span>
            </a>
        </div>
    </div>

    <!-- 2. Search & Sort Controls -->
    <div class="card p-4 sm:p-5 shadow-soft">
        <form method="GET" action="{{ route('food.index') }}" class="space-y-4">
            @if(request('category_id'))
                <input type="hidden" name="category_id" value="{{ request('category_id') }}">
            @endif

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-12 sm:gap-4 items-center">
                <!-- Search Input -->
                <div class="sm:col-span-8 lg:col-span-9 relative">
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Search food name, code (e.g. 1004), or description..."
                        class="form-input w-full rounded-lg border border-slate-300 bg-transparent py-2.5 pl-10 pr-4 text-sm text-slate-800 placeholder:text-slate-400 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">
                    <span class="pointer-events-none absolute left-3 top-2.5 text-slate-400 dark:text-navy-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    @if(request('q'))
                        <a href="{{ route('food.index', request()->except('q', 'page')) }}" class="absolute right-3 top-3 text-xs text-slate-400 hover:text-slate-600 dark:hover:text-navy-200">
                            &times; Clear
                        </a>
                    @endif
                </div>

                <!-- Sort Dropdown -->
                <div class="sm:col-span-4 lg:col-span-3 flex items-center space-x-2">
                    <select name="sort" onchange="this.form.submit()"
                        class="form-select w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-xs text-slate-700 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">
                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name (A &rarr; Z)</option>
                        <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Name (Z &rarr; A)</option>
                        <option value="calories_desc" {{ request('sort') === 'calories_desc' ? 'selected' : '' }}>Calories (High &rarr; Low)</option>
                        <option value="calories_asc" {{ request('sort') === 'calories_asc' ? 'selected' : '' }}>Calories (Low &rarr; High)</option>
                        <option value="protein_desc" {{ request('sort') === 'protein_desc' ? 'selected' : '' }}>Protein (High &rarr; Low)</option>
                        <option value="carbs_desc" {{ request('sort') === 'carbs_desc' ? 'selected' : '' }}>Carbohydrates (High &rarr; Low)</option>
                        <option value="fat_desc" {{ request('sort') === 'fat_desc' ? 'selected' : '' }}>Fat (High &rarr; Low)</option>
                    </select>

                    <button type="submit"
                        class="btn rounded-lg bg-primary px-3 py-2 text-xs font-semibold text-white shadow-soft hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus shrink-0">
                        Search
                    </button>
                </div>
            </div>

            <!-- Category Filter Pills -->
            <div class="pt-2 border-t border-slate-150 dark:border-navy-600">
                <p class="text-tiny uppercase tracking-wider font-semibold text-slate-400 dark:text-navy-300 mb-2">
                    Food Categories
                </p>
                <div class="flex flex-wrap gap-1.5 sm:gap-2">
                    @php
                        $isAllActive = !request('category_id');
                    @endphp
                    <a href="{{ route('food.index', request()->except('category_id', 'page')) }}"
                        class="rounded-full px-3 py-1 text-xs font-semibold transition-colors duration-200 {{ $isAllActive ? 'bg-primary text-white dark:bg-accent' : 'border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 dark:border-navy-600 dark:bg-navy-800 dark:text-navy-200 dark:hover:bg-navy-700' }}">
                        All ({{ $totalFoodsCount }})
                    </a>

                    @foreach($categories as $category)
                        @php
                            $isActive = (string) request('category_id') === (string) $category->id;
                            $catParams = request()->except('category_id', 'page');
                            if (!$isActive) {
                                $catParams['category_id'] = $category->id;
                            }
                        @endphp
                        <a href="{{ route('food.index', $catParams) }}"
                            class="rounded-full px-3 py-1 text-xs font-semibold transition-colors duration-200 {{ $isActive ? 'bg-primary text-white dark:bg-accent shadow-xs' : 'border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 dark:border-navy-600 dark:bg-navy-800 dark:text-navy-200 dark:hover:bg-navy-700' }}">
                            {{ ucwords(strtolower($category->name)) }} ({{ $category->foods_count }})
                        </a>
                    @endforeach
                </div>
            </div>
        </form>

        @if(request('q') || request('category_id') || request('sort'))
            <div class="mt-3 pt-3 border-t border-slate-150 dark:border-navy-600 flex items-center justify-between text-xs">
                <span class="text-slate-500 dark:text-navy-300">
                    Showing <strong>{{ $foods->total() }}</strong> results
                    @if(request('q')) for "<strong>{{ request('q') }}</strong>"@endif
                    @if(request('category_id')) in selected category @endif
                </span>
                <a href="{{ route('food.index') }}" class="font-semibold text-primary hover:underline dark:text-accent-light">
                    Reset all filters
                </a>
            </div>
        @endif
    </div>

    <!-- 3. Food Grid -->
    @if($foods->isNotEmpty())
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 sm:gap-5">
            @foreach($foods as $food)
                <div class="card p-4 shadow-soft transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md flex flex-col justify-between">
                    <div>
                        <!-- Category Badge & Code -->
                        <div class="flex items-center justify-between space-x-2 pb-2">
                            <div class="flex flex-wrap gap-1">
                                @forelse($food->categories as $cat)
                                    <span class="badge rounded bg-primary/10 px-2 py-0.5 text-tiny font-bold uppercase tracking-wider text-primary dark:bg-accent/15 dark:text-accent-light">
                                        {{ Str::limit(ucwords(strtolower($cat->name)), 22) }}
                                    </span>
                                @empty
                                    <span class="badge rounded bg-slate-100 px-2 py-0.5 text-tiny font-medium text-slate-400 dark:bg-navy-750 dark:text-navy-300">
                                        Uncategorized
                                    </span>
                                @endforelse
                            </div>
                            <span class="text-tiny font-mono text-slate-400 dark:text-navy-300 shrink-0">
                                #{{ $food->code }}
                            </span>
                        </div>

                        <!-- Food Title -->
                        <a href="{{ route('food.show', $food->id) }}"
                            class="block text-sm-plus font-bold text-slate-800 line-clamp-2 hover:text-primary dark:text-navy-100 dark:hover:text-accent-light transition-colors mt-1">
                            {{ $food->name }}
                        </a>

                        <!-- Serving Size info -->
                        <div class="mt-1 flex items-center space-x-1.5 text-tiny text-slate-400 dark:text-navy-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                            </svg>
                            <span class="truncate">
                                @if($food->standard_portion_description)
                                    {{ $food->standard_portion_description }}
                                    @if($food->standard_portion_grams)
                                        ({{ round($food->standard_portion_grams) }}g)
                                    @endif
                                @elseif($food->standard_portion_grams)
                                    {{ round($food->standard_portion_grams) }}g serving
                                @else
                                    Per 100g standard
                                @endif
                            </span>
                        </div>

                        <!-- Calorie Highlight -->
                        <div class="my-3 flex items-baseline justify-between rounded-lg bg-slate-50 px-3 py-2 dark:bg-navy-750">
                            <div>
                                <span class="text-xs uppercase tracking-wider text-slate-400 dark:text-navy-300 font-semibold">Energy</span>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold font-inter text-slate-800 dark:text-navy-100">
                                    {{ $food->energy_kcal ? round($food->energy_kcal) : '--' }}
                                </span>
                                <span class="text-xs font-normal text-slate-400 dark:text-navy-300">kcal</span>
                            </div>
                        </div>

                        <!-- 4 Macro Pills Grid -->
                        <div class="grid grid-cols-4 gap-1.5 text-center">
                            <div class="rounded-lg bg-rose-50 p-1.5 dark:bg-rose-900/20">
                                <p class="text-tiny font-bold text-rose-600 dark:text-rose-400 font-inter">
                                    {{ $food->protein !== null ? floatval($food->protein) . 'g' : '--' }}
                                </p>
                                <p class="text-[10px] text-slate-400 dark:text-navy-300 uppercase">Protein</p>
                            </div>

                            <div class="rounded-lg bg-sky-50 p-1.5 dark:bg-sky-900/20">
                                <p class="text-tiny font-bold text-sky-600 dark:text-sky-400 font-inter">
                                    {{ $food->carbs !== null ? floatval($food->carbs) . 'g' : '--' }}
                                </p>
                                <p class="text-[10px] text-slate-400 dark:text-navy-300 uppercase">Carbs</p>
                            </div>

                            <div class="rounded-lg bg-amber-50 p-1.5 dark:bg-amber-900/20">
                                <p class="text-tiny font-bold text-amber-600 dark:text-amber-400 font-inter">
                                    {{ $food->fat !== null ? floatval($food->fat) . 'g' : '--' }}
                                </p>
                                <p class="text-[10px] text-slate-400 dark:text-navy-300 uppercase">Fat</p>
                            </div>

                            <div class="rounded-lg bg-emerald-50 p-1.5 dark:bg-emerald-900/20">
                                <p class="text-tiny font-bold text-emerald-600 dark:text-emerald-400 font-inter">
                                    {{ $food->fibre !== null ? floatval($food->fibre) . 'g' : '--' }}
                                </p>
                                <p class="text-[10px] text-slate-400 dark:text-navy-300 uppercase">Fibre</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card Action -->
                    <div class="mt-4 pt-3 border-t border-slate-150 dark:border-navy-600 flex items-center justify-between">
                        <span class="text-tiny text-slate-400 dark:text-navy-300">
                            @php
                                $micronutrientCount = collect([$food->vit_a, $food->vit_c, $food->fe, $food->ca, $food->folate, $food->mg, $food->na])->filter()->count();
                            @endphp
                            {{ $micronutrientCount }} micronutrients
                        </span>
                        <a href="{{ route('food.show', $food->id) }}"
                            class="text-xs font-semibold text-primary hover:text-primary-focus dark:text-accent-light dark:hover:text-accent flex items-center space-x-1">
                            <span>Details</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- 4. Pagination -->
        <div class="mt-6">
            {{ $foods->links() }}
        </div>
    @else
        <div class="card p-12 text-center shadow-soft">
            <div class="mx-auto flex size-14 items-center justify-center rounded-full bg-slate-100 text-slate-400 dark:bg-navy-700 dark:text-navy-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <h3 class="mt-4 text-base font-bold text-slate-700 dark:text-navy-100">
                No foods found
            </h3>
            <p class="mt-1 text-xs text-slate-400 dark:text-navy-300">
                We couldn't find any food items matching your search criteria.
            </p>
            <div class="mt-5">
                <a href="{{ route('food.index') }}"
                    class="btn h-9 rounded-lg bg-primary px-4 text-xs font-semibold text-white shadow-soft hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                    Reset Search &amp; Filters
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
