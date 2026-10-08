@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-4 sm:space-y-5 lg:space-y-6 -mt-2">

    <!-- 1. Page Header -->
    <div class="flex flex-col justify-between space-y-3 sm:flex-row sm:items-center sm:space-y-0">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl font-inter">
                Today
                <span class="text-xs font-normal text-slate-400 dark:text-navy-300 ml-2">
                    {{ \Carbon\Carbon::now()->format('l, M d, Y') }}
                </span>
            </h1>
            <p class="mt-0.5 text-xs text-slate-400 dark:text-navy-300">
                Welcome back, {{ $user ? $user->name : 'Athlete' }} &bull; Health biometrics, targets &amp; daily agenda.
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('vital.create') }}"
                class="btn space-x-1.5 rounded-lg border border-slate-300 px-3.5 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 dark:focus:bg-navy-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-primary dark:text-accent-light" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Record Vital</span>
            </a>
            <a href="{{ route('muscle-groups.index') }}"
                class="btn space-x-1.5 rounded-lg border border-slate-300 px-3.5 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 dark:focus:bg-navy-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-slate-400 dark:text-navy-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Anatomy Explorer</span>
            </a>
            <a href="{{ route('workout.create') }}"
                class="btn space-x-1.5 rounded-lg bg-primary px-3.5 py-1.5 text-xs font-medium text-white shadow-soft hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span>New Plan</span>
            </a>
        </div>
    </div>

    <!-- 2. Recommended Weight Range Section (Top Positioned) -->
    @if($minWeight && $idealWeight && $maxWeight)
        <div>
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm-plus font-medium tracking-wide text-slate-700 dark:text-navy-100">
                    Recommended Weight Range
                </h2>
                <span class="text-xs text-slate-400 dark:text-navy-300">
                    Standing Height: {{ $height ? $height . ' cm' : '170 cm' }}
                </span>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 sm:gap-5 lg:gap-6">
                <!-- Minimum Weight Card -->
                <div class="card justify-center p-4.5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xl font-bold font-inter text-slate-700 dark:text-navy-100">
                                {{ $minWeight }} <span class="text-xs font-normal text-slate-400 dark:text-navy-300">kg</span>
                            </p>
                            <p class="text-xs-plus line-clamp-1 text-slate-400 dark:text-navy-300 mt-0.5">
                                Minimum Healthy Weight
                            </p>
                        </div>
                        <div class="mask is-star flex size-10 shrink-0 items-center justify-center bg-info">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <div class="badge mt-2 space-x-1 bg-info/10 py-1 px-1.5 text-info dark:bg-info/15">
                            <span>BMI 18.5 Target</span>
                        </div>
                    </div>
                </div>

                <!-- Ideal Target Weight Card -->
                <div class="card justify-center p-4.5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xl font-bold font-inter text-slate-700 dark:text-navy-100">
                                {{ $idealWeight }} <span class="text-xs font-normal text-slate-400 dark:text-navy-300">kg</span>
                            </p>
                            <p class="text-xs-plus line-clamp-1 text-slate-400 dark:text-navy-300 mt-0.5">
                                Ideal Target Weight
                            </p>
                        </div>
                        <div class="mask is-star flex size-10 shrink-0 items-center justify-center bg-success">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <div class="badge mt-2 space-x-1 bg-success/10 py-1 px-1.5 text-success dark:bg-success/15">
                            <span>BMI 22.2 Optimal</span>
                        </div>
                    </div>
                </div>

                <!-- Maximum Weight Card -->
                <div class="card justify-center p-4.5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xl font-bold font-inter text-slate-700 dark:text-navy-100">
                                {{ $maxWeight }} <span class="text-xs font-normal text-slate-400 dark:text-navy-300">kg</span>
                            </p>
                            <p class="text-xs-plus line-clamp-1 text-slate-400 dark:text-navy-300 mt-0.5">
                                Maximum Healthy Weight
                            </p>
                        </div>
                        <div class="mask is-star flex size-10 shrink-0 items-center justify-center bg-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <div class="badge mt-2 space-x-1 bg-warning/10 py-1 px-1.5 text-warning dark:bg-warning/15">
                            <span>BMI 24.9 Ceiling</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- 3. Health & Biometrics Stats Cards (Reference Exact Style with Warnings) -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm-plus font-medium tracking-wide text-slate-700 dark:text-navy-100">
                Vital Signs &amp; Biometrics
            </h2>
            <a href="{{ route('vital.index') }}" class="border-b border-dotted border-current pb-0.5 text-xs-plus font-medium text-primary outline-hidden transition-colors duration-300 hover:text-primary/70 focus:text-primary/70 dark:text-accent-light dark:hover:text-accent-light/70 dark:focus:text-accent-light/70">
                Manage Vitals &rarr;
            </a>
        </div>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-6 sm:gap-5 lg:gap-6">
            <!-- Card 1: Weight -->
            <a href="{{ route('vital.index') }}" class="card justify-center p-4.5 transition-all duration-200 hover:scale-[1.01] block">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-bold font-inter text-slate-700 dark:text-navy-100 sm:text-xl">
                            {{ $weight ? $weight : '--' }} <span class="text-xs font-normal text-slate-400 dark:text-navy-300">kg</span>
                        </p>
                        <p class="text-xs-plus line-clamp-1 text-slate-400 dark:text-navy-300 mt-0.5">Weight</p>
                    </div>
                    <div class="mask is-star flex size-10 shrink-0 items-center justify-center bg-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="badge mt-2 space-x-1 {{ $weightStatus['badgeClass'] }} py-1 px-1.5">
                        @if($weightStatus['isWarning'])
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        @endif
                        <span class="truncate">{{ $weightStatus['label'] }}</span>
                    </div>
                </div>
            </a>

            <!-- Card 2: BMI -->
            <a href="{{ route('vital.index') }}" class="card justify-center p-4.5 transition-all duration-200 hover:scale-[1.01] block">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-bold font-inter sm:text-xl" style="color: {{ $bmiColor ?: '#10b981' }};">
                            {{ $bmi ?: '--' }}
                        </p>
                        <p class="text-xs-plus line-clamp-1 text-slate-400 dark:text-navy-300 mt-0.5">Body Mass (BMI)</p>
                    </div>
                    <div class="mask is-star flex size-10 shrink-0 items-center justify-center bg-info">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
                <div>
                    @if($bmiIsWarning)
                        <div class="badge mt-2 space-x-1 {{ $bmiBadgeClass }} py-1 px-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span class="truncate">{{ $bmiCategory }}</span>
                        </div>
                    @elseif($bmi)
                        <div class="badge mt-2 space-x-1 {{ $bmiBadgeClass }} py-1 px-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="truncate">{{ $bmiCategory }}</span>
                        </div>
                    @else
                        <div class="badge mt-2 space-x-1 bg-slate-100 py-1 px-1.5 text-slate-400 dark:bg-navy-600 dark:text-navy-300">
                            <span>Not Logged</span>
                        </div>
                    @endif
                </div>
            </a>

            <!-- Card 3: Blood Pressure -->
            <a href="{{ route('vital.index') }}" class="card justify-center p-4.5 transition-all duration-200 hover:scale-[1.01] block">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-bold font-inter text-slate-700 dark:text-navy-100 sm:text-xl">
                            @if($latestBpSystolic && $latestBpDiastolic)
                                {{ intval($latestBpSystolic->value) }}/{{ intval($latestBpDiastolic->value) }}
                                <span class="text-xs font-normal text-slate-400 dark:text-navy-300">mmHg</span>
                            @else
                                --
                            @endif
                        </p>
                        <p class="text-xs-plus line-clamp-1 text-slate-400 dark:text-navy-300 mt-0.5">Blood Pressure</p>
                    </div>
                    <div class="mask is-star flex size-10 shrink-0 items-center justify-center bg-error">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="badge mt-2 space-x-1 {{ $bpStatus['badgeClass'] }} py-1 px-1.5">
                        @if($bpStatus['isWarning'])
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        @elseif($latestBpSystolic)
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        @endif
                        <span class="truncate">{{ $bpStatus['label'] }}</span>
                    </div>
                </div>
            </a>

            <!-- Card 4: Resting Heart Rate -->
            <a href="{{ route('vital.index') }}" class="card justify-center p-4.5 transition-all duration-200 hover:scale-[1.01] block">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-bold font-inter text-slate-700 dark:text-navy-100 sm:text-xl">
                            {{ $latestHeartRate ? intval($latestHeartRate->value) : '--' }}
                            <span class="text-xs font-normal text-slate-400 dark:text-navy-300">bpm</span>
                        </p>
                        <p class="text-xs-plus line-clamp-1 text-slate-400 dark:text-navy-300 mt-0.5">
                            Heart Rate
                            @if($maxHeartRate)
                                <span class="text-[10px] text-slate-400 dark:text-navy-300 font-normal">&bull; Max {{ $maxHeartRate }}</span>
                            @endif
                        </p>
                    </div>
                    <div class="mask is-star flex size-10 shrink-0 items-center justify-center bg-rose-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="badge mt-2 space-x-1 {{ $hrStatus['badgeClass'] }} py-1 px-1.5">
                        @if($hrStatus['isWarning'])
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        @elseif($latestHeartRate)
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        @endif
                        <span class="truncate">{{ $hrStatus['label'] }}</span>
                    </div>
                </div>
            </a>

            <!-- Card 5: Body Fat Percentage -->
            <a href="{{ route('vital.index') }}" class="card justify-center p-4.5 transition-all duration-200 hover:scale-[1.01] block">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-bold font-inter text-slate-700 dark:text-navy-100 sm:text-xl">
                            {{ $latestBodyFat ? $latestBodyFat->value : '--' }}
                            <span class="text-xs font-normal text-slate-400 dark:text-navy-300">%</span>
                        </p>
                        <p class="text-xs-plus line-clamp-1 text-slate-400 dark:text-navy-300 mt-0.5">Body Fat</p>
                    </div>
                    <div class="mask is-star flex size-10 shrink-0 items-center justify-center bg-amber-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="badge mt-2 space-x-1 {{ $bfStatus['badgeClass'] }} py-1 px-1.5">
                        @if($bfStatus['isWarning'])
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        @elseif($latestBodyFat)
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        @endif
                        <span class="truncate">{{ $bfStatus['label'] }}</span>
                    </div>
                </div>
            </a>

            <!-- Card 6: Standing Height -->
            <a href="{{ route('vital.index') }}" class="card justify-center p-4.5 transition-all duration-200 hover:scale-[1.01] block">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-bold font-inter text-slate-700 dark:text-navy-100 sm:text-xl">
                            {{ $height ? $height : '--' }}
                            <span class="text-xs font-normal text-slate-400 dark:text-navy-300">cm</span>
                        </p>
                        <p class="text-xs-plus line-clamp-1 text-slate-400 dark:text-navy-300 mt-0.5">Height</p>
                    </div>
                    <div class="mask is-star flex size-10 shrink-0 items-center justify-center bg-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="badge mt-2 space-x-1 bg-success/10 py-1 px-1.5 text-success dark:bg-success/15">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="truncate">Baseline</span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- 4. Program Activity Overview (Workout & Nutrition Plans Library) -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:gap-6">
        <a href="{{ route('workout.index') }}" class="card justify-center p-4.5 transition-all duration-200 hover:scale-[1.01] block">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold font-inter text-slate-700 dark:text-navy-100">
                        {{ $workoutCount }}
                    </p>
                    <p class="text-xs-plus line-clamp-1 text-slate-400 dark:text-navy-300 mt-0.5">
                        Workout Programs in Library
                    </p>
                </div>
                <div class="mask is-star flex size-10 shrink-0 items-center justify-center bg-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
            <div>
                <div class="badge mt-2 space-x-1 bg-primary/10 py-1 px-1.5 text-primary dark:bg-accent/15 dark:text-accent-light">
                    <span>{{ $todayWorkoutDays->count() }} active routines today</span>
                </div>
            </div>
        </a>

        <a href="{{ route('meal.index') }}" class="card justify-center p-4.5 transition-all duration-200 hover:scale-[1.01] block">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold font-inter text-slate-700 dark:text-navy-100">
                        {{ $mealPlanCount }}
                    </p>
                    <p class="text-xs-plus line-clamp-1 text-slate-400 dark:text-navy-300 mt-0.5">
                        Nutrition Plans in Library
                    </p>
                </div>
                <div class="mask is-star flex size-10 shrink-0 items-center justify-center bg-info">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <div>
                <div class="badge mt-2 space-x-1 bg-info/10 py-1 px-1.5 text-info dark:bg-info/15">
                    <span>{{ $todayMealDays->count() }} active meal plans today</span>
                </div>
            </div>
        </a>
    </div>

    <!-- 5. Side-by-Side 2 Columns: Today's Workout & Today's Meals -->
    <div class="grid grid-cols-1 gap-4 sm:gap-5 lg:grid-cols-2 lg:gap-6 items-start">

        <!-- Column 1: Today's Workout Card Panel -->
        <div class="card px-4 pb-5 sm:px-5">
            <div class="my-3 flex h-8 items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="mask is-star flex size-8 shrink-0 items-center justify-center bg-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h1m16 0h1m-9-9v1m0 16v1m-6.36-2.64l.7-.7m12.02-12.02l.7-.7M5.64 5.64l.7.7m12.02 12.02l.7.7" />
                        </svg>
                    </div>
                    <h2 class="font-medium tracking-wide text-slate-700 dark:text-navy-100">
                        Today's Workout
                    </h2>
                </div>
                <a href="{{ route('workout.index') }}" class="border-b border-dotted border-current pb-0.5 text-xs-plus font-medium text-primary outline-hidden transition-colors duration-300 hover:text-primary/70 focus:text-primary/70 dark:text-accent-light dark:hover:text-accent-light/70 dark:focus:text-accent-light/70">
                    View All
                </a>
            </div>

            @if($todayWorkoutDays->isNotEmpty())
                <div class="space-y-4 pt-1">
                    @foreach($todayWorkoutDays as $wd)
                        @php
                            $totalSets = $wd->workout_exercise->sum('sets');
                            $totalReps = $wd->workout_exercise->sum(fn($we) => $we->sets * $we->reps);
                            $startTime = $wd->workout->start_time ? \Carbon\Carbon::parse($wd->workout->start_time) : null;
                            $endTime = $wd->workout->end_time ? \Carbon\Carbon::parse($wd->workout->end_time) : null;
                            $durationMin = ($startTime && $endTime) ? $startTime->diffInMinutes($endTime) : 60;
                        @endphp
                        <div class="rounded-xl border border-slate-150 bg-slate-50/50 p-4 dark:border-navy-600 dark:bg-navy-750">
                            <!-- Top Info: Day & Time -->
                            <div class="flex items-start justify-between space-x-2">
                                <div>
                                    <span class="badge rounded-full bg-primary/10 px-2.5 py-0.5 text-tiny font-bold uppercase tracking-wider text-primary dark:bg-accent/15 dark:text-accent-light">
                                        {{ $wd->day ? $wd->day->name : 'Scheduled' }}
                                    </span>
                                    <a href="{{ route('workout.show', $wd->workout->id) }}" class="mt-1.5 block text-base font-bold text-slate-800 line-clamp-1 hover:text-primary dark:text-navy-100 dark:hover:text-accent-light sm:text-lg transition-colors">
                                        {{ $wd->workout->name }}
                                    </a>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($startTime && $endTime)
                                        <span class="rounded bg-white px-2 py-1 text-tiny font-semibold text-slate-600 shadow-xs dark:bg-navy-800 dark:text-navy-300">
                                            {{ $startTime->format('H:i') }} &ndash; {{ $endTime->format('H:i') }}
                                        </span>
                                    @endif
                                    <a href="{{ route('workout.show', $wd->workout->id) }}" class="btn size-7 rounded-full p-0 text-slate-400 hover:bg-slate-200/60 hover:text-slate-700 dark:text-navy-300 dark:hover:bg-navy-600 dark:hover:text-navy-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <!-- 4-Metric Bar (clean, no divider lines) -->
                            <div class="mt-3.5 grid grid-cols-4 rounded-xl bg-white p-2.5 text-center shadow-xs dark:bg-navy-800">
                                <div class="px-1">
                                    <div class="text-sm font-bold text-slate-800 dark:text-navy-100">{{ $wd->workout_exercise->count() }}</div>
                                    <div class="text-tiny uppercase tracking-wider text-slate-400 dark:text-navy-300">Exercises</div>
                                </div>
                                <div class="px-1">
                                    <div class="text-sm font-bold text-primary dark:text-accent-light">{{ $totalSets }}</div>
                                    <div class="text-tiny uppercase tracking-wider text-slate-400 dark:text-navy-300">Sets</div>
                                </div>
                                <div class="px-1">
                                    <div class="text-sm font-bold text-info">{{ $totalReps }}</div>
                                    <div class="text-tiny uppercase tracking-wider text-slate-400 dark:text-navy-300">Reps</div>
                                </div>
                                <div class="px-1">
                                    <div class="text-sm font-bold text-slate-700 dark:text-navy-200">{{ $durationMin }}<span class="text-tiny">m</span></div>
                                    <div class="text-tiny uppercase tracking-wider text-slate-400 dark:text-navy-300">Duration</div>
                                </div>
                            </div>

                            <!-- Exercises Grid -->
                            <div class="mt-3.5 grid grid-cols-1 gap-2 sm:grid-cols-2">
                                @foreach($wd->workout_exercise as $we)
                                    <div class="rounded-lg border border-slate-150 bg-white p-2.5 shadow-xs dark:border-navy-600/60 dark:bg-navy-700">
                                        <div class="flex items-center justify-between space-x-2">
                                            <span class="truncate text-xs font-semibold text-primary dark:text-accent-light" title="{{ $we->exercise->name }}">
                                                {{ $we->exercise->name }}
                                            </span>
                                            <span class="shrink-0 rounded bg-slate-100 px-1.5 py-0.5 text-tiny font-bold text-slate-600 dark:bg-navy-600 dark:text-navy-200">
                                                {{ $we->sets }}&times;{{ $we->reps }}
                                            </span>
                                        </div>
                                        @if($we->exercise->body_section && $we->exercise->body_section->isNotEmpty())
                                            <div class="mt-1.5 flex flex-wrap gap-1">
                                                @foreach($we->exercise->body_section as $eb)
                                                    <span class="rounded bg-slate-100 px-1.5 py-0.5 text-tiny uppercase tracking-tight text-slate-500 dark:bg-navy-600 dark:text-navy-300">
                                                        {{ $eb->body_section->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-xl border-2 border-dashed border-slate-200 bg-transparent p-8 text-center dark:border-navy-600 my-2">
                    <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-slate-100 text-slate-400 dark:bg-navy-700 dark:text-navy-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h1m16 0h1m-9-9v1m0 16v1m-6.36-2.64l.7-.7m12.02-12.02l.7-.7M5.64 5.64l.7.7m12.02 12.02l.7.7" />
                        </svg>
                    </div>
                    <h3 class="mt-3 text-sm font-semibold text-slate-700 dark:text-navy-100">
                        No workout scheduled for today
                    </h3>
                    <p class="mt-1 text-xs text-slate-400 dark:text-navy-300">
                        Rest day or no active training routine assigned for today.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('workout.index') }}" class="btn h-8 rounded-lg bg-primary/10 px-3.5 text-xs font-semibold text-primary hover:bg-primary/20 dark:bg-accent/15 dark:text-accent-light">
                            Browse All Workouts
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Column 2: Today's Meals Card Panel -->
        <div class="card px-4 pb-5 sm:px-5">
            <div class="my-3 flex h-8 items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="mask is-star flex size-8 shrink-0 items-center justify-center bg-info">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                        </svg>
                    </div>
                    <h2 class="font-medium tracking-wide text-slate-700 dark:text-navy-100">
                        Today's Meals
                    </h2>
                </div>
                <a href="{{ route('meal.index') }}" class="border-b border-dotted border-current pb-0.5 text-xs-plus font-medium text-info outline-hidden transition-colors duration-300 hover:text-info/70 focus:text-info/70 dark:text-accent-light dark:hover:text-accent-light/70 dark:focus:text-accent-light/70">
                    View All
                </a>
            </div>

            @if($todayMealDays->isNotEmpty())
                <div class="space-y-4 pt-1">
                    @foreach($todayMealDays as $mpd)
                        @php
                            $totalEntries = $mpd->entry->count();
                            $totalFoods = $mpd->entry->sum(fn($e) => $e->foods->count());
                            $totalKcal = $mpd->entry->sum(fn($e) => $e->foods->sum(fn($f) => $f->food->energy_kcal ?? 0));
                            $totalProtein = $mpd->entry->sum(fn($e) => $e->foods->sum(fn($f) => $f->food->protein ?? 0));
                        @endphp
                        <div class="rounded-xl border border-slate-150 bg-slate-50/50 p-4 dark:border-navy-600 dark:bg-navy-750">
                            <!-- Top Info: Day & Title -->
                            <div class="flex items-start justify-between space-x-2">
                                <div>
                                    <span class="badge rounded-full bg-info/10 px-2.5 py-0.5 text-tiny font-bold uppercase tracking-wider text-info dark:bg-info/15">
                                        {{ $mpd->day ? $mpd->day->name : 'Scheduled' }}
                                    </span>
                                    <a href="{{ route('meal.show', $mpd->meal_plan->id) }}" class="mt-1.5 block text-base font-bold text-slate-800 line-clamp-1 hover:text-info dark:text-navy-100 dark:hover:text-info sm:text-lg transition-colors">
                                        {{ $mpd->meal_plan->name }}
                                    </a>
                                </div>
                                <a href="{{ route('meal.show', $mpd->meal_plan->id) }}" class="btn size-7 rounded-full p-0 text-slate-400 hover:bg-slate-200/60 hover:text-slate-700 dark:text-navy-300 dark:hover:bg-navy-600 dark:hover:text-navy-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>

                            <!-- 4-Metric Bar (clean, no divider lines) -->
                            <div class="mt-3.5 grid grid-cols-4 rounded-xl bg-white p-2.5 text-center shadow-xs dark:bg-navy-800">
                                <div class="px-1">
                                    <div class="text-sm font-bold text-slate-800 dark:text-navy-100">{{ $totalEntries }}</div>
                                    <div class="text-tiny uppercase tracking-wider text-slate-400 dark:text-navy-300">Meals</div>
                                </div>
                                <div class="px-1">
                                    <div class="text-sm font-bold text-info">{{ $totalFoods }}</div>
                                    <div class="text-tiny uppercase tracking-wider text-slate-400 dark:text-navy-300">Foods</div>
                                </div>
                                <div class="px-1">
                                    <div class="text-sm font-bold text-warning">{{ round($totalKcal) }}</div>
                                    <div class="text-tiny uppercase tracking-wider text-slate-400 dark:text-navy-300">Kcal</div>
                                </div>
                                <div class="px-1">
                                    <div class="text-sm font-bold text-slate-700 dark:text-navy-200">{{ round($totalProtein, 1) }}<span class="text-tiny">g</span></div>
                                    <div class="text-tiny uppercase tracking-wider text-slate-400 dark:text-navy-300">Protein</div>
                                </div>
                            </div>

                            <!-- Meal Entries Grid -->
                            <div class="mt-3.5 grid grid-cols-1 gap-2 sm:grid-cols-2">
                                @foreach($mpd->entry as $entry)
                                    <div class="rounded-lg border border-slate-150 bg-white p-2.5 shadow-xs dark:border-navy-600/60 dark:bg-navy-700">
                                        <div class="text-tiny font-bold uppercase tracking-wider text-info">
                                            {{ $entry->meal_type ? $entry->meal_type->name : 'Meal' }}
                                        </div>
                                        <div class="mt-1.5 space-y-1">
                                            @foreach($entry->foods as $ef)
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="truncate font-medium text-primary dark:text-accent-light" title="{{ $ef->food->name ?? '' }}">
                                                        {{ $ef->food->name ?? 'Food Item' }}
                                                    </span>
                                                    <span class="ml-2 shrink-0 text-tiny text-slate-400 dark:text-navy-300">
                                                        {{ $ef->food->energy_kcal ?? 0 }} kcal
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-xl border-2 border-dashed border-slate-200 bg-transparent p-8 text-center dark:border-navy-600 my-2">
                    <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-slate-100 text-slate-400 dark:bg-navy-700 dark:text-navy-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                        </svg>
                    </div>
                    <h3 class="mt-3 text-sm font-semibold text-slate-700 dark:text-navy-100">
                        No meals planned for today
                    </h3>
                    <p class="mt-1 text-xs text-slate-400 dark:text-navy-300">
                        Log nutrition according to your macro targets or create a meal plan.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('meal.index') }}" class="btn h-8 rounded-lg bg-info/10 px-3.5 text-xs font-semibold text-info hover:bg-info/20 dark:bg-info/15">
                            Browse Meal Plans
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- 6. Vitals Progress & Activity Overview Section (Grid 12-columns from dashboards-personal.html) -->
    <div class="grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6 items-start">

        <!-- Column 1 (8 cols): Progression Chart Card -->
        <div class="col-span-12 lg:col-span-8 space-y-4">
            <div class="card px-4 pb-5 sm:px-5">
                <div class="my-3 flex flex-col justify-between space-y-2 sm:flex-row sm:items-center sm:space-y-0">
                    <div class="flex items-center space-x-2">
                        <div class="mask is-star flex size-8 shrink-0 items-center justify-center bg-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16l4-6 4 3 4-8" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-medium tracking-wide text-slate-700 dark:text-navy-100">
                                Weight &amp; BMI Progression Over Time
                            </h2>
                            <p class="text-tiny text-slate-400 dark:text-navy-300">
                                Dual-axis trajectory tracking body weight (kg) and body mass index (BMI).
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3 text-tiny font-semibold">
                        <span class="inline-flex items-center space-x-1.5 text-primary dark:text-accent-light">
                            <span class="size-2 rounded-full bg-primary dark:bg-accent"></span>
                            <span>Weight (kg)</span>
                        </span>
                        <span class="inline-flex items-center space-x-1.5 text-info">
                            <span class="size-2 rounded-full bg-info"></span>
                            <span>BMI</span>
                        </span>
                    </div>
                </div>

                <!-- 4 Quick Metric Summary Tiles -->
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4 my-3">
                    <div class="rounded-xl border border-slate-150 bg-slate-50/60 p-3 text-center dark:border-navy-600 dark:bg-navy-750">
                        <div class="text-lg font-bold font-inter text-slate-800 dark:text-navy-100 sm:text-xl">
                            {{ $weight ? $weight : '--' }} <span class="text-xs font-normal text-slate-400 dark:text-navy-300">kg</span>
                        </div>
                        <div class="mt-1 text-tiny font-semibold uppercase tracking-wider text-slate-400 dark:text-navy-300">
                            Current
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-150 bg-slate-50/60 p-3 text-center dark:border-navy-600 dark:bg-navy-750">
                        @php
                            $changeColorClass = 'text-slate-500 dark:text-navy-300';
                            if ($weightChange < 0) {
                                $changeColorClass = 'text-success';
                            } elseif ($weightChange > 0) {
                                $changeColorClass = 'text-error';
                            }
                        @endphp
                        <div class="text-lg font-bold font-inter {{ $changeColorClass }} sm:text-xl">
                            {{ $weightChange > 0 ? '+' : '' }}{{ $weightChange }} <span class="text-xs font-normal">kg</span>
                        </div>
                        <div class="mt-1 text-tiny font-semibold uppercase tracking-wider text-slate-400 dark:text-navy-300">
                            Net Change
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-150 bg-slate-50/60 p-3 text-center dark:border-navy-600 dark:bg-navy-750">
                        <div class="text-lg font-bold font-inter text-info sm:text-xl">
                            {{ $vitalsCount }}
                        </div>
                        <div class="mt-1 text-tiny font-semibold uppercase tracking-wider text-slate-400 dark:text-navy-300">
                            Records Logged
                        </div>
                    </div>

                    <a href="{{ route('vital.create') }}" class="rounded-xl border border-primary/20 bg-primary/5 p-3 text-center transition-all duration-200 hover:bg-primary/10 dark:border-accent/20 dark:bg-accent/10 flex flex-col items-center justify-center">
                        <div class="text-lg font-bold text-primary dark:text-accent-light sm:text-xl leading-none">
                            +
                        </div>
                        <div class="mt-1 text-tiny font-semibold uppercase tracking-wider text-primary dark:text-accent-light">
                            Record Vital
                        </div>
                    </a>
                </div>

                <!-- ApexChart Component -->
                @if(count($chartLabels) > 0)
                    <div class="pt-2">
                        <div id="vitalsProgressApexChart" class="w-full"></div>
                    </div>

                    <script>
                        (function () {
                            function initVitalsChart() {
                                const chartElement = document.getElementById('vitalsProgressApexChart');
                                if (!chartElement || typeof ApexCharts === 'undefined') return;

                                if (chartElement._x_chart) {
                                    chartElement._x_chart.destroy();
                                }

                                const isDarkMode = document.documentElement.classList.contains('dark') || localStorage.getItem('_x_darkMode_on') === 'true';

                                const chartOptions = {
                                    series: [
                                        {
                                            name: 'Weight (kg)',
                                            type: 'area',
                                            data: {!! json_encode($chartWeightData) !!}
                                        },
                                        {
                                            name: 'BMI',
                                            type: 'line',
                                            data: {!! json_encode($chartBmiData) !!}
                                        }
                                    ],
                                    chart: {
                                        height: 280,
                                        type: 'line',
                                        toolbar: { show: false },
                                        parentHeightOffset: 0,
                                        fontFamily: 'Inter, sans-serif'
                                    },
                                    colors: ['#4467EF', '#38bdf8'],
                                    fill: {
                                        type: ['gradient', 'solid'],
                                        gradient: {
                                            shade: isDarkMode ? 'dark' : 'light',
                                            type: 'vertical',
                                            shadeIntensity: 0.5,
                                            gradientToColors: ['#4467EF'],
                                            inverseColors: false,
                                            opacityFrom: 0.25,
                                            opacityTo: 0.02,
                                            stops: [0, 100]
                                        }
                                    },
                                    stroke: {
                                        curve: 'smooth',
                                        width: [2.5, 2],
                                        dashArray: [0, 5]
                                    },
                                    markers: {
                                        size: [4, 4],
                                        strokeColors: isDarkMode ? '#0f172a' : '#ffffff',
                                        strokeWidth: 2,
                                        hover: { size: 6 }
                                    },
                                    xaxis: {
                                        categories: {!! json_encode($chartLabels) !!},
                                        axisBorder: { show: false },
                                        axisTicks: { show: false },
                                        labels: {
                                            style: {
                                                colors: isDarkMode ? '#94a3b8' : '#64748b',
                                                fontSize: '11px',
                                                fontFamily: 'Inter, sans-serif'
                                            }
                                        }
                                    },
                                    yaxis: [
                                        {
                                            title: {
                                                text: 'Weight (kg)',
                                                style: {
                                                    color: '#4467EF',
                                                    fontSize: '11px',
                                                    fontWeight: 600,
                                                    fontFamily: 'Inter, sans-serif'
                                                }
                                            },
                                            labels: {
                                                style: {
                                                    colors: '#4467EF',
                                                    fontSize: '11px',
                                                    fontFamily: 'Inter, sans-serif'
                                                },
                                                formatter: function (val) {
                                                    return val ? val.toFixed(1) : '';
                                                }
                                            }
                                        },
                                        {
                                            opposite: true,
                                            title: {
                                                text: 'BMI',
                                                style: {
                                                    color: '#38bdf8',
                                                    fontSize: '11px',
                                                    fontWeight: 600,
                                                    fontFamily: 'Inter, sans-serif'
                                                }
                                            },
                                            labels: {
                                                style: {
                                                    colors: '#38bdf8',
                                                    fontSize: '11px',
                                                    fontFamily: 'Inter, sans-serif'
                                                },
                                                formatter: function (val) {
                                                    return val ? val.toFixed(1) : '';
                                                }
                                            }
                                        }
                                    ],
                                    grid: {
                                        borderColor: isDarkMode ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)',
                                        strokeDashArray: 4,
                                        xaxis: { lines: { show: false } },
                                        yaxis: { lines: { show: true } }
                                    },
                                    tooltip: {
                                        theme: isDarkMode ? 'dark' : 'light',
                                        shared: true,
                                        intersect: false,
                                        y: {
                                            formatter: function (y, { seriesIndex }) {
                                                if (typeof y !== 'undefined' && y !== null) {
                                                    return seriesIndex === 0 ? y + ' kg' : y;
                                                }
                                                return y;
                                            }
                                        }
                                    },
                                    legend: { show: false }
                                };

                                const chart = new ApexCharts(chartElement, chartOptions);
                                chart.render();
                                chartElement._x_chart = chart;
                            }

                            if (document.readyState === 'loading') {
                                document.addEventListener('DOMContentLoaded', initVitalsChart);
                            } else {
                                initVitalsChart();
                            }
                        })();
                    </script>
                @else
                    <div class="py-12 text-center">
                        <svg class="mx-auto size-10 text-slate-300 dark:text-navy-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                        <p class="mt-2 text-xs text-slate-400 dark:text-navy-300">
                            No weight logs recorded yet to construct trend chart.
                        </p>
                        <a href="{{ route('vital.create') }}" class="btn mt-3 h-8 rounded-lg bg-primary px-3 text-xs font-semibold text-white shadow-soft dark:bg-accent">
                            Record First Vital
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Column 2 (4 cols): Calendar Widget from dashboards-personal.html -->
        <div class="col-span-12 lg:col-span-4">
            <div class="card p-4">
                <div class="space-y-1 text-center font-inter text-xs-plus">
                    <div class="flex items-center justify-between px-2 pb-3">
                        <p class="font-medium text-slate-700 dark:text-navy-100">
                            {{ $calendarMonthName }}
                        </p>
                        <span class="badge rounded-full bg-primary/10 px-2 py-0.5 text-tiny font-bold text-primary dark:bg-accent/15 dark:text-accent-light">
                            Active
                        </span>
                    </div>

                    <!-- Weekdays Row -->
                    <div class="grid grid-cols-7 pb-2">
                        <div class="text-tiny font-semibold text-primary dark:text-accent-light">SUN</div>
                        <div class="text-tiny font-semibold text-primary dark:text-accent-light">MON</div>
                        <div class="text-tiny font-semibold text-primary dark:text-accent-light">TUE</div>
                        <div class="text-tiny font-semibold text-primary dark:text-accent-light">WED</div>
                        <div class="text-tiny font-semibold text-primary dark:text-accent-light">THU</div>
                        <div class="text-tiny font-semibold text-primary dark:text-accent-light">FRI</div>
                        <div class="text-tiny font-semibold text-primary dark:text-accent-light">SAT</div>
                    </div>

                    <!-- Month Dates Grid -->
                    <div class="grid grid-cols-7 place-items-center gap-y-1">
                        <!-- Prev Month Padding Days -->
                        @foreach($prevMonthDays as $pDay)
                            <div class="flex size-7 items-center justify-center rounded-xl text-slate-400 dark:text-navy-400 text-tiny">
                                {{ $pDay }}
                            </div>
                        @endforeach

                        <!-- Current Month Days -->
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @if($d === $calendarTodayDay)
                                <button type="button" class="flex size-7 items-center justify-center rounded-xl bg-primary font-semibold text-white shadow-soft dark:bg-accent text-tiny">
                                    {{ $d }}
                                </button>
                            @else
                                <button type="button" class="flex size-7 items-center justify-center rounded-xl text-slate-700 hover:bg-primary/10 hover:text-primary dark:text-navy-100 dark:hover:bg-accent-light/10 dark:hover:text-accent-light text-tiny transition-colors">
                                    {{ $d }}
                                </button>
                            @endif
                        @endfor

                        <!-- Next Month Padding Days -->
                        @foreach($nextMonthDays as $nDay)
                            <div class="flex size-7 items-center justify-center rounded-xl text-slate-400 dark:text-navy-400 text-tiny">
                                {{ $nDay }}
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Daily Motivation / Consistency Banner -->
                <div class="mt-4 rounded-xl border border-slate-150 bg-slate-50/70 p-3 text-xs text-slate-600 dark:border-navy-600 dark:bg-navy-750 dark:text-navy-200">
                    <div class="flex items-center space-x-2 font-semibold text-slate-800 dark:text-navy-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-primary dark:text-accent-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span>Daily Consistency</span>
                    </div>
                    <p class="mt-1 text-tiny text-slate-400 dark:text-navy-300">
                        Regular vitals logging and routine adherence are the strongest predictors of physical progress.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
