@extends('layouts.app')

@section('title', 'Vitals')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col justify-between space-y-3 sm:flex-row sm:items-center sm:space-y-0">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl uppercase">
                VITALS LOG
            </h2>
            <p class="text-xs text-slate-500 dark:text-navy-300">
                Track your weight, body metrics, and cardiovascular vitals over time.
            </p>
        </div>
        <div>
            <a href="{{ route('vital.create') }}"
                class="btn space-x-2 rounded-xl bg-primary px-4 py-2 text-xs font-semibold text-white shadow-soft hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Record Vitals</span>
            </a>
        </div>
    </div>



    <!-- Import & Export Card -->
    <div class="card rounded-xl border border-slate-150 bg-white p-5 shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-navy-200 mb-4">
            Import & Export Data
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Export Block -->
            <div class="space-y-2">
                <h5 class="text-sm font-bold text-slate-800 dark:text-navy-100">Export Vitals Data</h5>
                <p class="text-xs text-slate-500 dark:text-navy-300">
                    Download your full history of logged vitals as a JSON file.
                </p>
                <div class="pt-2">
                    <a href="{{ route('vital.export') }}"
                        class="btn h-9 space-x-2 rounded-lg border border-primary/30 px-4 text-xs font-semibold text-primary hover:bg-primary/10 dark:border-accent/30 dark:text-accent-light dark:hover:bg-accent/10 inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span>Export JSON</span>
                    </a>
                </div>
            </div>

            <!-- Import Block -->
            <div class="space-y-2 pt-4 md:pt-0">
                <h5 class="text-sm font-bold text-slate-800 dark:text-navy-100">Import Vitals Data</h5>
                <p class="text-xs text-slate-500 dark:text-navy-300">
                    Upload a previously exported JSON vitals file.
                </p>
                <form action="{{ route('vital.import') }}" method="POST" enctype="multipart/form-data" class="pt-2 flex flex-col sm:flex-row items-start sm:items-center gap-2">
                    @csrf
                    <input type="file"
                        name="vitals_file"
                        accept=".json,.txt"
                        required
                        class="block w-full text-xs text-slate-500 dark:text-navy-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 dark:file:bg-navy-600 dark:file:text-navy-100">
                    <button type="submit"
                        class="btn h-8 shrink-0 rounded-lg bg-primary px-4 text-xs font-semibold text-white shadow-soft hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                        Import JSON
                    </button>
                </form>
                @error('vitals_file')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- History Card -->
    <div class="card rounded-xl border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <div class="border-b border-slate-150 px-5 py-4 dark:border-navy-600 text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-navy-200">
            Vital History Log
        </div>

        @if(count($vitals) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-slate-150 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:border-navy-600 dark:text-navy-300">
                            <th class="px-5 py-3">Date Measured</th>
                            <th class="px-5 py-3">Vital Metric</th>
                            <th class="px-5 py-3">Value</th>
                            <th class="px-5 py-3">Unit</th>
                            <th class="px-5 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-150 dark:divide-navy-600">
                        @foreach($vitals as $userVital)
                            @php
                                $date_measured = \Carbon\Carbon::parse($userVital->date_measured);
                                $vitalName = $userVital->vital ? $userVital->vital->name : 'Unknown Metric';
                                $unitSymbol = $userVital->vital && $userVital->vital->unit ? $userVital->vital->unit->symbol : '-';
                            @endphp
                            <tr class="hover:bg-slate-50 dark:hover:bg-navy-800/50 transition-colors">
                                <td class="px-5 py-3.5 text-xs font-semibold text-slate-800 dark:text-navy-100">
                                    {{ $date_measured->format('d M, Y') }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="badge rounded bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 dark:bg-navy-600 dark:text-navy-200">
                                        {{ $vitalName }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-xs font-bold text-primary dark:text-accent-light">
                                    {{ $userVital->value }}
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-400 dark:text-navy-300">
                                    {{ $unitSymbol }}
                                </td>
                                <td class="px-5 py-3.5 text-right space-x-1.5">
                                    <a href="{{ route('vital.show', $userVital->id) }}"
                                        class="btn h-7 rounded-lg border border-slate-300 px-2.5 text-tiny font-medium text-slate-700 hover:bg-slate-100 dark:border-navy-500 dark:text-navy-200 dark:hover:bg-navy-600">
                                        View
                                    </a>
                                    <a href="{{ route('vital.edit', $userVital->id) }}"
                                        class="btn h-7 rounded-lg border border-primary/30 px-2.5 text-tiny font-medium text-primary hover:bg-primary/10 dark:border-accent/30 dark:text-accent-light dark:hover:bg-accent/10">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="mx-auto size-12 text-slate-300 dark:text-navy-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <h4 class="mt-3 text-base font-semibold text-slate-700 dark:text-navy-100">No vitals logged yet</h4>
                <p class="mt-1 text-xs text-slate-400 dark:text-navy-300 mb-4">Log your weight, height, and health metrics to track fitness trends.</p>
                <a href="{{ route('vital.create') }}"
                    class="btn h-8 rounded-lg bg-primary px-4 text-xs font-semibold text-white shadow-soft hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                    Record First Entry
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
