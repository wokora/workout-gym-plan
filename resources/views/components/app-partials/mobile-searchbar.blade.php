<div x-show="$store.breakpoints.isXs && $store.global.isSearchbarActive" x-transition:enter="easy-out transition-all"
    x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="easy-in transition-all" x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    class="fixed inset-0 z-100 flex flex-col bg-white dark:bg-navy-700 sm:hidden">
    <div class="flex items-center space-x-2 bg-slate-100 px-3 pt-2 pb-2 dark:bg-navy-800">
        <button
            class="btn -ml-1.5 size-7 shrink-0 rounded-full p-0 text-slate-600 hover:bg-slate-300/20 active:bg-slate-300/25 dark:text-navy-100 dark:hover:bg-navy-300/20 dark:active:bg-navy-300/25"
            @click="$store.global.isSearchbarActive = false">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" stroke-width="1.5"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <span class="text-sm font-bold text-slate-800 dark:text-navy-100">Quick Navigation</span>
    </div>

    <div class="overflow-y-auto p-4 space-y-2">
        <a href="{{ route('index') }}"
            @click="$store.global.isSearchbarActive = false"
            class="flex items-center space-x-3 rounded-xl border border-slate-150 bg-white p-3 shadow-soft dark:border-navy-600 dark:bg-navy-800">
            <div class="avatar size-10">
                <div class="is-initial rounded-lg bg-primary/10 text-primary dark:bg-accent/20 dark:text-accent-light">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-800 dark:text-navy-100">Dashboard</p>
                <p class="text-xs text-slate-400 dark:text-navy-300">Overview and stats</p>
            </div>
        </a>

        <a href="{{ route('workout.index') }}"
            @click="$store.global.isSearchbarActive = false"
            class="flex items-center space-x-3 rounded-xl border border-slate-150 bg-white p-3 shadow-soft dark:border-navy-600 dark:bg-navy-800">
            <div class="avatar size-10">
                <div class="is-initial rounded-lg bg-secondary/10 text-secondary">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-800 dark:text-navy-100">Workouts</p>
                <p class="text-xs text-slate-400 dark:text-navy-300">Routines and training days</p>
            </div>
        </a>

        <a href="{{ route('meal.index') }}"
            @click="$store.global.isSearchbarActive = false"
            class="flex items-center space-x-3 rounded-xl border border-slate-150 bg-white p-3 shadow-soft dark:border-navy-600 dark:bg-navy-800">
            <div class="avatar size-10">
                <div class="is-initial rounded-lg bg-warning/10 text-warning">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-800 dark:text-navy-100">Meal Plans</p>
                <p class="text-xs text-slate-400 dark:text-navy-300">Nutrition and food entries</p>
            </div>
        </a>

        <a href="{{ route('exercise.index') }}"
            @click="$store.global.isSearchbarActive = false"
            class="flex items-center space-x-3 rounded-xl border border-slate-150 bg-white p-3 shadow-soft dark:border-navy-600 dark:bg-navy-800">
            <div class="avatar size-10">
                <div class="is-initial rounded-lg bg-info/10 text-info">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-800 dark:text-navy-100">Exercises</p>
                <p class="text-xs text-slate-400 dark:text-navy-300">Exercise database</p>
            </div>
        </a>

        <a href="{{ route('body.index') }}"
            @click="$store.global.isSearchbarActive = false"
            class="flex items-center space-x-3 rounded-xl border border-slate-150 bg-white p-3 shadow-soft dark:border-navy-600 dark:bg-navy-800">
            <div class="avatar size-10">
                <div class="is-initial rounded-lg bg-success/10 text-success">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-800 dark:text-navy-100">Body Sections</p>
                <p class="text-xs text-slate-400 dark:text-navy-300">Target muscle areas</p>
            </div>
        </a>

        <a href="{{ route('vital.index') }}"
            @click="$store.global.isSearchbarActive = false"
            class="flex items-center space-x-3 rounded-xl border border-slate-150 bg-white p-3 shadow-soft dark:border-navy-600 dark:bg-navy-800">
            <div class="avatar size-10">
                <div class="is-initial rounded-lg bg-error/10 text-error">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-800 dark:text-navy-100">Vitals Log</p>
                <p class="text-xs text-slate-400 dark:text-navy-300">Track body measurements</p>
            </div>
        </a>
    </div>
</div>
