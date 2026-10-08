<?php

namespace App\Http\Controllers;

use App\Models\Body\Body;
use App\Models\Exercise\Exercise;
use App\Models\Meal\Day as MealDay;
use App\Models\Meal\Plan as MealPlan;
use App\Models\Vital\UserVital;
use App\Models\Vital\Vital;
use App\Models\Workout\Day as WorkoutDay;
use App\Models\Workout\Workout;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $userId = Auth::id();

        // 1. Core counts
        $workoutCount = Workout::where('user_id', $userId)->count();
        $mealPlanCount = MealPlan::where('user_id', $userId)->count();
        $exerciseCount = Exercise::count();
        $bodySectionsCount = Body::count();

        // 2. User vitals & BMI calculation
        $latestWeight = $user ? $user->latestVital('weight') : null;
        $latestHeight = $user ? $user->latestVital('height') : null;
        $latestHeartRate = $user ? $user->latestVital('heart_rate') : null;
        $latestBpSystolic = $user ? $user->latestVital('bp_systolic') : null;
        $latestBpDiastolic = $user ? $user->latestVital('bp_diastolic') : null;
        $latestBodyFat = $user ? $user->latestVital('body_fat') : null;

        $weight = $latestWeight ? $latestWeight->value : null;
        $height = $latestHeight ? $latestHeight->value : null;
        $bmi = $user ? $user->bmi() : null;

        $bmiCategory = 'Normal';
        $bmiColor = '#10b981';
        $bmiBadgeClass = 'bg-success/10 text-success dark:bg-success/15';

        $bmiIsWarning = false;
        if ($bmi) {
            if ($bmi < 18.5) {
                $bmiCategory = 'Underweight';
                $bmiColor = '#38bdf8';
                $bmiBadgeClass = 'bg-info/10 text-info dark:bg-info/15';
                $bmiIsWarning = true;
            } elseif ($bmi < 25) {
                $bmiCategory = 'Normal';
                $bmiColor = '#10b981';
                $bmiBadgeClass = 'bg-success/10 text-success dark:bg-success/15';
                $bmiIsWarning = false;
            } elseif ($bmi < 30) {
                $bmiCategory = 'Overweight';
                $bmiColor = '#fbbf24';
                $bmiBadgeClass = 'bg-warning/10 text-warning dark:bg-warning/15';
                $bmiIsWarning = true;
            } else {
                $bmiCategory = 'Obese';
                $bmiColor = '#ef4444';
                $bmiBadgeClass = 'bg-error/10 text-error dark:bg-error/15';
                $bmiIsWarning = true;
            }
        }

        // 3. Recommended weight targets (BMI 18.5, 22.2, 24.9)
        $minWeight = null;
        $idealWeight = null;
        $maxWeight = null;

        if ($height && $height > 0) {
            $heightM = $height / 100;
            $heightSqrd = $heightM * $heightM;
            $minWeight = round(18.5 * $heightSqrd, 1);
            $idealWeight = round(22.2 * $heightSqrd, 1);
            $maxWeight = round(24.9 * $heightSqrd, 1);
        }

        // 3b. Status and warnings for each bodily vital
        // Weight Status
        $weightStatus = [
            'label' => 'Logged',
            'badgeClass' => 'bg-slate-100 text-slate-600 dark:bg-navy-600 dark:text-navy-200',
            'color' => '#64748b',
            'isWarning' => false,
        ];
        if ($weight && $minWeight && $maxWeight) {
            if ($weight > $maxWeight) {
                $weightStatus = [
                    'label' => 'Above Target',
                    'badgeClass' => 'bg-warning/10 text-warning dark:bg-warning/15',
                    'color' => '#fbbf24',
                    'isWarning' => true,
                ];
            } elseif ($weight < $minWeight) {
                $weightStatus = [
                    'label' => 'Below Target',
                    'badgeClass' => 'bg-info/10 text-info dark:bg-info/15',
                    'color' => '#38bdf8',
                    'isWarning' => true,
                ];
            } else {
                $weightStatus = [
                    'label' => 'Healthy Range',
                    'badgeClass' => 'bg-success/10 text-success dark:bg-success/15',
                    'color' => '#10b981',
                    'isWarning' => false,
                ];
            }
        }

        // Blood Pressure Status
        $bpStatus = [
            'label' => 'Not Logged',
            'badgeClass' => 'bg-slate-100 text-slate-400 dark:bg-navy-600 dark:text-navy-300',
            'color' => '#64748b',
            'isWarning' => false,
        ];
        if ($latestBpSystolic && $latestBpDiastolic) {
            $sys = floatval($latestBpSystolic->value);
            $dia = floatval($latestBpDiastolic->value);
            if ($sys >= 140 || $dia >= 90) {
                $bpStatus = [
                    'label' => 'Stage 2 High',
                    'badgeClass' => 'bg-error/10 text-error dark:bg-error/15',
                    'color' => '#ef4444',
                    'isWarning' => true,
                ];
            } elseif ($sys >= 130 || $dia >= 80) {
                $bpStatus = [
                    'label' => 'Stage 1 High',
                    'badgeClass' => 'bg-warning/10 text-warning dark:bg-warning/15',
                    'color' => '#fbbf24',
                    'isWarning' => true,
                ];
            } elseif ($sys >= 120 && $dia < 80) {
                $bpStatus = [
                    'label' => 'Elevated',
                    'badgeClass' => 'bg-warning/10 text-warning dark:bg-warning/15',
                    'color' => '#fbbf24',
                    'isWarning' => true,
                ];
            } elseif ($sys < 90 || $dia < 60) {
                $bpStatus = [
                    'label' => 'Low BP',
                    'badgeClass' => 'bg-info/10 text-info dark:bg-info/15',
                    'color' => '#38bdf8',
                    'isWarning' => true,
                ];
            } else {
                $bpStatus = [
                    'label' => 'Optimal',
                    'badgeClass' => 'bg-success/10 text-success dark:bg-success/15',
                    'color' => '#10b981',
                    'isWarning' => false,
                ];
            }
        }

        // 2b. User Age & Age-derived biometrics
        $userAge = $user ? $user->age() : null;
        $maxHeartRate = $user ? $user->maxHeartRate() : null;
        $bmr = $user ? $user->bmr() : null;

        // Heart Rate Status
        $hrStatus = [
            'label' => 'Not Logged',
            'badgeClass' => 'bg-slate-100 text-slate-400 dark:bg-navy-600 dark:text-navy-300',
            'color' => '#64748b',
            'isWarning' => false,
            'maxHeartRate' => $maxHeartRate,
            'targetZone' => $maxHeartRate ? round($maxHeartRate * 0.5) . '-' . round($maxHeartRate * 0.85) . ' bpm' : null,
        ];
        if ($latestHeartRate) {
            $hr = floatval($latestHeartRate->value);
            if ($hr > 100) {
                $hrStatus['label'] = 'High Pulse';
                $hrStatus['badgeClass'] = 'bg-error/10 text-error dark:bg-error/15';
                $hrStatus['color'] = '#ef4444';
                $hrStatus['isWarning'] = true;
            } elseif ($hr >= 85) {
                $hrStatus['label'] = 'Elevated';
                $hrStatus['badgeClass'] = 'bg-warning/10 text-warning dark:bg-warning/15';
                $hrStatus['color'] = '#fbbf24';
                $hrStatus['isWarning'] = true;
            } elseif ($hr < 50) {
                $hrStatus['label'] = 'Low Pulse';
                $hrStatus['badgeClass'] = 'bg-info/10 text-info dark:bg-info/15';
                $hrStatus['color'] = '#38bdf8';
                $hrStatus['isWarning'] = true;
            } else {
                $hrStatus['label'] = 'Normal';
                $hrStatus['badgeClass'] = 'bg-success/10 text-success dark:bg-success/15';
                $hrStatus['color'] = '#10b981';
                $hrStatus['isWarning'] = false;
            }
        }

        // Body Fat Status (Age-stratified standards)
        $bfStatus = [
            'label' => 'Not Logged',
            'badgeClass' => 'bg-slate-100 text-slate-400 dark:bg-navy-600 dark:text-navy-300',
            'color' => '#64748b',
            'isWarning' => false,
        ];
        if ($latestBodyFat) {
            $bf = floatval($latestBodyFat->value);

            // Age-stratified clinical standards (ACSM)
            $highBfThreshold = 28;
            $modBfThreshold = 22;
            $lowBfThreshold = 6;
            if ($userAge !== null) {
                if ($userAge < 30) {
                    $highBfThreshold = 24;
                    $modBfThreshold = 19;
                    $lowBfThreshold = 6;
                } elseif ($userAge < 50) {
                    $highBfThreshold = 26;
                    $modBfThreshold = 21;
                    $lowBfThreshold = 7;
                } else {
                    $highBfThreshold = 28;
                    $modBfThreshold = 23;
                    $lowBfThreshold = 8;
                }
            }

            if ($bf > $highBfThreshold) {
                $bfStatus = [
                    'label' => 'High Fat',
                    'badgeClass' => 'bg-error/10 text-error dark:bg-error/15',
                    'color' => '#ef4444',
                    'isWarning' => true,
                ];
            } elseif ($bf > $modBfThreshold) {
                $bfStatus = [
                    'label' => 'Moderate',
                    'badgeClass' => 'bg-warning/10 text-warning dark:bg-warning/15',
                    'color' => '#fbbf24',
                    'isWarning' => true,
                ];
            } elseif ($bf < $lowBfThreshold) {
                $bfStatus = [
                    'label' => 'Very Low',
                    'badgeClass' => 'bg-info/10 text-info dark:bg-info/15',
                    'color' => '#38bdf8',
                    'isWarning' => true,
                ];
            } else {
                $bfStatus = [
                    'label' => 'Healthy',
                    'badgeClass' => 'bg-success/10 text-success dark:bg-success/15',
                    'color' => '#10b981',
                    'isWarning' => false,
                ];
            }
        }

        // 4. Today's scheduled workout routines
        $todayDayNum = intval(date('w'));

        $todayWorkoutDays = WorkoutDay::whereHas('day', fn($q) => $q->where('number', $todayDayNum))
            ->whereHas('workout', fn($q) => $q->where('user_id', $userId))
            ->with(['workout', 'workout_exercise.exercise.body_section.body_section', 'day'])
            ->get();

        // 5. Today's scheduled meals
        $todayMealDays = MealDay::whereHas('day', fn($q) => $q->where('number', $todayDayNum))
            ->whereHas('meal_plan', fn($q) => $q->where('user_id', $userId))
            ->with(['meal_plan', 'entry.foods.food', 'entry.meal_type', 'day'])
            ->get();

        // 6. Historical Vitals for Progress Tracking & Chart
        $weightVitalModel = Vital::where('slug', 'weight')->first();
        $weightLogs = collect();

        if ($user && $weightVitalModel) {
            $weightLogs = UserVital::where('user_id', $userId)
                ->where('vital_id', $weightVitalModel->id)
                ->orderBy('date_measured', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        }

        $chartLabels = [];
        $chartWeightData = [];
        $chartBmiData = [];
        $heightVal = $height ?: 170;

        foreach ($weightLogs as $wl) {
            $chartLabels[] = $wl->date_measured ? Carbon::parse($wl->date_measured)->format('d M') : '';
            $wVal = floatval($wl->value);
            $chartWeightData[] = $wVal;
            if ($heightVal && $heightVal > 0) {
                $hM = $heightVal / 100;
                $chartBmiData[] = round($wVal / ($hM * $hM), 1);
            } else {
                $chartBmiData[] = null;
            }
        }

        $firstWeightLog = $weightLogs->first();
        $lastWeightLog = $weightLogs->last();
        $weightChange = ($firstWeightLog && $lastWeightLog)
            ? round($lastWeightLog->value - $firstWeightLog->value, 1)
            : 0;

        $vitalsCount = $user ? $user->vitals()->distinct('date_measured')->count('date_measured') : 0;
        if ($vitalsCount === 0) {
            $vitalsCount = $weightLogs->count();
        }

        // 7. Calendar month matrix (for Lineone Personal Dashboard calendar widget)
        $calendarNow = Carbon::today();
        $calendarMonthName = $calendarNow->format('F Y');
        $calendarTodayDay = $calendarNow->day;
        $startOfMonth = $calendarNow->copy()->startOfMonth();
        $startDayOfWeek = $startOfMonth->dayOfWeek; // 0 = Sunday, 6 = Saturday
        $daysInMonth = $calendarNow->daysInMonth;

        $prevMonthDays = [];
        if ($startDayOfWeek > 0) {
            $prevMonthTotalDays = $calendarNow->copy()->subMonth()->daysInMonth;
            for ($i = $startDayOfWeek - 1; $i >= 0; $i--) {
                $prevMonthDays[] = $prevMonthTotalDays - $i;
            }
        }

        $totalCells = count($prevMonthDays) + $daysInMonth;
        $nextMonthCellsNeeded = ($totalCells % 7 === 0) ? 0 : 7 - ($totalCells % 7);
        $nextMonthDays = $nextMonthCellsNeeded > 0 ? range(1, $nextMonthCellsNeeded) : [];

        return view('index', compact(
            'user',
            'workoutCount',
            'mealPlanCount',
            'exerciseCount',
            'bodySectionsCount',
            'weight',
            'height',
            'bmi',
            'bmiCategory',
            'bmiColor',
            'bmiBadgeClass',
            'bmiIsWarning',
            'weightStatus',
            'bpStatus',
            'hrStatus',
            'bfStatus',
            'latestHeartRate',
            'latestBpSystolic',
            'latestBpDiastolic',
            'latestBodyFat',
            'minWeight',
            'idealWeight',
            'maxWeight',
            'todayWorkoutDays',
            'todayMealDays',
            'chartLabels',
            'chartWeightData',
            'chartBmiData',
            'weightChange',
            'vitalsCount',
            'userAge',
            'maxHeartRate',
            'bmr',
            'calendarMonthName',
            'calendarTodayDay',
            'daysInMonth',
            'prevMonthDays',
            'nextMonthDays'
        ));
    }
}

