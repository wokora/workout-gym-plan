@props([
    'muscles' => [],
    'interactive' => false,
    'selected' => null,
    'viewMode' => 'both',
    'showControls' => true,
    'showLegend' => true,
    'height' => 'h-72 sm:h-96',
    'title' => 'Target Muscle Groups',
    'subtitle' => null,
    'card' => true,
])

@php
    if ($muscles instanceof \Illuminate\Support\Collection) {
        $muscles = $muscles->all();
    } elseif (is_string($muscles)) {
        $muscles = array_filter(array_map('trim', explode(',', $muscles)));
    } elseif (!is_array($muscles)) {
        $muscles = [];
    }

    $allMuscleGroups = [
        'Chest', 'Back', 'Shoulders', 'Biceps', 'Triceps',
        'Quads', 'Hamstrings', 'Glutes', 'Calves', 'Abs', 'Forearms'
    ];
@endphp

<div x-data="muscleMap({
        activeMuscles: @js($muscles),
        selectedMuscle: @js($selected),
        viewMode: @js($viewMode),
        interactive: @js($interactive)
    })"
    class="muscle-map-container relative {{ $card ? 'card rounded-xl border border-slate-150 bg-white p-5 shadow-soft dark:border-navy-600 dark:bg-navy-700' : '' }}"
    data-interactive="{{ $interactive ? 'true' : 'false' }}">

    <!-- Header & Controls -->
    <div class="flex flex-col gap-3 pb-4 border-b border-slate-150 dark:border-navy-600 sm:flex-row sm:items-center sm:justify-between">
        <div>
            @if($title)
                <h3 class="font-semibold text-sm text-slate-800 dark:text-navy-100 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-primary dark:text-accent-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>{{ $title }}</span>
                </h3>
            @endif
            @if($subtitle)
                <p class="text-xs text-slate-500 dark:text-navy-300 mt-0.5">{{ $subtitle }}</p>
            @elseif($interactive)
                <p class="text-xs text-slate-500 dark:text-navy-300 mt-0.5">Click any muscle to filter exercises</p>
            @endif
        </div>

        @if($showControls)
            <div class="flex items-center gap-2 self-start sm:self-auto">
                @if($interactive)
                    <button type="button"
                        x-show="selectedMuscle"
                        x-transition
                        @click="clearSelection()"
                        class="btn h-7 rounded-lg border border-slate-200 px-2.5 text-tiny font-medium text-slate-600 hover:bg-slate-100 dark:border-navy-500 dark:text-navy-200 dark:hover:bg-navy-600">
                        Reset Selection
                    </button>
                @endif
                <div class="inline-flex rounded-lg bg-slate-100 p-0.5 dark:bg-navy-800">
                    <button type="button"
                        @click="setView('both')"
                        :class="viewMode === 'both' ? 'bg-white shadow-sm text-primary font-bold dark:bg-navy-600 dark:text-accent-light' : 'text-slate-500 hover:text-slate-800 dark:text-navy-300 dark:hover:text-navy-100'"
                        class="rounded-md px-2.5 py-1 text-xs font-semibold transition-all">
                        Both
                    </button>
                    <button type="button"
                        @click="setView('front')"
                        :class="viewMode === 'front' ? 'bg-white shadow-sm text-primary font-bold dark:bg-navy-600 dark:text-accent-light' : 'text-slate-500 hover:text-slate-800 dark:text-navy-300 dark:hover:text-navy-100'"
                        class="rounded-md px-2.5 py-1 text-xs font-semibold transition-all">
                        Front
                    </button>
                    <button type="button"
                        @click="setView('back')"
                        :class="viewMode === 'back' ? 'bg-white shadow-sm text-primary font-bold dark:bg-navy-600 dark:text-accent-light' : 'text-slate-500 hover:text-slate-800 dark:text-navy-300 dark:hover:text-navy-100'"
                        class="rounded-md px-2.5 py-1 text-xs font-semibold transition-all">
                        Back
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- Floating Interactive Tooltip -->
    <div x-show="tooltipVisible"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        :style="`left: ${tooltipPos.x}px; top: ${tooltipPos.y}px;`"
        class="pointer-events-none absolute z-30 -translate-x-1/2 rounded-md bg-slate-900/90 px-2.5 py-1 text-xs font-medium text-white shadow-xl backdrop-blur-sm dark:bg-navy-950/95"
        style="display: none;">
        <span x-text="tooltipText"></span>
        <div class="absolute left-1/2 top-full -translate-x-1/2 border-4 border-transparent border-t-slate-900/90 dark:border-t-navy-950/95"></div>
    </div>

    <!-- Anatomical Map Bodies -->
    <div class="flex items-center justify-center gap-4 sm:gap-12 overflow-hidden py-4"
        @mouseover="onMuscleEnter($event)"
        @mousemove="onMouseMove($event)"
        @mouseout="onMuscleLeave($event)"
        @click="onMuscleClick($event)">

        <!-- Front Body View -->
        <div x-show="viewMode === 'both' || viewMode === 'front'"
            x-transition
            class="relative flex flex-col items-center {{ $height }} w-full max-w-[210px] sm:max-w-[260px]">
            <span class="mb-2 text-tiny font-bold uppercase tracking-wider text-slate-400 dark:text-navy-300">Front Anatomy</span>
            <div class="size-full flex items-center justify-center">
                @include('components.muscle-map.front')
            </div>
        </div>

        <!-- Back Body View -->
        <div x-show="viewMode === 'both' || viewMode === 'back'"
            x-transition
            class="relative flex flex-col items-center {{ $height }} w-full max-w-[210px] sm:max-w-[260px]">
            <span class="mb-2 text-tiny font-bold uppercase tracking-wider text-slate-400 dark:text-navy-300">Back Anatomy</span>
            <div class="size-full flex items-center justify-center">
                @include('components.muscle-map.back')
            </div>
        </div>
    </div>

    <!-- Legend / Muscle Filter Badges -->
    @if($showLegend)
        <div class="mt-4 pt-4 border-t border-slate-150 dark:border-navy-600">
            <div class="flex items-center justify-between mb-2">
                <span class="text-tiny uppercase font-semibold text-slate-400 dark:text-navy-300">
                    {{ $interactive ? 'Filter By Muscle Group:' : 'Targeted Muscle Groups:' }}
                </span>
                @if($interactive)
                    <span class="text-tiny text-primary dark:text-accent-light font-medium" x-show="selectedMuscle">
                        Filtered: <span class="font-bold" x-text="selectedMuscle"></span>
                    </span>
                @endif
            </div>

            <div class="flex flex-wrap gap-1.5">
                @if($interactive)
                    @foreach($allMuscleGroups as $group)
                        <button type="button"
                            @click="toggleMuscle('{{ $group }}')"
                            @mouseenter="hoveredMuscle = '{{ $group }}'"
                            @mouseleave="hoveredMuscle = null"
                            :class="{
                                'bg-primary text-white shadow-sm dark:bg-accent': selectedMuscle === '{{ $group }}',
                                'bg-primary/10 text-primary dark:bg-accent/15 dark:text-accent-light': isMuscleHighlighted('{{ $group }}') && selectedMuscle !== '{{ $group }}',
                                'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-navy-800 dark:text-navy-200 dark:hover:bg-navy-650': !isMuscleHighlighted('{{ $group }}')
                            }"
                            class="badge cursor-pointer rounded-full px-3 py-1 text-xs font-semibold transition-all">
                            {{ $group }}
                        </button>
                    @endforeach
                @else
                    @forelse($muscles as $group)
                        <span
                            @mouseenter="hoveredMuscle = '{{ $group }}'"
                            @mouseleave="hoveredMuscle = null"
                            class="badge cursor-default rounded-full bg-primary/10 text-primary dark:bg-accent/15 dark:text-accent-light px-3 py-1 text-xs font-semibold hover:bg-primary/20 dark:hover:bg-accent/25 transition-all">
                            {{ $group }}
                        </span>
                    @empty
                        <span class="text-xs text-slate-400 dark:text-navy-300 italic">No muscle groups mapped yet.</span>
                    @endforelse
                @endif
            </div>
        </div>
    @endif
</div>
