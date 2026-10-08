@extends('layouts.app')

@section('title', 'Add Food Entry')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl">
                Add Food Entry
            </h2>
            <p class="text-xs text-slate-500 dark:text-navy-300">
                Adding foods to <span class="font-semibold text-slate-700 dark:text-navy-100">{{ $day->name ?? 'Day' }}</span> in {{ $meal->name }}
            </p>
        </div>
        <a href="{{ route('meal.day.show', [$meal->id, $day->id]) }}"
            class="text-xs font-semibold text-primary hover:text-primary-focus dark:text-accent-light dark:hover:text-accent">
            &larr; Back to Day
        </a>
    </div>

    <!-- Card -->
    <div class="card rounded-xl border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <form action="{{ route('meal.day.entry.store', [$meal->id, $day->id]) }}" method="POST">
            @csrf
            <div class="p-5 sm:p-6 space-y-6">
                <!-- Meal Type Selector -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-navy-200 mb-2">
                        Meal Type <span class="text-error">*</span>
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                        @foreach(\App\Models\Meal\Type::get() as $type)
                            <label class="relative flex cursor-pointer rounded-lg border border-slate-200 p-3 text-center transition-all hover:border-primary/50 dark:border-navy-500 dark:hover:border-accent/50 has-[:checked]:border-primary has-[:checked]:bg-primary/5 dark:has-[:checked]:border-accent dark:has-[:checked]:bg-accent/10">
                                <input type="radio"
                                    name="meal_type"
                                    value="{{ $type->id }}"
                                    id="mealType{{ $type->id }}"
                                    class="sr-only"
                                    @if(old('meal_type') == $type->id) checked @endif
                                    required>
                                <span class="w-full text-xs font-semibold text-slate-700 dark:text-navy-100">{{ $type->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('meal_type')
                        <p class="mt-1.5 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Food Search Input -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-navy-200 mb-2">
                        Search & Add Foods <span class="text-error">*</span>
                    </label>
                    <div class="flex space-x-2">
                        <div class="relative flex-1">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 dark:text-navy-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input type="text"
                                id="foodSearchInput"
                                placeholder="Search by food name, brand, or ingredient..."
                                class="form-input w-full rounded-lg border border-slate-300 bg-transparent pl-10 pr-3 py-2 text-xs sm:text-sm text-slate-800 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">
                        </div>
                        <button type="button"
                            onclick="searchFoods()"
                            class="btn h-9 rounded-lg bg-primary px-4 text-xs font-semibold text-white shadow-soft hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                            Search
                        </button>
                    </div>

                    <!-- Search Results Dropdown/Box -->
                    <div id="foodResults" class="mt-2 max-h-60 overflow-y-auto space-y-1.5 empty:hidden"></div>
                </div>

                <!-- Selected Foods Chips -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-navy-200 mb-2">
                        Selected Foods
                    </label>
                    <div id="selectedFoods" class="min-h-14 rounded-lg border border-dashed border-slate-200 bg-slate-50 p-3 dark:border-navy-500 dark:bg-navy-800/40 flex flex-wrap items-center gap-2">
                        <span id="noFoodsMsg" class="text-xs text-slate-400 dark:text-navy-300 mx-auto py-2">Search and select foods to add them to this entry</span>
                    </div>
                    @error('foods')
                        <p class="mt-1.5 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description / Notes -->
                <div>
                    <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-navy-200 mb-1.5">
                        Notes / Portion Description (Optional)
                    </label>
                    <textarea name="description"
                        id="description"
                        rows="2"
                        placeholder="e.g. Post-workout meal, 2 boiled eggs and 1 slice whole wheat bread"
                        class="form-textarea w-full rounded-lg border border-slate-300 bg-transparent p-3 text-slate-800 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent @error('description') border-error @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Card Footer -->
            <div class="flex items-center justify-end space-x-3 border-t border-slate-150 px-5 py-4 dark:border-navy-600">
                <a href="{{ route('meal.day.show', [$meal->id, $day->id]) }}"
                    class="btn h-9 rounded-lg border border-slate-300 px-4 text-xs font-semibold text-slate-700 hover:bg-slate-100 dark:border-navy-500 dark:text-navy-200 dark:hover:bg-navy-600">
                    Cancel
                </a>
                <button type="submit"
                    class="btn h-9 rounded-lg bg-primary px-5 text-xs font-semibold text-white shadow-soft hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                    Add to Day
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const selectedFoods = {};

    function searchFoods() {
        const query = document.getElementById('foodSearchInput').value.trim();
        if (!query) return;

        fetch('{{ route('food.search') }}?q=' + encodeURIComponent(query))
            .then(r => r.json())
            .then(foods => {
                const container = document.getElementById('foodResults');
                if (foods.length === 0) {
                    container.innerHTML = '<p class="text-center text-xs text-slate-400 py-3 dark:text-navy-300">No foods found matching query.</p>';
                    return;
                }
                container.innerHTML = foods.map(f => {
                    const selected = selectedFoods[f.id] !== undefined;
                    return `
                        <div class="flex items-center justify-between rounded-lg border border-slate-200 p-2.5 transition-colors cursor-pointer dark:border-navy-600 ${selected ? 'bg-primary/10 border-primary/30 dark:bg-accent/15 dark:border-accent/40' : 'bg-white hover:bg-slate-50 dark:bg-navy-700 dark:hover:bg-navy-600'}"
                             data-id="${f.id}"
                             data-name="${f.name.replace(/'/g, "\\'")}"
                             onclick="toggleFoodResult(this)">
                            <div class="min-w-0 pr-3">
                                <div class="text-xs font-bold text-slate-800 dark:text-navy-100 truncate flex items-center space-x-2">
                                    <span>${f.name}</span>
                                    ${selected ? '<span class="badge rounded bg-primary/20 px-1.5 py-0.5 text-tiny font-bold text-primary dark:bg-accent/30 dark:text-accent-light">Added</span>' : ''}
                                </div>
                                <div class="text-tiny text-slate-400 dark:text-navy-300 mt-0.5 space-x-1.5">
                                    <span>${f.energy_kcal} kcal</span>
                                    ${f.protein ? '<span class="text-info font-medium">&bull; ' + f.protein + 'g P</span>' : ''}
                                    ${f.carbs ? '<span class="text-warning font-medium">&bull; ' + f.carbs + 'g C</span>' : ''}
                                    ${f.fat ? '<span class="text-error font-medium">&bull; ' + f.fat + 'g F</span>' : ''}
                                </div>
                            </div>
                            <button type="button" class="btn size-7 shrink-0 rounded-full p-0 text-xs font-bold ${selected ? 'bg-error/15 text-error hover:bg-error hover:text-white' : 'bg-primary/15 text-primary hover:bg-primary hover:text-white dark:bg-accent/20 dark:text-accent-light dark:hover:bg-accent'}">
                                ${selected ? '&times;' : '+'}
                            </button>
                        </div>`;
                }).join('');
            });
    }

    function toggleFoodResult(el) {
        const id = el.dataset.id;
        const name = el.dataset.name;
        if (selectedFoods[id]) {
            removeFood(id);
        } else {
            addFood(id, name);
        }
        searchFoods();
    }

    function addFood(id, name) {
        if (selectedFoods[id]) return;
        selectedFoods[id] = name;
        renderChips();
    }

    function removeFood(id) {
        delete selectedFoods[id];
        renderChips();
    }

    function renderChips() {
        const container = document.getElementById('selectedFoods');
        const ids = Object.keys(selectedFoods);
        const msg = document.getElementById('noFoodsMsg');

        if (ids.length === 0) {
            msg.style.display = '';
            container.querySelectorAll('.food-chip, .food-hidden-input').forEach(el => el.remove());
            return;
        }

        msg.style.display = 'none';
        container.querySelectorAll('.food-chip, .food-hidden-input').forEach(el => el.remove());

        ids.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'foods[]';
            input.value = id;
            input.className = 'food-hidden-input';
            container.appendChild(input);

            const chip = document.createElement('span');
            chip.className = 'food-chip inline-flex items-center space-x-2 rounded-full border border-primary/30 bg-primary/10 px-3 py-1 text-xs font-semibold text-primary dark:border-accent/40 dark:bg-accent/15 dark:text-accent-light';
            chip.innerHTML = `<span>${selectedFoods[id]}</span><button type="button" onclick="event.stopPropagation(); removeFood(${id})" class="size-4 shrink-0 rounded-full text-slate-400 hover:text-error dark:text-navy-300 font-bold leading-none">&times;</button>`;
            container.appendChild(chip);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('foodSearchInput').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchFoods();
            }
        });
    });
</script>
@endsection
