@extends('layouts.app')

@section('title', 'Entry Detail')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <div>
                    <h2 class="text-white mb-0">{{ $entry->meal_type->name }}</h2>
                    <div class="text-muted" style="font-size: 0.85rem;">
                        <a href="{{ route('meal.show', $entry->meal_plan_day->meal_plan->id) }}" class="text-muted text-decoration-none">{{ $entry->meal_plan_day->meal_plan->name }}</a>
                        &bull;
                        <a href="{{ route('meal.day.show', [$entry->meal_plan_day->meal_plan->id, $entry->meal_plan_day->id]) }}" class="text-muted text-decoration-none">{{ $entry->meal_plan_day->day->name }}</a>
                    </div>
                </div>
                <div class="d-flex mt-2 mt-sm-0" style="gap: 0.5rem;">
                    <a href="{{ route('meal.day.entry.edit', [$entry->meal_plan_day->meal_plan->id, $entry->meal_plan_day->id, $entry->id]) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                    <form action="{{ route('meal.day.entry.destroy', [$entry->meal_plan_day->meal_plan->id, $entry->meal_plan_day->id, $entry->id]) }}" method="POST" onsubmit="return confirm('Delete this entry?');" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-secondary btn-sm" style="border-color: rgba(239,68,68,0.4); color: #ef4444;">Delete</button>
                    </form>
                </div>
            </div>

            <!-- Meal Type Badge -->
            <div class="text-center mb-4">
                <span class="badge py-2 px-4" style="background: #1a2e1a; border: 1px solid rgba(163,230,53,0.2); border-radius: 10px; color: #a3e635; font-size: 0.95rem; font-weight: 600;">
                    {{ $entry->meal_type->name }}
                </span>
            </div>

            <!-- Description -->
            @if($entry->description)
            <div class="card mb-4">
                <div class="card-header" style="font-size: 0.9rem;">Notes</div>
                <div class="card-body py-3">
                    <p class="text-white mb-0" style="font-size: 0.95rem; line-height: 1.6;">{{ $entry->description }}</p>
                </div>
            </div>
            @endif

            <!-- Foods -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Foods</span>
                    <span class="text-muted" style="font-size: 0.8rem;">{{ $entry->foods->count() }} item{{ $entry->foods->count() !== 1 ? 's' : '' }}</span>
                </div>
                <div class="card-body p-0">
                    @foreach($entry->foods as $entryFood)
                        <div class="d-flex align-items-center px-4 py-3" style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                            <div class="flex-grow-1" style="min-width: 0;">
                                <div class="text-white font-weight-bold mb-1" style="font-size: 0.95rem;">
                                    {{ $entryFood->food->name }}
                                </div>
                                <div style="font-size: 0.75rem;">
                                    <span style="color: #94a3b8;">{{ $entryFood->food->energy_kcal }} kcal/100g</span>
                                    @if($entryFood->food->protein)
                                        <span class="ml-2" style="color: #38bdf8;">{{ $entryFood->food->protein }}g protein</span>
                                    @endif
                                    @if($entryFood->food->carbs)
                                        <span class="ml-2" style="color: #fbbf24;">{{ $entryFood->food->carbs }}g carbs</span>
                                    @endif
                                    @if($entryFood->food->fat)
                                        <span class="ml-2" style="color: #fb7185;">{{ $entryFood->food->fat }}g fat</span>
                                    @endif
                                </div>
                                @if($entryFood->food->description)
                                    <div class="text-muted mt-1" style="font-size: 0.75rem; line-height: 1.4;">{{ $entryFood->food->description }}</div>
                                @endif
                            </div>
                            @if($entryFood->food->standard_portion_description)
                                <div class="text-right ml-3 flex-shrink-0" style="font-size: 0.75rem; color: #64748b; line-height: 1.3;">
                                    <div>{{ $entryFood->food->standard_portion_description }}</div>
                                    <div>{{ $entryFood->food->standard_portion_grams }}g</div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Nutrition Summary -->
            @php
                $totalKcal = $entry->foods->sum(fn($f) => $f->food->energy_kcal ?? 0);
                $totalProtein = $entry->foods->sum(fn($f) => $f->food->protein ?? 0);
                $totalCarbs = $entry->foods->sum(fn($f) => $f->food->carbs ?? 0);
                $totalFat = $entry->foods->sum(fn($f) => $f->food->fat ?? 0);
            @endphp
            <div class="row mb-4">
                <div class="col-6 col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center py-3">
                            <div class="font-weight-bold" style="font-size: 1.5rem; color: #f1f5f9;">{{ round($totalKcal) }}</div>
                            <small class="text-muted text-uppercase">kcal</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center py-3">
                            <div class="font-weight-bold" style="font-size: 1.5rem; color: #38bdf8;">{{ round($totalProtein, 1) }}g</div>
                            <small class="text-muted text-uppercase">Protein</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center py-3">
                            <div class="font-weight-bold" style="font-size: 1.5rem; color: #fbbf24;">{{ round($totalCarbs, 1) }}g</div>
                            <small class="text-muted text-uppercase">Carbs</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center py-3">
                            <div class="font-weight-bold" style="font-size: 1.5rem; color: #fb7185;">{{ round($totalFat, 1) }}g</div>
                            <small class="text-muted text-uppercase">Fat</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
