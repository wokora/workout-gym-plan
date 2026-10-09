<?php

namespace App\Http\Controllers;

use App\Http\Resources\MealPlanResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserVitalResource;
use App\Http\Resources\WorkoutResource;
use App\Models\Body\Body;
use App\Models\Exercise\Exercise;
use App\Models\Meal\Day as MealDay;
use App\Models\Meal\Plan as MealPlan;
use App\Models\Vital\UserVital;
use App\Models\Vital\Vital;
use App\Models\Workout\Day as WorkoutDay;
use App\Models\Workout\Workout;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Return application dashboard analytics and health insights as JSON.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $userId = $user->id;

        // 1. Core counts
        $workoutCount = Workout::where('user_id', $userId)->count();
        $mealPlanCount = MealPlan::where('user_id', $userId)->count();
        $exerciseCount = Exercise::count();
        $bodySectionsCount = Body::count();

        // 2. User vitals & BMI calculation
        $latestWeight = $user->latestVital('weight');
        $latestHeight = $user->latestVital('height');
        $latestHeartRate = $user->latestVital('heart_rate');
        $latestBpSystolic = $user->latestVital('bp_systolic');
        $latestBpDiastolic = $user->latestVital('bp_diastolic');
        $latestBodyFat = $user->latestVital('body_fat');

        $weight = $latestWeight ? floatval($latestWeight->value) : null;
        $height = $latestHeight ? floatval($latestHeight->value) : null;
        $bmi = $user->bmi();
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
        $calcHeight = ($height && $height > 0) ? $height : 170;
        $heightM = $calcHeight / 100;
        $heightSqrd = $heightM * $heightM;
        $minWeight = round(18.5 * $heightSqrd, 1);
        $idealWeight = round(22.2 * $heightSqrd, 1);
        $maxWeight = round(24.9 * $heightSqrd, 1);

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

        // Age-derived biometrics & Heart Rate Status
        $userAge = $user->age();
        $maxHeartRate = $user->maxHeartRate();
        $bmr = $user->bmr();

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

        // Body Fat Status
        $bfStatus = [
            'label' => 'Not Logged',
            'badgeClass' => 'bg-slate-100 text-slate-400 dark:bg-navy-600 dark:text-navy-300',
            'color' => '#64748b',
            'isWarning' => false,
        ];
        if ($latestBodyFat) {
            $bf = floatval($latestBodyFat->value);
            $gender = strtolower($user->gender ? ($user->gender->slug ?? $user->gender->name ?? 'male') : 'male');
            $highBfThreshold = 25;
            $modBfThreshold = 18;
            $lowBfThreshold = 6;

            if ($gender === 'female' || $gender === 'f') {
                $highBfThreshold = 32;
                $modBfThreshold = 25;
                $lowBfThreshold = 14;
            } elseif ($userAge && $userAge > 40) {
                if ($userAge > 60) {
                    $highBfThreshold = 28;
                    $modBfThreshold = 22;
                    $lowBfThreshold = 9;
                } else {
                    $highBfThreshold = 26;
                    $modBfThreshold = 20;
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

        $formattedTodayWorkouts = $todayWorkoutDays->map(function ($wd) {
            $totalSets = $wd->workout_exercise->sum('sets');
            $totalReps = $wd->workout_exercise->sum(fn($we) => $we->sets * $we->reps);
            $startTime = $wd->workout->start_time ? Carbon::parse($wd->workout->start_time) : null;
            $endTime = $wd->workout->end_time ? Carbon::parse($wd->workout->end_time) : null;
            $durationMin = ($startTime && $endTime) ? $startTime->diffInMinutes($endTime) : 60;

            return [
                'id' => $wd->id,
                'workout_id' => $wd->workout->id,
                'workout_name' => $wd->workout->name,
                'day_name' => $wd->day ? $wd->day->name : 'Scheduled',
                'start_time' => $startTime ? $startTime->format('H:i') : null,
                'end_time' => $endTime ? $endTime->format('H:i') : null,
                'duration_min' => $durationMin,
                'exercise_count' => $wd->workout_exercise->count(),
                'total_sets' => $totalSets,
                'total_reps' => $totalReps,
                'exercises' => $wd->workout_exercise->map(function ($we) {
                    return [
                        'id' => $we->id,
                        'name' => $we->exercise->name,
                        'sets' => $we->sets,
                        'reps' => $we->reps,
                        'body_sections' => $we->exercise->body_section->map(fn($eb) => $eb->body_section->name)->values(),
                    ];
                }),
            ];
        });

        // 5. Today's scheduled meals
        $todayMealDays = MealDay::whereHas('day', fn($q) => $q->where('number', $todayDayNum))
            ->whereHas('meal_plan', fn($q) => $q->where('user_id', $userId))
            ->with(['meal_plan', 'entry.foods.food', 'entry.meal_type', 'day'])
            ->get();

        $formattedTodayMeals = $todayMealDays->map(function ($mpd) {
            $totalEntries = $mpd->entry->count();
            $totalFoods = $mpd->entry->sum(fn($e) => $e->foods->count());
            $totalKcal = $mpd->entry->sum(fn($e) => $e->foods->sum(fn($f) => $f->food->energy_kcal ?? 0));
            $totalProtein = $mpd->entry->sum(fn($e) => $e->foods->sum(fn($f) => $f->food->protein ?? 0));

            return [
                'id' => $mpd->id,
                'meal_plan_id' => $mpd->meal_plan->id,
                'meal_plan_name' => $mpd->meal_plan->name,
                'day_name' => $mpd->day ? $mpd->day->name : 'Scheduled',
                'total_entries' => $totalEntries,
                'total_foods' => $totalFoods,
                'total_kcal' => round($totalKcal),
                'total_protein' => round($totalProtein, 1),
                'entries' => $mpd->entry->map(function ($entry) {
                    return [
                        'id' => $entry->id,
                        'meal_type' => $entry->meal_type ? $entry->meal_type->name : 'Meal',
                        'foods' => $entry->foods->map(function ($ef) {
                            return [
                                'name' => $ef->food->name ?? 'Food Item',
                                'energy_kcal' => $ef->food->energy_kcal ?? 0,
                            ];
                        }),
                    ];
                }),
            ];
        });

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

        // 8. Recent entities
        $recentWorkouts = Workout::where('user_id', $userId)
            ->with(['workout_day.day', 'workout_day.workout_exercise.exercise'])
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        $recentVitals = $user->vitals()
            ->with(['vital.unit'])
            ->orderBy('date_measured', 'desc')
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();

        $recentMealPlans = MealPlan::where('user_id', $userId)
            ->with(['meal_plan_day.day', 'meal_plan_day.entry.meal_type'])
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'user' => new UserResource($user),
            'counts' => [
                'workouts' => $workoutCount,
                'meal_plans' => $mealPlanCount,
                'exercises' => $exerciseCount,
                'body_sections' => $bodySectionsCount,
            ],
            'vitals' => [
                'weight' => $weight,
                'height' => $height,
                'heart_rate' => $latestHeartRate ? floatval($latestHeartRate->value) : null,
                'bp_systolic' => $latestBpSystolic ? floatval($latestBpSystolic->value) : null,
                'bp_diastolic' => $latestBpDiastolic ? floatval($latestBpDiastolic->value) : null,
                'body_fat' => $latestBodyFat ? floatval($latestBodyFat->value) : null,
            ],
            'metrics' => [
                'bmi' => $bmi,
                'bmi_category' => $bmiCategory,
                'bmi_color' => $bmiColor,
                'bmi_badge_class' => $bmiBadgeClass,
                'bmi_is_warning' => $bmiIsWarning,
                'bmr' => $bmr,
                'max_heart_rate' => $maxHeartRate,
                'age' => $userAge,
                'min_weight' => $minWeight,
                'ideal_weight' => $idealWeight,
                'max_weight' => $maxWeight,
            ],
            'statuses' => [
                'weight' => $weightStatus,
                'bp' => $bpStatus,
                'hr' => $hrStatus,
                'bf' => $bfStatus,
            ],
            'today_workouts' => $formattedTodayWorkouts,
            'today_meals' => $formattedTodayMeals,
            'vitals_chart' => [
                'labels' => $chartLabels,
                'weights' => $chartWeightData,
                'bmis' => $chartBmiData,
                'weight_change' => $weightChange,
                'vitals_count' => $vitalsCount,
            ],
            'calendar' => [
                'month_name' => $calendarMonthName,
                'today_day' => $calendarTodayDay,
                'days_in_month' => $daysInMonth,
                'prev_month_days' => $prevMonthDays,
                'next_month_days' => $nextMonthDays,
            ],
            'recent_workouts' => WorkoutResource::collection($recentWorkouts),
            'recent_vitals' => UserVitalResource::collection($recentVitals),
            'recent_meal_plans' => MealPlanResource::collection($recentMealPlans),
        ]);
    }
}
