@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
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
            <div class="row mb-4">
                @php
                    $latestVital = \App\Models\Vital\Vital::where('user_id', Auth::id())->orderBy('date_measured', 'desc')->first();
                    $bmi = null;
                    $bmiCategory = '';
                    $bmiColor = '';
                    if ($latestVital && $latestVital->weight && $latestVital->height) {
                        $heightM = $latestVital->height / 100;
                        $bmi = round($latestVital->weight / ($heightM * $heightM), 1);
                        if ($bmi < 18.5) { $bmiCategory = 'Underweight'; $bmiColor = '#38bdf8'; }
                        elseif ($bmi < 25) { $bmiCategory = 'Normal'; $bmiColor = '#10b981'; }
                        elseif ($bmi < 30) { $bmiCategory = 'Overweight'; $bmiColor = '#fbbf24'; }
                        else { $bmiCategory = 'Obese'; $bmiColor = '#ef4444'; }
                    }
                @endphp
                <div class="col-6 col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center py-3">
                            <div class="font-weight-bold" style="font-size: 1.5rem; color: #a3e635;">{{ \App\Models\Workout\Workout::count() }}</div>
                            <small class="text-muted text-uppercase">Workouts</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center py-3">
                            <div class="font-weight-bold" style="font-size: 1.5rem; color: #38bdf8;">{{ \App\Models\Meal\Plan::count() }}</div>
                            <small class="text-muted text-uppercase">Meal Plans</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center py-3">
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
                <div class="col-6 col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center py-3">
                            <div class="font-weight-bold" style="font-size: 1.5rem; color: #10b981;">{{ \App\Models\Food\Food::count() }}</div>
                            <small class="text-muted text-uppercase">Foods</small>
                        </div>
                    </div>
                </div>
            </div>

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
                @endphp
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Vitals Progress</span>
                        <a href="{{ route('vital.create') }}" class="btn btn-primary btn-sm py-1 px-3" style="font-size: 0.75rem;">+ Record</a>
                    </div>
                    <div class="card-body">
                        <div style="position: relative; height: 250px; width: 100%;">
                            <canvas id="dashboardVitalsChart"></canvas>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recommended Ranges -->
            @if(isset($latestVital) && $latestVital && $latestVital->height)
                @php
                    $heightSqrd = ($latestVital->height / 100) * ($latestVital->height / 100);
                @endphp
                <div class="card mb-4">
                    <div class="card-header">Recommended Weight Range (BMI 18.5 - 24.9)</div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="font-weight-bold" style="font-size: 1.1rem; color: #38bdf8;">{{ round(18.5 * $heightSqrd, 1) }} kg</div>
                                <small class="text-muted text-uppercase" style="font-size: 0.65rem;">Min (18.5)</small>
                            </div>
                            <div class="col-4">
                                <div class="font-weight-bold" style="font-size: 1.2rem; color: #a3e635;">{{ round(22.2 * $heightSqrd, 1) }} kg</div>
                                <small class="text-muted text-uppercase" style="font-size: 0.65rem;">Ideal (22.2)</small>
                            </div>
                            <div class="col-4">
                                <div class="font-weight-bold" style="font-size: 1.1rem; color: #fbbf24;">{{ round(24.9 * $heightSqrd, 1) }} kg</div>
                                <small class="text-muted text-uppercase" style="font-size: 0.65rem;">Max (24.9)</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Today's Workout -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Today's Workout</span>
                    <a href="{{ route('workout.create') }}" class="btn btn-primary btn-sm py-1 px-3" style="font-size: 0.75rem;">+ Add Workout</a>
                </div>
                <div class="card-body">
                    @php
                        $todayDayNum = date('w');
                        $workoutDays = \App\Models\Workout\Day::whereHas('day', fn($q) => $q->where('number', $todayDayNum))
                            ->whereHas('workout', fn($q) => $q->whereDate('start_date', '<=', now())->whereDate('end_date', '>=', now()))
                            ->with(['workout', 'workout_exercise.exercise'])
                            ->get();
                    @endphp

                    @if($workoutDays->isNotEmpty())
                        @foreach($workoutDays as $wd)
                            <div class="mb-3 pb-3" style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                                <a href="{{ route('workout.show', $wd->workout->id) }}" class="text-white font-weight-bold text-decoration-none" style="font-size: 0.95rem;">
                                    {{ $wd->workout->name }}
                                </a>
                                <small class="text-muted ml-2">{{ \Carbon\Carbon::parse($wd->workout->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($wd->workout->end_time)->format('H:i') }}</small>
                                <div class="d-flex flex-wrap mt-2" style="gap: 0.35rem;">
                                    @foreach($wd->workout_exercise as $we)
                                        <span style="background: rgba(163,230,53,0.08); border: 1px solid rgba(163,230,53,0.15); border-radius: 6px; padding: 0.25rem 0.6rem; font-size: 0.78rem; color: #a3e635;">
                                            {{ $we->exercise->name }}
                                            <span style="color: #64748b; margin-left: 0.3rem;">{{ $we->sets }}x{{ $we->reps }}</span>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center mb-0 py-2" style="font-size: 0.85rem;">No workout scheduled for today.</p>
                    @endif
                </div>
            </div>

            <!-- Today's Meals -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Today's Meals</span>
                    <a href="{{ route('meal.create') }}" class="btn btn-primary btn-sm py-1 px-3" style="font-size: 0.75rem;">+ Add Meal Plan</a>
                </div>
                <div class="card-body">
                    @php
                        $mealPlanDays = \App\Models\Meal\Day::whereHas('day', fn($q) => $q->where('number', $todayDayNum))
                            ->whereHas('meal_plan', fn($q) => $q->whereDate('start_date', '<=', now())->whereDate('end_date', '>=', now()))
                            ->with(['meal_plan', 'entry.foods.food', 'entry.meal_type'])
                            ->get();
                    @endphp

                    @if($mealPlanDays->isNotEmpty())
                        @foreach($mealPlanDays as $mpd)
                            <div class="mb-3 pb-3" style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                                <a href="{{ route('meal.show', $mpd->meal_plan->id) }}" class="text-white font-weight-bold text-decoration-none" style="font-size: 0.95rem;">
                                    {{ $mpd->meal_plan->name }}
                                </a>
                                @foreach($mpd->entry as $entry)
                                    <div class="mt-2">
                                        <span style="color: #64748b; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em;">{{ $entry->meal_type->name }}</span>
                                        <div class="d-flex flex-wrap mt-1" style="gap: 0.35rem;">
                                            @foreach($entry->foods as $ef)
                                                <span style="background: rgba(163,230,53,0.08); border: 1px solid rgba(163,230,53,0.15); border-radius: 6px; padding: 0.25rem 0.6rem; font-size: 0.78rem; color: #a3e635;">
                                                    {{ $ef->food->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center mb-0 py-2" style="font-size: 0.85rem;">No meals planned for today.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

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
                            backgroundColor: 'rgba(163, 230, 53, 0.08)',
                            borderWidth: 2,
                            pointBackgroundColor: '#a3e635',
                            pointBorderColor: '#0b0f17',
                            pointRadius: 3,
                            pointHoverRadius: 6,
                            tension: 0.3,
                            yAxisID: 'y',
                            fill: true
                        },
                        {
                            label: 'BMI',
                            data: {!! json_encode($bmiData) !!},
                            borderColor: '#06b6d4',
                            backgroundColor: 'rgba(6, 182, 212, 0.05)',
                            borderWidth: 2,
                            pointBackgroundColor: '#06b6d4',
                            pointBorderColor: '#0b0f17',
                            pointRadius: 3,
                            pointHoverRadius: 6,
                            tension: 0.3,
                            yAxisID: 'y1',
                            borderDash: [5, 5]
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: {
                            labels: { color: '#94a3b8', font: { family: 'Inter', size: 11 }, usePointStyle: true, padding: 20 }
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: 'rgba(255,255,255,0.04)' },
                            ticks: { color: '#64748b', font: { family: 'Inter', size: 10 } }
                        },
                        y: {
                            type: 'linear', position: 'left',
                            grid: { color: 'rgba(255,255,255,0.04)' },
                            ticks: { color: '#a3e635', font: { family: 'Inter', size: 10 } }
                        },
                        y1: {
                            type: 'linear', position: 'right',
                            grid: { drawOnChartArea: false },
                            ticks: { color: '#06b6d4', font: { family: 'Inter', size: 10 } }
                        }
                    }
                }
            });
        });
    </script>
@endif
