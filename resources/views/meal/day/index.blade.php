@extends('layouts.app')

@section('title', 'Meal Day')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <div>
                    <h2 class="text-white mb-0">{{ $day->meal_plan->name }} &bull; <span class="text-primary text-glow">{{ $day->day->name }}</span></h2>
                    <a href="{{ route('meal.show', $day->meal_plan->id) }}" class="text-muted font-weight-bold" style="font-size: 0.85rem; text-decoration: none;">&larr; Back to Meal Plan</a>
                </div>
                <div>
                    <a href="{{ route('meal.day.entry.create', [$day->meal_plan->id, $day->id]) }}" class="btn btn-primary d-inline-flex align-items-center mt-2 mt-sm-0">
                        <svg class="mr-2" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        Add Food Entry
                    </a>
                </div>
            </div>

            @php
                $entries = $day->entry()->orderBy('number', 'ASC')->get();
                $grouped = $entries->groupBy('meal_type_id');
                $mealTypes = \App\Models\Meal\Type::orderBy('number')->get();
            @endphp

            @if($entries->count() > 0)
                <!-- Tabs -->
                <div class="meal-tabs d-flex mb-3" style="gap: 0.5rem;">
                    @foreach($mealTypes as $type)
                        @php $hasEntries = $grouped->has($type->id); @endphp
                        <button type="button" class="meal-tab-btn {{ $hasEntries ? '' : 'disabled-tab' }}"
                            data-tab="tab-{{ $type->id }}"
                            onclick="{{ $hasEntries ? "switchTab('tab-{$type->id}', this)" : '' }}"
                            style="flex: 1; background: rgba(30,41,59,0.3); border: none; color: {{ $hasEntries ? '#94a3b8' : '#475569' }}; padding: 0.6rem 0.5rem; font-family: 'Outfit', sans-serif; font-weight: 600; font-size: 0.82rem; letter-spacing: 0.02em; cursor: {{ $hasEntries ? 'pointer' : 'default' }}; transition: color 0.2s ease; border-radius: 50px; opacity: {{ $hasEntries ? '1' : '0.4' }}; text-align: center;">
                            {{ $type->name }}
                        </button>
                    @endforeach
                </div>

                <!-- Tab Content -->
                @foreach($mealTypes as $type)
                    @if($grouped->has($type->id))
                        <div class="tab-panel" id="tab-{{ $type->id }}" style="display: none;">
                            <div class="card mb-3">
                                @foreach($grouped[$type->id] as $entry)
                                    <div class="px-4 py-3" style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-white font-weight-bold" style="font-size: 0.9rem;">
                                                {{ $entry->meal_type->name }}
                                            </span>
                                            <a href="{{ route('meal.day.entry.edit', [$day->meal_plan->id, $day->id, $entry->id]) }}" class="btn btn-secondary btn-sm py-1 px-3" style="font-size: 0.75rem;">
                                                Manage
                                            </a>
                                        </div>

                                        <div class="d-flex flex-wrap" style="gap: 0.4rem;">
                                            @foreach($entry->foods as $entryFood)
                                                <a href="{{ route('meal.day.entry.show', [$day->meal_plan->id, $day->id, $entry->id]) }}" class="text-decoration-none">
                                                    <span class="food-chip-sm d-inline-flex align-items-center"
                                                        style="background: rgba(163,230,53,0.08); border: 1px solid rgba(163,230,53,0.15); border-radius: 8px; padding: 0.35rem 0.7rem; font-size: 0.8rem; color: #a3e635; font-weight: 500; transition: all 0.15s ease; cursor: pointer;">
                                                        {{ $entryFood->food->name }}
                                                        <span class="ml-1" style="color: #64748b; font-size: 0.7rem;">{{ $entryFood->food->energy_kcal }}kcal</span>
                                                    </span>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @php
                                $tabKcal = $grouped[$type->id]->sum(fn($e) => $e->foods->sum(fn($f) => $f->food->energy_kcal ?? 0));
                                $tabProtein = $grouped[$type->id]->sum(fn($e) => $e->foods->sum(fn($f) => $f->food->protein ?? 0));
                                $tabCarbs = $grouped[$type->id]->sum(fn($e) => $e->foods->sum(fn($f) => $f->food->carbs ?? 0));
                                $tabFat = $grouped[$type->id]->sum(fn($e) => $e->foods->sum(fn($f) => $f->food->fat ?? 0));
                            @endphp
                            <div class="row mb-3">
                                <div class="col-6 col-md-3 mb-2">
                                    <div class="card h-100">
                                        <div class="card-body text-center py-3">
                                            <div class="font-weight-bold" style="font-size: 1.5rem; color: #f1f5f9;">{{ round($tabKcal) }}</div>
                                            <small class="text-muted text-uppercase">kcal</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 mb-2">
                                    <div class="card h-100">
                                        <div class="card-body text-center py-3">
                                            <div class="font-weight-bold" style="font-size: 1.5rem; color: #38bdf8;">{{ round($tabProtein, 1) }}g</div>
                                            <small class="text-muted text-uppercase">Protein</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 mb-2">
                                    <div class="card h-100">
                                        <div class="card-body text-center py-3">
                                            <div class="font-weight-bold" style="font-size: 1.5rem; color: #fbbf24;">{{ round($tabCarbs, 1) }}g</div>
                                            <small class="text-muted text-uppercase">Carbs</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 mb-2">
                                    <div class="card h-100">
                                        <div class="card-body text-center py-3">
                                            <div class="font-weight-bold" style="font-size: 1.5rem; color: #fb7185;">{{ round($tabFat, 1) }}g</div>
                                            <small class="text-muted text-uppercase">Fat</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="card">
                    <div class="text-center py-5 text-muted card-body">
                        <svg class="mb-3" width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        <h5>No food entries for this day</h5>
                        <p class="mb-4">Start adding foods to build your meal plan!</p>
                        <a href="{{ route('meal.day.entry.create', [$day->meal_plan->id, $day->id]) }}" class="btn btn-outline-primary btn-sm">Add First Food</a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function switchTab(tabId, btn) {
            document.querySelectorAll('.tab-panel').forEach(p => p.style.display = 'none');
            document.querySelectorAll('.meal-tab-btn').forEach(b => {
                b.style.color = '#94a3b8';
            });
            document.getElementById(tabId).style.display = 'block';
            btn.style.color = '#a3e635';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const firstTab = document.querySelector('.meal-tab-btn');
            if (firstTab) firstTab.click();
        });
    </script>

    <style>
        .meal-tab-btn:not(.disabled-tab):hover {
            color: #cbd5e1 !important;
        }
        .food-chip-sm:hover {
            background: rgba(163,230,53,0.12) !important;
            border-color: rgba(163,230,53,0.3) !important;
        }
    </style>
@endsection
