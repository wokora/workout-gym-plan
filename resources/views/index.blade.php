@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                <div>
                    <h1 class="font-weight-bold mb-1 text-white" style="font-size: 1.5rem;">
                        Today
                        <span class="text-muted" style="font-size: 0.9rem; font-weight: 400;">
                            {{ \Carbon\Carbon::now()->format('l, M d') }}
                        </span>
                    </h1>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-3">
                @php
                    $latestVital = \App\Models\Vital\Vital::where('user_id', Auth::id())->orderBy('date_measured', 'desc')->first();
                    $workoutCount = \App\Models\Workout\Workout::where('user_id', Auth::id())->count();
                    $mealPlanCount = \App\Models\Meal\Plan::where('user_id', Auth::id())->count();
                    $bmi = null;
                    $bmiCategory = '';
                    $bmiColor = '';
                    $weight = null;
                    if ($latestVital && $latestVital->weight && $latestVital->height) {
                        $heightM = $latestVital->height / 100;
                        $weight = $latestVital->weight;
                        $bmi = round($latestVital->weight / ($heightM * $heightM), 1);
                        if ($bmi < 18.5) { $bmiCategory = 'Underweight'; $bmiColor = '#38bdf8'; }
                        elseif ($bmi < 25) { $bmiCategory = 'Normal'; $bmiColor = '#10b981'; }
                        elseif ($bmi < 30) { $bmiCategory = 'Overweight'; $bmiColor = '#fbbf24'; }
                        else { $bmiCategory = 'Obese'; $bmiColor = '#ef4444'; }
                    }
                @endphp
                <div class="col-6 col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center py-2">
                            <div class="font-weight-bold" style="font-size: 1.5rem; color: #a3e635;">{{ $workoutCount }}</div>
                            <small class="text-muted text-uppercase">Workouts</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center py-2">
                            <div class="font-weight-bold" style="font-size: 1.5rem; color: #38bdf8;">{{ $mealPlanCount }}</div>
                            <small class="text-muted text-uppercase">Meal Plans</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center py-2">
                            @if($weight)
                                <div class="font-weight-bold" style="font-size: 1.5rem; color: #f1f5f9;">{{ $weight }}</div>
                                <small class="text-muted text-uppercase">Weight</small>
                            @else
                                <div class="font-weight-bold" style="font-size: 1.5rem; color: #64748b;">--</div>
                                <small class="text-muted text-uppercase">Weight</small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center py-2">
                            @if($bmi)
                                <div class="font-weight-bold" style="font-size: 1.5rem; color: {{ $bmiColor }};">{{ $bmi }}</div>
                                <small class="text-muted text-uppercase">BMI &bull; {{ $bmiCategory }}</small>
                            @else
                                <div class="font-weight-bold" style="font-size: 1.5rem; color: #64748b;">--</div>
                                <small class="text-muted text-uppercase">BMI</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Tabs -->
            <div class="d-flex mb-3" style="gap: 0.35rem;">
                <button class="dash-tab active" data-tab="tab-workouts" onclick="switchDashTab('tab-workouts', this)"
                    style="flex: 1; background: #141c2b; border: 1px solid rgba(163,230,53,0.2); color: #a3e635; padding: 0.55rem 1rem; font-family: 'Outfit', sans-serif; font-weight: 600; font-size: 0.8rem; border-radius: 8px; cursor: pointer; transition: all 0.2s ease; letter-spacing: 0.02em;">
                    Workouts
                </button>
                <button class="dash-tab" data-tab="tab-meals" onclick="switchDashTab('tab-meals', this)"
                    style="flex: 1; background: #0c1220; border: 1px solid rgba(255,255,255,0.06); color: #475569; padding: 0.55rem 1rem; font-family: 'Outfit', sans-serif; font-weight: 600; font-size: 0.8rem; border-radius: 8px; cursor: pointer; transition: all 0.2s ease; letter-spacing: 0.02em;">
                    Meals
                </button>
            </div>

            <!-- Workouts Tab -->
            <div class="tab-panel-dash" id="tab-workouts">

            <!-- Today's Workout -->
            @php
                $todayDayNum = date('w');
                $workoutDays = \App\Models\Workout\Day::whereHas('day', fn($q) => $q->where('number', $todayDayNum))
                    ->whereHas('workout', fn($q) => $q->whereDate('start_date', '<=', now())->whereDate('end_date', '>=', now()))
                    ->with(['workout', 'workout_exercise.exercise.body_section.body_section', 'day'])
                    ->get();
            @endphp

            @if($workoutDays->isNotEmpty())
                <div class="d-flex align-items-center gap-2 mb-2">
                    <svg width="14" height="14" fill="none" stroke="#a3e635" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12h1m16 0h1m-9-9v1m0 16v1m-6.36-2.64l.7-.7m12.02-12.02l.7-.7M5.64 5.64l.7.7m12.02 12.02l.7.7"/></svg>
                    <h6 class="text-uppercase mb-0" style="font-size: 0.7rem; letter-spacing: 0.1em; font-weight: 600; color: #a3e635;">Today's Workout</h6>
                </div>
                <div class="row mb-3">
                    @foreach($workoutDays as $wd)
                        @php
                            $totalSets = $wd->workout_exercise->sum('sets');
                            $totalReps = $wd->workout_exercise->sum(fn($we) => $we->sets * $we->reps);
                            $startTime = \Carbon\Carbon::parse($wd->workout->start_time);
                            $endTime = \Carbon\Carbon::parse($wd->workout->end_time);
                            $durationMin = $startTime->diffInMinutes($endTime);
                        @endphp
                        <div class="col-12 mb-2">
                            <a href="{{ route('workout.show', $wd->workout->id) }}" class="text-decoration-none">
                                <div class="card" style="border-left: 3px solid #a3e635; transition: all 0.2s ease;">
                                    <div class="card-body py-2 px-4">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <span style="font-size: 0.65rem; color: #a3e635; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em;">{{ $wd->day->name }}</span>
                                                </div>
                                                <div class="font-weight-bold text-white" style="font-size: 1.05rem; letter-spacing: -0.01em;">{{ $wd->workout->name }}</div>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span style="background: #141c2b; color: #64748b; font-size: 0.65rem; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 500;">
                                                    {{ $startTime->format('H:i') }} – {{ $endTime->format('H:i') }}
                                                </span>
                                                <svg width="16" height="16" fill="none" stroke="#475569" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                            </div>
                                        </div>

                                        <div class="row mb-3 mx-0" style="background: #0a1019; border: 1px solid rgba(255,255,255,0.04); border-radius: 10px; padding: 0.5rem 0.5rem;">
                                            <div class="col-3 px-0 text-center">
                                                <div style="font-size: 0.95rem; font-weight: 700; color: #f1f5f9; line-height: 1;">{{ $wd->workout_exercise->count() }}</div>
                                                <div style="font-size: 0.55rem; color: #475569; text-transform: uppercase; letter-spacing: 0.08em; margin-top: 2px;">Exercises</div>
                                            </div>
                                            <div class="col-3 px-0 text-center" style="border-left: 1px solid rgba(255,255,255,0.04);">
                                                <div style="font-size: 0.95rem; font-weight: 700; color: #a3e635; line-height: 1;">{{ $totalSets }}</div>
                                                <div style="font-size: 0.55rem; color: #475569; text-transform: uppercase; letter-spacing: 0.08em; margin-top: 2px;">Sets</div>
                                            </div>
                                            <div class="col-3 px-0 text-center" style="border-left: 1px solid rgba(255,255,255,0.04);">
                                                <div style="font-size: 0.95rem; font-weight: 700; color: #38bdf8; line-height: 1;">{{ $totalReps }}</div>
                                                <div style="font-size: 0.55rem; color: #475569; text-transform: uppercase; letter-spacing: 0.08em; margin-top: 2px;">Reps</div>
                                            </div>
                                            <div class="col-3 px-0 text-center" style="border-left: 1px solid rgba(255,255,255,0.04);">
                                                <div style="font-size: 0.95rem; font-weight: 700; color: #94a3b8; line-height: 1;">{{ $durationMin }}<span style="font-size: 0.6rem;">m</span></div>
                                                <div style="font-size: 0.55rem; color: #475569; text-transform: uppercase; letter-spacing: 0.08em; margin-top: 2px;">Duration</div>
                                            </div>
                                        </div>

                                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.4rem;">
                                            @foreach($wd->workout_exercise as $we)
                                                <div style="background: #0c1220; border: 1px solid rgba(255,255,255,0.04); border-radius: 8px; padding: 0.4rem 0.5rem;">
                                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                                        <span style="font-size: 0.7rem; color: #a3e635; font-weight: 600; line-height: 1.3; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; flex: 1; margin-right: 0.35rem;">{{ $we->exercise->name }}</span>
                                                        <span style="font-size: 0.6rem; color: #475569; font-weight: 500; white-space: nowrap; flex-shrink: 0;">{{ $we->sets }}×{{ $we->reps }}</span>
                                                    </div>
                                                    @if($we->exercise->body_section->isNotEmpty())
                                                        <div class="d-flex flex-wrap" style="gap: 0.2rem;">
                                                            @foreach($we->exercise->body_section as $eb)
                                                                <span style="font-size: 0.5rem; color: #334155; background: rgba(255,255,255,0.02); border-radius: 3px; padding: 0.1rem 0.3rem; text-transform: uppercase; letter-spacing: 0.04em;">{{ $eb->body_section->name }}</span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card mb-3" style="border: 1px dashed rgba(255,255,255,0.06); background: transparent;">
                    <div class="card-body text-center py-5">
                        <svg class="mb-3" width="32" height="32" fill="none" stroke="#334155" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12h1m16 0h1m-9-9v1m0 16v1m-6.36-2.64l.7-.7m12.02-12.02l.7-.7M5.64 5.64l.7.7m12.02 12.02l.7.7"/></svg>
                        <p class="text-muted mb-0" style="font-size: 0.85rem;">No workout scheduled for today.</p>
                    </div>
                </div>
            @endif

            </div>
            <!-- End Workouts Tab -->

            <!-- Meals Tab Content -->
            <div class="tab-panel-dash" id="tab-meals" style="display: none;">

            <!-- Today's Meals -->
            @php
                $mealPlanDays = \App\Models\Meal\Day::whereHas('day', fn($q) => $q->where('number', $todayDayNum))
                    ->whereHas('meal_plan', fn($q) => $q->whereDate('start_date', '<=', now())->whereDate('end_date', '>=', now()))
                    ->with(['meal_plan', 'entry.foods.food', 'entry.meal_type', 'day'])
                    ->get();
            @endphp

            @if($mealPlanDays->isNotEmpty())
                <div class="d-flex align-items-center gap-2 mb-2">
                    <svg width="14" height="14" fill="none" stroke="#38bdf8" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"/></svg>
                    <h6 class="text-uppercase mb-0" style="font-size: 0.7rem; letter-spacing: 0.1em; font-weight: 600; color: #38bdf8;">Today's Meals</h6>
                </div>
                <div class="row mb-3">
                    @foreach($mealPlanDays as $mpd)
                        @php
                            $totalEntries = $mpd->entry->count();
                            $totalFoods = $mpd->entry->sum(fn($e) => $e->foods->count());
                            $totalKcal = $mpd->entry->sum(fn($e) => $e->foods->sum(fn($f) => $f->food->energy_kcal ?? 0));
                            $totalProtein = $mpd->entry->sum(fn($e) => $e->foods->sum(fn($f) => $f->food->protein ?? 0));
                        @endphp
                        <div class="col-12 mb-2">
                            <a href="{{ route('meal.show', $mpd->meal_plan->id) }}" class="text-decoration-none">
                                <div class="card" style="border-left: 3px solid #38bdf8; transition: all 0.2s ease;">
                                    <div class="card-body py-2 px-4">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <span style="font-size: 0.65rem; color: #38bdf8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em;">{{ $mpd->day->name }}</span>
                                                </div>
                                                <div class="font-weight-bold text-white" style="font-size: 1.05rem; letter-spacing: -0.01em;">{{ $mpd->meal_plan->name }}</div>
                                            </div>
                                            <svg width="16" height="16" fill="none" stroke="#475569" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                        </div>

                                        <div class="row mb-3 mx-0" style="background: #0a1019; border: 1px solid rgba(255,255,255,0.04); border-radius: 10px; padding: 0.5rem 0.5rem;">
                                            <div class="col-3 px-0 text-center">
                                                <div style="font-size: 0.95rem; font-weight: 700; color: #f1f5f9; line-height: 1;">{{ $totalEntries }}</div>
                                                <div style="font-size: 0.55rem; color: #475569; text-transform: uppercase; letter-spacing: 0.08em; margin-top: 2px;">Meals</div>
                                            </div>
                                            <div class="col-3 px-0 text-center" style="border-left: 1px solid rgba(255,255,255,0.04);">
                                                <div style="font-size: 0.95rem; font-weight: 700; color: #38bdf8; line-height: 1;">{{ $totalFoods }}</div>
                                                <div style="font-size: 0.55rem; color: #475569; text-transform: uppercase; letter-spacing: 0.08em; margin-top: 2px;">Foods</div>
                                            </div>
                                            <div class="col-3 px-0 text-center" style="border-left: 1px solid rgba(255,255,255,0.04);">
                                                <div style="font-size: 0.95rem; font-weight: 700; color: #fbbf24; line-height: 1;">{{ round($totalKcal) }}</div>
                                                <div style="font-size: 0.55rem; color: #475569; text-transform: uppercase; letter-spacing: 0.08em; margin-top: 2px;">Kcal</div>
                                            </div>
                                            <div class="col-3 px-0 text-center" style="border-left: 1px solid rgba(255,255,255,0.04);">
                                                <div style="font-size: 0.95rem; font-weight: 700; color: #94a3b8; line-height: 1;">{{ round($totalProtein, 1) }}<span style="font-size: 0.6rem;">g</span></div>
                                                <div style="font-size: 0.55rem; color: #475569; text-transform: uppercase; letter-spacing: 0.08em; margin-top: 2px;">Protein</div>
                                            </div>
                                        </div>

                                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.4rem;">
                                            @foreach($mpd->entry as $entry)
                                                <div style="background: #0c1220; border: 1px solid rgba(255,255,255,0.04); border-radius: 8px; padding: 0.4rem 0.5rem;">
                                                    <div style="font-size: 0.55rem; color: #38bdf8; text-transform: uppercase; letter-spacing: 0.06em; font-weight: 600; margin-bottom: 0.3rem;">{{ $entry->meal_type->name }}</div>
                                                    @foreach($entry->foods as $ef)
                                                        <div class="d-flex justify-content-between align-items-center" style="font-size: 0.68rem; padding: 0.15rem 0;">
                                                            <span style="color: #a3e635; font-weight: 500; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; flex: 1; margin-right: 0.35rem;">{{ $ef->food->name }}</span>
                                                            <span style="color: #475569; font-size: 0.6rem; white-space: nowrap; flex-shrink: 0;">{{ $ef->food->energy_kcal }} kcal</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card mb-3" style="border: 1px dashed rgba(255,255,255,0.06); background: transparent;">
                    <div class="card-body text-center py-5">
                        <svg class="mb-3" width="32" height="32" fill="none" stroke="#334155" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"/></svg>
                        <p class="text-muted mb-0" style="font-size: 0.85rem;">No meals planned for today.</p>
                    </div>
                </div>
            @endif

            </div>
            <!-- End Meals Tab -->


            <!-- Vitals Progress Chart -->
            @php
                $vitals = \App\Models\Vital\Vital::where('user_id', Auth::id())->orderBy('date_measured')->get();
            @endphp
            @if($vitals->count() > 0)
                @php
                    $chartLabels = [];
                    $weightData = [];
                    $bmiData = [];
                    foreach ($vitals as $v) {
                        $chartLabels[] = \Carbon\Carbon::parse($v->date_measured)->format('d M');
                        $weightData[] = floatval($v->weight);
                        $bmiData[] = round(($v->weight / ($v->height * $v->height)) * 10000, 1);
                    }
                    $latestVitalChart = $vitals->last();
                    $firstVitalChart = $vitals->first();
                    $weightChange = $latestVitalChart && $firstVitalChart ? round($latestVitalChart->weight - $firstVitalChart->weight, 1) : 0;
                @endphp
                <div class="d-flex align-items-center gap-2 mb-2">
                    <svg width="14" height="14" fill="none" stroke="#a3e635" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18"/><path stroke-linecap="round" stroke-linejoin="round" d="M7 16l4-6 4 3 4-8"/></svg>
                    <h6 class="text-uppercase mb-0" style="font-size: 0.7rem; letter-spacing: 0.1em; font-weight: 600; color: #a3e635;">Vitals Progress</h6>
                </div>

                <div class="row mb-2">
                    <div class="col-6 col-md-3 mb-2">
                        <div style="background: #0a1019; border: 1px solid rgba(255,255,255,0.04); border-radius: 10px; padding: 0.4rem 0.6rem; text-align: center;">
                            <div style="font-size: 0.95rem; font-weight: 700; color: #f1f5f9; line-height: 1;">{{ $latestVitalChart->weight }}<span style="font-size: 0.6rem; color: #475569;">kg</span></div>
                            <div style="font-size: 0.5rem; color: #475569; text-transform: uppercase; letter-spacing: 0.06em; margin-top: 1px;">Current</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-2">
                        <div style="background: #0a1019; border: 1px solid rgba(255,255,255,0.04); border-radius: 10px; padding: 0.4rem 0.6rem; text-align: center;">
                            <div style="font-size: 0.95rem; font-weight: 700; color: {{ $weightChange < 0 ? '#10b981' : ($weightChange > 0 ? '#ef4444' : '#94a3b8') }}; line-height: 1;">{{ $weightChange > 0 ? '+' : '' }}{{ $weightChange }}<span style="font-size: 0.6rem;">kg</span></div>
                            <div style="font-size: 0.5rem; color: #475569; text-transform: uppercase; letter-spacing: 0.06em; margin-top: 1px;">Change</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-2">
                        <div style="background: #0a1019; border: 1px solid rgba(255,255,255,0.04); border-radius: 10px; padding: 0.4rem 0.6rem; text-align: center;">
                            <div style="font-size: 0.95rem; font-weight: 700; color: #38bdf8; line-height: 1;">{{ $vitals->count() }}</div>
                            <div style="font-size: 0.5rem; color: #475569; text-transform: uppercase; letter-spacing: 0.06em; margin-top: 1px;">Records</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-2">
                        <a href="{{ route('vital.create') }}" class="text-decoration-none">
                            <div style="background: #0a1019; border: 1px solid rgba(163,230,53,0.15); border-radius: 10px; padding: 0.4rem 0.6rem; text-align: center; transition: all 0.2s ease;">
                                <div style="font-size: 1.2rem; font-weight: 700; color: #a3e635; line-height: 1;">+</div>
                                <div style="font-size: 0.5rem; color: #a3e635; text-transform: uppercase; letter-spacing: 0.06em; margin-top: 1px;">Record</div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="card mb-3" style="background: #0a1019; border: 1px solid rgba(255,255,255,0.04);">
                    <div class="card-body py-2 px-4">
                        <div style="position: relative; height: 260px; width: 100%;">
                            <canvas id="dashboardVitalsChart"></canvas>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recommended Weight Range -->
            @if(isset($latestVital) && $latestVital && $latestVital->height)
                @php
                    $heightSqrd = ($latestVital->height / 100) * ($latestVital->height / 100);
                    $minWeight = round(18.5 * $heightSqrd, 1);
                    $idealWeight = round(22.2 * $heightSqrd, 1);
                    $maxWeight = round(24.9 * $heightSqrd, 1);
                @endphp
                <div class="row mb-3">
                    <div class="col-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center py-2">
                                <div class="font-weight-bold" style="font-size: 1.5rem; color: #38bdf8;">{{ $minWeight }} kg</div>
                                <small class="text-muted text-uppercase">Min (BMI 18.5)</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center py-2">
                                <div class="font-weight-bold" style="font-size: 1.5rem; color: #a3e635;">{{ $idealWeight }} kg</div>
                                <small class="text-muted text-uppercase">Ideal (BMI 22.2)</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center py-2">
                                <div class="font-weight-bold" style="font-size: 1.5rem; color: #fbbf24;">{{ $maxWeight }} kg</div>
                                <small class="text-muted text-uppercase">Max (BMI 24.9)</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Workouts Tab -->
        </div>
    </div>
@endsection

<script>
    function switchDashTab(tabId, btn) {
        document.querySelectorAll('.tab-panel-dash').forEach(p => p.style.display = 'none');
        document.querySelectorAll('.dash-tab').forEach(b => {
            b.style.background = '#0c1220';
            b.style.borderColor = 'rgba(255,255,255,0.06)';
            b.style.color = '#475569';
        });
        document.getElementById(tabId).style.display = 'block';
        btn.style.background = '#141c2b';
        btn.style.borderColor = 'rgba(163,230,53,0.2)';
        btn.style.color = '#a3e635';
    }
</script>

@if(isset($vitals) && $vitals->count() > 0)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('dashboardVitalsChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [
                        {
                            label: 'Weight (kg)',
                            data: {!! json_encode($weightData) !!},
                            borderColor: '#a3e635',
                            backgroundColor: 'rgba(163, 230, 53, 0.06)',
                            borderWidth: 2.5,
                            pointBackgroundColor: '#a3e635',
                            pointBorderColor: '#0a1019',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 7,
                            tension: 0.4,
                            yAxisID: 'y',
                            fill: true
                        },
                        {
                            label: 'BMI',
                            data: {!! json_encode($bmiData) !!},
                            borderColor: '#38bdf8',
                            backgroundColor: 'rgba(56, 189, 248, 0.04)',
                            borderWidth: 2,
                            pointBackgroundColor: '#38bdf8',
                            pointBorderColor: '#0a1019',
                            pointBorderWidth: 2,
                            pointRadius: 3,
                            pointHoverRadius: 6,
                            tension: 0.4,
                            yAxisID: 'y1',
                            borderDash: [6, 4]
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: {
                            labels: { color: '#64748b', font: { family: 'Inter', size: 11, weight: '500' }, usePointStyle: true, pointStyleWidth: 8, padding: 24 }
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: 'rgba(255,255,255,0.03)' },
                            ticks: { color: '#475569', font: { family: 'Inter', size: 10 }, padding: 8 }
                        },
                        y: {
                            type: 'linear', position: 'left',
                            grid: { color: 'rgba(255,255,255,0.03)' },
                            ticks: { color: '#a3e635', font: { family: 'Inter', size: 10 }, padding: 8 }
                        },
                        y1: {
                            type: 'linear', position: 'right',
                            grid: { drawOnChartArea: false },
                            ticks: { color: '#38bdf8', font: { family: 'Inter', size: 10 }, padding: 8 }
                        }
                    }
                }
            });
        });
    </script>
@endif
