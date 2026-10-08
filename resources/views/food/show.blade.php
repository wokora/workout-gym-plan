@extends('layouts.app')

@section('title', $food->name . ' - Nutrition Profile')

@section('content')
<div class="space-y-5 sm:space-y-6">

    <!-- 1. Header & Back Link -->
    <div class="flex flex-col justify-between space-y-3 sm:flex-row sm:items-center sm:space-y-0">
        <div>
            <div class="flex items-center space-x-2 text-xs text-slate-400 dark:text-navy-300 mb-1">
                <a href="{{ route('food.index') }}" class="hover:text-primary dark:hover:text-accent-light">
                    &larr; Back to Food Database
                </a>
                <span>&bull;</span>
                <span>Code #{{ $food->code }}</span>
            </div>
            <h1 class="text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl font-inter">
                {{ $food->name }}
            </h1>
            <div class="mt-1 flex flex-wrap items-center gap-1.5">
                @foreach($food->categories as $cat)
                    <span class="badge rounded-full bg-primary/10 text-primary dark:bg-accent/15 dark:text-accent-light px-2.5 py-0.5 text-tiny font-bold uppercase tracking-wider">
                        {{ ucwords(strtolower($cat->name)) }}
                    </span>
                @endforeach
                @if($food->standard_portion_description)
                    <span class="text-xs text-slate-400 dark:text-navy-300">
                        &bull; {{ $food->standard_portion_description }} ({{ $food->standard_portion_grams ? round($food->standard_portion_grams) . 'g' : '100g' }})
                    </span>
                @endif
            </div>
        </div>

        <div class="flex items-center space-x-2">
            <a href="{{ route('food.index') }}"
                class="btn space-x-1.5 rounded-lg border border-slate-300 px-3.5 py-2 text-xs font-medium text-slate-700 hover:bg-slate-150 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span>Browse All Foods</span>
            </a>
            <a href="{{ route('meal.index') }}"
                class="btn space-x-1.5 rounded-lg bg-primary px-3.5 py-2 text-xs font-semibold text-white shadow-soft hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span>Meal Plans</span>
            </a>
        </div>
    </div>

    <!-- 2. Main Content Grid -->
    <div class="grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6">

        <!-- Left Column: Core Nutritional Profile (8 cols) -->
        <div class="col-span-12 lg:col-span-8 space-y-4 sm:space-y-5">

            <!-- Serving & Energy Header Card -->
            <div class="card p-4 sm:p-5 shadow-soft">
                <div class="flex items-center space-x-2 pb-3 border-b border-slate-150 dark:border-navy-600">
                    <div class="mask is-star flex size-8 shrink-0 items-center justify-center bg-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm-plus font-bold text-slate-800 dark:text-navy-100">
                            Energy &amp; Serving Standard
                        </h2>
                        <p class="text-tiny text-slate-400 dark:text-navy-300">
                            Nutritional density measured per standard serving and 100g reference.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4 my-4">
                    <div class="rounded-xl border border-slate-150 bg-slate-50/60 p-3 text-center dark:border-navy-600 dark:bg-navy-750">
                        <div class="text-xl font-bold font-inter text-slate-800 dark:text-navy-100 sm:text-2xl">
                            {{ $food->energy_kcal ? round($food->energy_kcal) : '--' }}
                        </div>
                        <div class="mt-1 text-tiny font-semibold uppercase tracking-wider text-slate-400 dark:text-navy-300">
                            Calories (kcal)
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-150 bg-slate-50/60 p-3 text-center dark:border-navy-600 dark:bg-navy-750">
                        <div class="text-xl font-bold font-inter text-slate-800 dark:text-navy-100 sm:text-2xl">
                            {{ $food->energy_kj ? round($food->energy_kj) : '--' }}
                        </div>
                        <div class="mt-1 text-tiny font-semibold uppercase tracking-wider text-slate-400 dark:text-navy-300">
                            Energy (kJ)
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-150 bg-slate-50/60 p-3 text-center dark:border-navy-600 dark:bg-navy-750">
                        <div class="text-xl font-bold font-inter text-info sm:text-2xl">
                            {{ $food->standard_portion_grams ? round($food->standard_portion_grams) . 'g' : '100g' }}
                        </div>
                        <div class="mt-1 text-tiny font-semibold uppercase tracking-wider text-slate-400 dark:text-navy-300">
                            Portion Weight
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-150 bg-slate-50/60 p-3 text-center dark:border-navy-600 dark:bg-navy-750">
                        <div class="text-xl font-bold font-inter text-slate-700 dark:text-navy-100 sm:text-2xl">
                            {{ $food->water !== null ? floatval($food->water) . 'g' : '--' }}
                        </div>
                        <div class="mt-1 text-tiny font-semibold uppercase tracking-wider text-slate-400 dark:text-navy-300">
                            Water Content
                        </div>
                    </div>
                </div>

                @if($food->standard_portion_description)
                    <div class="rounded-lg bg-info/10 px-3 py-2 text-xs text-info dark:bg-info/15 flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Standard serving description: <strong>{{ $food->standard_portion_description }}</strong></span>
                    </div>
                @endif
            </div>

            <!-- Macronutrient Distribution Card -->
            <div class="card p-4 sm:p-5 shadow-soft">
                <div class="flex items-center space-x-2 pb-3 border-b border-slate-150 dark:border-navy-600">
                    <div class="mask is-star flex size-8 shrink-0 items-center justify-center bg-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm-plus font-bold text-slate-800 dark:text-navy-100">
                            Macronutrient Breakdown
                        </h2>
                        <p class="text-tiny text-slate-400 dark:text-navy-300">
                            Key macronutrients driving metabolism, muscle protein synthesis, and cellular energy.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4 my-4">
                    <div class="rounded-xl border border-rose-200 bg-rose-50/50 p-3.5 text-center dark:border-rose-900/30 dark:bg-rose-900/10">
                        <div class="text-2xl font-bold font-inter text-rose-600 dark:text-rose-400">
                            {{ $food->protein !== null ? floatval($food->protein) : 0 }}<span class="text-xs font-normal">g</span>
                        </div>
                        <div class="mt-1 text-tiny font-bold uppercase tracking-wider text-rose-500">
                            Protein
                        </div>
                        <div class="mt-1 text-[11px] text-slate-400 dark:text-navy-300">
                            {{ round(($food->protein ?? 0) * 4) }} kcal
                        </div>
                    </div>

                    <div class="rounded-xl border border-sky-200 bg-sky-50/50 p-3.5 text-center dark:border-sky-900/30 dark:bg-sky-900/10">
                        <div class="text-2xl font-bold font-inter text-sky-600 dark:text-sky-400">
                            {{ $food->carbs !== null ? floatval($food->carbs) : 0 }}<span class="text-xs font-normal">g</span>
                        </div>
                        <div class="mt-1 text-tiny font-bold uppercase tracking-wider text-sky-500">
                            Carbohydrates
                        </div>
                        <div class="mt-1 text-[11px] text-slate-400 dark:text-navy-300">
                            {{ round(($food->carbs ?? 0) * 4) }} kcal
                        </div>
                    </div>

                    <div class="rounded-xl border border-amber-200 bg-amber-50/50 p-3.5 text-center dark:border-amber-900/30 dark:bg-amber-900/10">
                        <div class="text-2xl font-bold font-inter text-amber-600 dark:text-amber-400">
                            {{ $food->fat !== null ? floatval($food->fat) : 0 }}<span class="text-xs font-normal">g</span>
                        </div>
                        <div class="mt-1 text-tiny font-bold uppercase tracking-wider text-amber-500">
                            Total Fat
                        </div>
                        <div class="mt-1 text-[11px] text-slate-400 dark:text-navy-300">
                            {{ round(($food->fat ?? 0) * 9) }} kcal
                        </div>
                    </div>

                    <div class="rounded-xl border border-emerald-200 bg-emerald-50/50 p-3.5 text-center dark:border-emerald-900/30 dark:bg-emerald-900/10">
                        <div class="text-2xl font-bold font-inter text-emerald-600 dark:text-emerald-400">
                            {{ $food->fibre !== null ? floatval($food->fibre) : 0 }}<span class="text-xs font-normal">g</span>
                        </div>
                        <div class="mt-1 text-tiny font-bold uppercase tracking-wider text-emerald-500">
                            Dietary Fibre
                        </div>
                        <div class="mt-1 text-[11px] text-slate-400 dark:text-navy-300">
                            Digestive health
                        </div>
                    </div>
                </div>
            </div>

            <!-- Micronutrients & Essential Minerals Table -->
            <div class="card p-4 sm:p-5 shadow-soft">
                <div class="flex items-center space-x-2 pb-3 border-b border-slate-150 dark:border-navy-600">
                    <div class="mask is-star flex size-8 shrink-0 items-center justify-center bg-info">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm-plus font-bold text-slate-800 dark:text-navy-100">
                            Micronutrients &amp; Minerals
                        </h2>
                        <p class="text-tiny text-slate-400 dark:text-navy-300">
                            Essential vitamins, electrolytes, and trace minerals.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-3">
                    <!-- Minerals Column -->
                    <div class="space-y-2">
                        <h3 class="text-tiny uppercase font-bold tracking-wider text-slate-400 dark:text-navy-300">
                            Minerals &amp; Electrolytes
                        </h3>
                        <div class="divide-y divide-slate-150 dark:divide-navy-600 rounded-lg border border-slate-150 dark:border-navy-600">
                            <div class="flex items-center justify-between p-2.5 text-xs">
                                <span class="font-medium text-slate-700 dark:text-navy-100">Calcium (Ca)</span>
                                <span class="font-bold text-slate-800 dark:text-navy-100 font-inter">{{ $food->ca !== null ? floatval($food->ca) . ' mg' : '--' }}</span>
                            </div>
                            <div class="flex items-center justify-between p-2.5 text-xs">
                                <span class="font-medium text-slate-700 dark:text-navy-100">Iron (Fe)</span>
                                <span class="font-bold text-slate-800 dark:text-navy-100 font-inter">{{ $food->fe !== null ? floatval($food->fe) . ' mg' : '--' }}</span>
                            </div>
                            <div class="flex items-center justify-between p-2.5 text-xs">
                                <span class="font-medium text-slate-700 dark:text-navy-100">Sodium (Na)</span>
                                <span class="font-bold text-slate-800 dark:text-navy-100 font-inter">{{ $food->na !== null ? floatval($food->na) . ' mg' : '--' }}</span>
                            </div>
                            <div class="flex items-center justify-between p-2.5 text-xs">
                                <span class="font-medium text-slate-700 dark:text-navy-100">Magnesium (Mg)</span>
                                <span class="font-bold text-slate-800 dark:text-navy-100 font-inter">{{ $food->mg !== null ? floatval($food->mg) . ' mg' : '--' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Vitamins Column -->
                    <div class="space-y-2">
                        <h3 class="text-tiny uppercase font-bold tracking-wider text-slate-400 dark:text-navy-300">
                            Vitamins &amp; Co-factors
                        </h3>
                        <div class="divide-y divide-slate-150 dark:divide-navy-600 rounded-lg border border-slate-150 dark:border-navy-600">
                            <div class="flex items-center justify-between p-2.5 text-xs">
                                <span class="font-medium text-slate-700 dark:text-navy-100">Vitamin A</span>
                                <span class="font-bold text-slate-800 dark:text-navy-100 font-inter">{{ $food->vit_a !== null ? floatval($food->vit_a) . ' &micro;g' : '--' }}</span>
                            </div>
                            <div class="flex items-center justify-between p-2.5 text-xs">
                                <span class="font-medium text-slate-700 dark:text-navy-100">Vitamin C</span>
                                <span class="font-bold text-slate-800 dark:text-navy-100 font-inter">{{ $food->vit_c !== null ? floatval($food->vit_c) . ' mg' : '--' }}</span>
                            </div>
                            <div class="flex items-center justify-between p-2.5 text-xs">
                                <span class="font-medium text-slate-700 dark:text-navy-100">Folate</span>
                                <span class="font-bold text-slate-800 dark:text-navy-100 font-inter">{{ $food->folate !== null ? floatval($food->folate) . ' &micro;g' : '--' }}</span>
                            </div>
                            <div class="flex items-center justify-between p-2.5 text-xs">
                                <span class="font-medium text-slate-700 dark:text-navy-100">Ash</span>
                                <span class="font-bold text-slate-800 dark:text-navy-100 font-inter">{{ $food->ash !== null ? floatval($food->ash) . ' g' : '--' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Caloric Energy Ratios & Related Foods (4 cols) -->
        <div class="col-span-12 lg:col-span-4 space-y-4 sm:space-y-5">

            <!-- Caloric Energy Ratio Card -->
            @php
                $pCal = ($food->protein ?? 0) * 4;
                $cCal = ($food->carbs ?? 0) * 4;
                $fCal = ($food->fat ?? 0) * 9;
                $totalMacroCal = $pCal + $cCal + $fCal;
                $pPct = $totalMacroCal > 0 ? round(($pCal / $totalMacroCal) * 100) : 0;
                $cPct = $totalMacroCal > 0 ? round(($cCal / $totalMacroCal) * 100) : 0;
                $fPct = $totalMacroCal > 0 ? max(0, 100 - $pPct - $cPct) : 0;
            @endphp
            <div class="card p-4 sm:p-5 shadow-soft space-y-3">
                <h3 class="text-sm font-bold text-slate-800 dark:text-navy-100 pb-2 border-b border-slate-150 dark:border-navy-600">
                    Caloric Energy Distribution
                </h3>

                <!-- Distribution Stacked Bar -->
                <div class="space-y-1.5">
                    <div class="flex h-3 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-navy-600">
                        <div style="width: {{ $pPct }}%;" class="bg-rose-500" title="Protein: {{ $pPct }}%"></div>
                        <div style="width: {{ $cPct }}%;" class="bg-sky-500" title="Carbs: {{ $cPct }}%"></div>
                        <div style="width: {{ $fPct }}%;" class="bg-amber-500" title="Fat: {{ $fPct }}%"></div>
                    </div>
                    <div class="flex justify-between text-tiny text-slate-400 dark:text-navy-300">
                        <span class="text-rose-500 font-semibold">{{ $pPct }}% Protein</span>
                        <span class="text-sky-500 font-semibold">{{ $cPct }}% Carbs</span>
                        <span class="text-amber-500 font-semibold">{{ $fPct }}% Fat</span>
                    </div>
                </div>

                <div class="pt-2 text-xs text-slate-400 dark:text-navy-300">
                    Macronutrient energy calculated using Atwater general factors (4 kcal/g protein &amp; carbs, 9 kcal/g fat).
                </div>
            </div>

            <!-- Related Foods in Category -->
            @if($relatedFoods->isNotEmpty())
                <div class="card p-4 sm:p-5 shadow-soft space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-150 dark:border-navy-600">
                        <h3 class="text-sm font-bold text-slate-800 dark:text-navy-100">
                            Similar Foods
                        </h3>
                        <a href="{{ route('food.index', ['category_id' => $food->categories->first()->id ?? null]) }}"
                            class="text-tiny font-semibold text-primary hover:underline dark:text-accent-light">
                            View All &rarr;
                        </a>
                    </div>

                    <div class="divide-y divide-slate-150 dark:divide-navy-600">
                        @foreach($relatedFoods as $rf)
                            <a href="{{ route('food.show', $rf->id) }}"
                                class="flex items-center justify-between py-2.5 transition-colors hover:text-primary dark:hover:text-accent-light group block">
                                <div class="pr-2">
                                    <p class="text-xs font-semibold text-slate-700 dark:text-navy-100 line-clamp-1 group-hover:text-primary dark:group-hover:text-accent-light">
                                        {{ $rf->name }}
                                    </p>
                                    <p class="text-tiny text-slate-400 dark:text-navy-300">
                                        {{ $rf->protein ? floatval($rf->protein) . 'g protein' : '0g protein' }}
                                    </p>
                                </div>
                                <span class="badge rounded bg-slate-100 px-2 py-0.5 text-tiny font-bold text-slate-700 dark:bg-navy-750 dark:text-navy-200 shrink-0 font-inter">
                                    {{ $rf->energy_kcal ? round($rf->energy_kcal) : '--' }} kcal
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

    </div>

</div>
@endsection
