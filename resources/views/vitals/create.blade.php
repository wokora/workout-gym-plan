@extends('layouts.app')

@section('title', 'Record Vital')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl">
                Record Vital
            </h2>
            <p class="text-xs text-slate-500 dark:text-navy-300">
                Log health and body measurements to track your fitness trends.
            </p>
        </div>
        <a href="{{ route('vital.index') }}"
            class="text-xs font-semibold text-primary hover:text-primary-focus dark:text-accent-light dark:hover:text-accent">
            &larr; Back to Log
        </a>
    </div>

    <!-- Card -->
    <div class="card rounded-xl border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <form action="{{ route('vital.store') }}" method="POST" id="recordVitalForm">
            @csrf
            <div class="p-5 sm:p-6 space-y-6">
                <!-- Date Picker -->
                <div>
                    <label for="date" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-navy-200 mb-1.5">
                        Date Measured <span class="text-error">*</span>
                    </label>
                    <input type="date"
                        name="date"
                        id="date"
                        value="{{ old('date', date('Y-m-d')) }}"
                        class="form-input max-w-xs rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-xs sm:text-sm text-slate-800 placeholder:text-slate-400 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent @error('date') border-error @enderror"
                        required>
                    @error('date')
                        <p class="mt-1 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Vital Metric Selector -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-navy-200">
                            Select Vital Metric <span class="text-error">*</span>
                        </label>
                        <span class="text-tiny text-slate-400 dark:text-navy-300">Choose the metric to record</span>
                    </div>

                    @php
                        $selectedVitalId = old('vital_id', $vitals->first() ? $vitals->first()->id : null);
                    @endphp
                    <div class="vital-selector-grid grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                        @foreach($vitals as $vital)
                            @php
                                $htmlType = $vital->inputType && in_array($vital->inputType->code, ['decimal', 'integer']) ? 'number' : 'text';
                                $step = $vital->inputType ? ($vital->inputType->step ?? 'any') : 'any';
                                $lastVal = $latestValues[$vital->id] ?? null;
                                $unitSym = $vital->unit ? $vital->unit->symbol : '';
                            @endphp
                            <label class="relative flex cursor-pointer flex-col justify-between rounded-lg border border-slate-200 p-3 transition-all hover:border-primary/50 dark:border-navy-500 dark:hover:border-accent/50 has-[:checked]:border-primary has-[:checked]:bg-primary/5 dark:has-[:checked]:border-accent dark:has-[:checked]:bg-accent/10">
                                <input type="radio"
                                    name="vital_id"
                                    id="vitalRadio{{ $vital->id }}"
                                    value="{{ $vital->id }}"
                                    data-name="{{ $vital->name }}"
                                    data-unit="{{ $unitSym }}"
                                    data-type="{{ $htmlType }}"
                                    data-step="{{ $step }}"
                                    data-min="{{ $vital->min_value ?? '' }}"
                                    data-max="{{ $vital->max_value ?? '' }}"
                                    data-last="{{ $lastVal ?? '' }}"
                                    data-desc="{{ $vital->description ?? '' }}"
                                    class="sr-only"
                                    {{ $selectedVitalId == $vital->id ? 'checked' : '' }}>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-800 dark:text-navy-100">{{ $vital->name }}</span>
                                    @if($unitSym)
                                        <span class="badge rounded bg-slate-100 px-1.5 py-0.5 text-tiny font-semibold text-slate-600 dark:bg-navy-600 dark:text-navy-200">
                                            {{ $unitSym }}
                                        </span>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('vital_id')
                        <p class="mt-1.5 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dynamic Value Input Card -->
                <div class="rounded-xl border border-slate-150 bg-slate-50 p-4 sm:p-5 dark:border-navy-600 dark:bg-navy-800/40 space-y-3">
                    <div class="flex items-center justify-between">
                        <label for="vitalValue" class="text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-navy-200">
                            Enter <span id="labelVitalName" class="text-primary dark:text-accent-light font-bold">Value</span>
                        </label>
                        <span id="lastValueWrapper" class="text-xs text-slate-400 dark:text-navy-300 hidden">
                            Last recorded: <button type="button" class="font-semibold text-primary underline dark:text-accent-light" id="btnUseLast"></button>
                        </span>
                    </div>

                    <div class="flex rounded-lg shadow-sm">
                        <input type="number"
                            step="any"
                            name="value"
                            id="vitalValue"
                            value="{{ old('value') }}"
                            placeholder="Key in measurement value..."
                            class="form-input flex-1 rounded-l-lg border border-r-0 border-slate-300 bg-white px-4 py-2.5 text-base sm:text-lg font-semibold text-slate-800 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent @error('value') border-error @enderror"
                            required
                            autofocus>
                        <span id="badgeVitalUnit" class="inline-flex items-center rounded-r-lg border border-l-0 border-slate-300 bg-slate-100 px-4 text-sm font-bold text-slate-600 dark:border-navy-450 dark:bg-navy-600 dark:text-navy-200">
                            --
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-tiny text-slate-400 dark:text-navy-300">
                        <span id="descVitalText"></span>
                        <span id="rangeVitalText"></span>
                    </div>

                    @error('value')
                        <p class="text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-between border-t border-slate-150 px-5 py-4 dark:border-navy-600">
                <a href="{{ route('vital.index') }}"
                    class="btn h-9 rounded-lg border border-slate-300 px-4 text-xs font-semibold text-slate-700 hover:bg-slate-100 dark:border-navy-500 dark:text-navy-200 dark:hover:bg-navy-600">
                    Cancel
                </a>
                <div class="flex items-center space-x-2">
                    <button type="submit"
                        name="action"
                        value="save_and_new"
                        class="btn h-9 rounded-lg border border-primary/30 px-4 text-xs font-semibold text-primary hover:bg-primary/10 dark:border-accent/30 dark:text-accent-light dark:hover:bg-accent/10">
                        Save & Record Another
                    </button>
                    <button type="submit"
                        name="action"
                        value="save"
                        class="btn h-9 rounded-lg bg-primary px-5 text-xs font-semibold text-white shadow-soft hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                        Save Vital
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const radioButtons = document.querySelectorAll('input[name="vital_id"]');
        const labelVitalName = document.getElementById('labelVitalName');
        const badgeVitalUnit = document.getElementById('badgeVitalUnit');
        const vitalValueInput = document.getElementById('vitalValue');
        const descVitalText = document.getElementById('descVitalText');
        const rangeVitalText = document.getElementById('rangeVitalText');
        const lastValueWrapper = document.getElementById('lastValueWrapper');
        const btnUseLast = document.getElementById('btnUseLast');

        function updateSelectedVital(radio) {
            if (!radio) return;

            const name = radio.dataset.name || 'Value';
            const unit = radio.dataset.unit || '';
            const step = radio.dataset.step || 'any';
            const type = radio.dataset.type || 'number';
            const min = radio.dataset.min || '';
            const max = radio.dataset.max || '';
            const last = radio.dataset.last || '';
            const desc = radio.dataset.desc || '';

            labelVitalName.textContent = name;
            badgeVitalUnit.textContent = unit || '-';

            vitalValueInput.type = type;
            vitalValueInput.step = step;
            vitalValueInput.placeholder = last ? `e.g. ${last}` : `Enter ${name.toLowerCase()}`;

            if (min) vitalValueInput.min = min;
            else vitalValueInput.removeAttribute('min');

            if (max) vitalValueInput.max = max;
            else vitalValueInput.removeAttribute('max');

            descVitalText.textContent = desc;

            if (min && max) {
                rangeVitalText.textContent = `Valid range: ${min} - ${max} ${unit}`;
            } else {
                rangeVitalText.textContent = '';
            }

            if (last) {
                lastValueWrapper.classList.remove('hidden');
                btnUseLast.textContent = `${last} ${unit}`;
                btnUseLast.onclick = function () {
                    vitalValueInput.value = last;
                    vitalValueInput.focus();
                };
            } else {
                lastValueWrapper.classList.add('hidden');
            }
        }

        radioButtons.forEach(radio => {
            radio.addEventListener('change', function () {
                updateSelectedVital(this);
                vitalValueInput.focus();
            });
        });

        const checkedRadio = document.querySelector('input[name="vital_id"]:checked') || radioButtons[0];
        if (checkedRadio) {
            updateSelectedVital(checkedRadio);
        }
    });
</script>
@endsection
