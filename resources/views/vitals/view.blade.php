@extends('layouts.app')

@section('title', 'Vital Record')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col justify-between space-y-3 sm:flex-row sm:items-center sm:space-y-0">
        <div>
            <div class="flex items-center space-x-2">
                <span class="badge rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-semibold text-primary dark:bg-accent/15 dark:text-accent-light">
                    Vital Log
                </span>
                <span class="text-xs text-slate-400 dark:text-navy-300">&bull;</span>
                <a href="{{ route('vital.index') }}" class="text-xs font-semibold text-slate-500 hover:text-primary dark:text-navy-300 dark:hover:text-accent-light">
                    &larr; Back to Vitals
                </a>
            </div>
            <h2 class="mt-1 text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl">
                {{ $vital->vital ? $vital->vital->name : 'Vital Record' }}
            </h2>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('vital.edit', $vital->id) }}"
                class="btn h-8 rounded-lg border border-slate-300 px-3 text-xs font-medium text-slate-700 hover:bg-slate-100 dark:border-navy-500 dark:text-navy-100 dark:hover:bg-navy-600">
                Edit Record
            </a>
            <form action="{{ route('vital.destroy', $vital->id) }}"
                method="POST"
                onsubmit="return confirm('Are you sure you want to delete this vital record?');"
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

    <!-- Main Detail Card -->
    <div class="card rounded-xl border border-slate-150 bg-white p-6 shadow-soft dark:border-navy-600 dark:bg-navy-700 text-center">
        <span class="text-tiny uppercase font-semibold text-slate-400 dark:text-navy-300">Date Measured</span>
        <h4 class="mt-1 text-base font-bold text-slate-800 dark:text-navy-100">
            {{ \Carbon\Carbon::parse($vital->date_measured)->format('F d, Y') }}
        </h4>

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="rounded-xl border border-slate-150 bg-slate-50 p-5 dark:border-navy-600 dark:bg-navy-800/40">
                <span class="text-tiny uppercase font-semibold text-slate-400 dark:text-navy-300">
                    {{ $vital->vital ? $vital->vital->name : 'Measurement' }}
                </span>
                <div class="mt-2 text-3xl font-bold text-primary dark:text-accent-light">
                    {{ $vital->value }} <span class="text-sm font-normal text-slate-400 dark:text-navy-300">{{ $vital->vital && $vital->vital->unit ? $vital->vital->unit->symbol : '' }}</span>
                </div>
                @if($vital->vital && $vital->vital->description)
                    <p class="mt-2 text-xs text-slate-400 dark:text-navy-300">{{ $vital->vital->description }}</p>
                @endif
            </div>

            @if($vital->vital && $vital->vital->slug === 'weight')
                @php
                    $heightVital = Auth::user()->latestVital('height');
                    $bmi = null;
                    $bmiClass = 'text-success';
                    $badgeClass = 'bg-success/10 text-success dark:bg-success/15';
                    $bmiLabel = 'Healthy';
                    if ($heightVital && $heightVital->value > 0) {
                        $heightM = $heightVital->value / 100;
                        $bmi = round($vital->value / ($heightM * $heightM), 1);
                        if ($bmi < 18.5) { $bmiClass = 'text-info'; $badgeClass = 'bg-info/10 text-info'; $bmiLabel = 'Underweight'; }
                        elseif ($bmi >= 25 && $bmi < 30) { $bmiClass = 'text-warning'; $badgeClass = 'bg-warning/10 text-warning'; $bmiLabel = 'Overweight'; }
                        elseif ($bmi >= 30) { $bmiClass = 'text-error'; $badgeClass = 'bg-error/10 text-error'; $bmiLabel = 'Obese'; }
                    }
                @endphp
                @if($bmi)
                    <div class="rounded-xl border border-slate-150 bg-slate-50 p-5 dark:border-navy-600 dark:bg-navy-800/40">
                        <span class="text-tiny uppercase font-semibold text-slate-400 dark:text-navy-300">
                            Estimated BMI (Height: {{ $heightVital->value }} cm)
                        </span>
                        <div class="mt-2 text-3xl font-bold {{ $bmiClass }}">
                            {{ $bmi }}
                        </div>
                        <span class="mt-2 inline-block rounded-full px-2.5 py-0.5 text-tiny font-bold {{ $badgeClass }}">
                            {{ $bmiLabel }}
                        </span>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
