@extends('layouts.app')

@section('title', 'Manage Entry')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>MANAGE FOOD ENTRY</span>
                    <a href="{{ route('meal.day.show', [$entry->meal_plan_day->meal_plan->id, $entry->meal_plan_day->id]) }}" class="text-primary font-weight-bold" style="font-size: 0.85rem; text-decoration: none;">&larr; Back to Day</a>
                </div>

                <form action="{{ route('meal.day.entry.update', [$entry->meal_plan_day->meal_plan->id, $entry->meal_plan_day->id, $entry->id]) }}" method="post">
                    @method('PATCH')
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-4">
                            <label>Meal Type</label>
                            <div class="meal-type-grid">
                                @foreach(\App\Models\Meal\Type::get() as $type)
                                    <div class="day-selector-item">
                                        <input type="radio" name="meal_type" value="{{ $type->id }}" id="mealType{{ $type->id }}" @if( old('meal_type', $entry->meal_type->id) == $type->id ) checked @endif>
                                        <label for="mealType{{ $type->id }}">{{ $type->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @error('meal_type')
                                <div class="text-danger mt-2" style="font-size: 80%">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <div style="position: relative; display: flex; align-items: center; background: rgba(15,23,42,0.8); border: 1px solid rgba(255,255,255,0.1); border-radius: 14px; overflow: hidden; transition: border-color 0.2s ease;" id="searchWrapper">
                                <svg width="18" height="18" fill="#64748b" viewBox="0 0 16 16" style="position: absolute; left: 16px; z-index: 1; pointer-events: none;">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                </svg>
                                <input type="text" id="foodSearchInput" placeholder="Search foods..."
                                    style="flex: 1; background: transparent; border: none; color: #f1f5f9; padding: 14px 16px 14px 46px; font-size: 0.95rem; outline: none; font-family: 'Inter', sans-serif;">
                                <button type="button" onclick="searchFoods()"
                                    style="background: #a3e635; border: none; color: #0b0f17; padding: 10px 22px; margin: 5px; border-radius: 8px; font-family: 'Outfit', sans-serif; font-weight: 600; font-size: 0.85rem; letter-spacing: 0.03em; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;"
                                    onmouseover="this.style.background='#b8f54a'; this.style.transform='scale(1.02)';"
                                    onmouseout="this.style.background='#a3e635'; this.style.transform='scale(1)';">
                                    Search
                                </button>
                            </div>
                        </div>

                        <div id="foodResults" class="mb-3"></div>

                        <div id="selectedFoods" class="d-flex flex-wrap align-items-start p-3 mb-4" style="min-height: 52px; background: rgba(15,23,42,0.6); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; gap: 0.5rem;">
                            <span id="noFoodsMsg" class="text-muted" style="font-size: 0.85rem; width: 100%; text-align: center; padding: 0.5rem 0;">Search and add foods above</span>
                        </div>
                        @error('foods')
                            <div class="text-danger mb-3" style="font-size: 80%; margin-top: -12px;">{{ $message }}</div>
                        @enderror

                        <div class="form-group mb-0">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="2" placeholder="e.g. Post-workout meal">{{ old('description', $entry->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <button type="button" onclick="document.getElementById('delete-entry-form').submit();" class="btn btn-secondary btn-sm" style="border-color: rgba(239, 68, 68, 0.4); color: #ef4444;">
                            Delete Entry
                        </button>
                        <div>
                            <a href="{{ route('meal.day.show', [$entry->meal_plan_day->meal_plan->id, $entry->meal_plan_day->id]) }}" class="btn btn-secondary btn-sm mr-2">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>

            <form id="delete-entry-form" action="{{ route('meal.day.entry.destroy', [$entry->meal_plan_day->meal_plan->id, $entry->meal_plan_day->id, $entry->id]) }}" method="post" onsubmit="return confirm('Are you sure you want to remove this entry from this day?');" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    <script>
        const selectedFoods = {};

        @foreach($entry->foods as $entryFood)
            selectedFoods[{{ $entryFood->food_id }}] = '{{ $entryFood->food->name }}';
        @endforeach

        function searchFoods() {
            const query = document.getElementById('foodSearchInput').value.trim();
            if (!query) return;

            fetch('{{ route('food.search') }}?q=' + encodeURIComponent(query))
                .then(r => r.json())
                .then(foods => {
                    const container = document.getElementById('foodResults');
                    if (foods.length === 0) {
                        container.innerHTML = '<p class="text-center text-muted py-3 mb-0">No foods found.</p>';
                        return;
                    }
                    container.innerHTML = foods.map(f => {
                        const selected = selectedFoods[f.id] !== undefined;
                        return `
                            <div class="food-result-item d-flex align-items-center px-3 py-2 mb-1"
                                 style="background: ${selected ? 'rgba(163,230,53,0.06)' : 'rgba(30,41,59,0.3)'}; border-left: 3px solid ${selected ? '#a3e635' : 'transparent'}; border-radius: 0 8px 8px 0; cursor: pointer; transition: all 0.15s ease; overflow: hidden;"
                                 data-id="${f.id}"
                                 data-name="${f.name.replace(/'/g, "\\'")}"
                                 onclick="toggleFoodResult(this)">
                                <div class="flex-grow-1" style="min-width: 0;">
                                    <div class="text-white font-weight-bold" style="font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        ${f.name}
                                        ${selected ? '<span class="badge ml-2" style="background: rgba(163,230,53,0.2); color: #a3e635; font-size: 0.65rem; font-weight: 600;">Selected</span>' : ''}
                                    </div>
                                    <div style="font-size: 0.72rem; color: #64748b;">
                                        ${f.energy_kcal} kcal/100g
                                        ${f.protein ? '<span class="mx-1" style="color: #38bdf8;">' + f.protein + 'g P</span>' : ''}
                                        ${f.carbs ? '<span class="mx-1" style="color: #fbbf24;">' + f.carbs + 'g C</span>' : ''}
                                        ${f.fat ? '<span class="mx-1" style="color: #fb7185;">' + f.fat + 'g F</span>' : ''}
                                    </div>
                                </div>
                                <div class="food-result-action ml-3 flex-shrink-0" style="width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.15s ease; font-size: 0.85rem; font-weight: 700;
                                    ${selected ? 'background: rgba(239,68,68,0.15); color: #ef4444;' : 'background: rgba(163,230,53,0.08); color: #a3e635;'}">
                                    ${selected ? '&times;' : '+'}
                                </div>
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
                chip.className = 'food-chip';
                chip.style.cssText = 'display: inline-flex; align-items: center; background: rgba(163,230,53,0.12); border: 1px solid rgba(163,230,53,0.3); border-radius: 20px; padding: 0.45rem 0.7rem 0.45rem 1rem; font-size: 0.85rem; color: #a3e635; font-weight: 500; transition: all 0.2s ease;';
                chip.innerHTML = `${selectedFoods[id]} <span onclick="event.stopPropagation(); removeFood(${id})" style="margin-left: 0.5rem; cursor: pointer; width: 20px; height: 20px; border-radius: 50%; background: rgba(239,68,68,0.2); color: #ef4444; display: inline-flex; align-items: center; justify-content: center; font-size: 0.85rem; line-height: 1; transition: all 0.15s ease;">&times;</span>`;
                container.appendChild(chip);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            renderChips();
            document.getElementById('foodSearchInput').addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchFoods();
                }
            });
        });
    </script>

    <style>
        #foodResults {
            max-height: 260px;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .food-result-item:hover {
            background: rgba(30,41,59,0.55) !important;
            border-left-color: rgba(163,230,53,0.4) !important;
            padding-left: 1.2rem !important;
        }
        .day-selector-item input[type="radio"] {
            position: absolute !important;
            opacity: 0 !important;
            width: 100% !important;
            height: 100% !important;
            cursor: pointer !important;
            z-index: 2 !important;
            left: 0 !important;
            top: 0 !important;
        }
        .meal-type-grid {
            display: flex !important;
            gap: 0.75rem !important;
        }
        .meal-type-grid .day-selector-item {
            flex: 1 !important;
        }
        .day-selector-item input[type="radio"]:checked + label {
            background: #1a2e1a !important;
            border-color: #a3e635 !important;
            color: #a3e635 !important;
            box-shadow: 0 0 12px rgba(163, 230, 53, 0.1) !important;
        }
    </style>
@endsection
