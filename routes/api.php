<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

// Health Check
Route::get('test', function () {
    return response()->json(['ping' => 'pong', 'app' => 'workout-api', 'timestamp' => now()->toIso8601String()]);
});

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::post('sso/callback', [\App\Http\Controllers\Auth\SsoController::class, 'handleCallback']);
});

// Public Lookups / Reference Data
Route::get('days', function () {
    return response()->json(['records' => \App\Models\Day\Day::orderBy('number')->get()]);
});
Route::get('meal-types', function () {
    return response()->json(['records' => \App\Models\Meal\Type::orderBy('id')->get()]);
});
Route::get('genders', function () {
    return response()->json(['records' => \App\Models\Gender::orderBy('id')->get()]);
});
Route::get('muscle-groups', [\App\Http\Controllers\MuscleGroupController::class, 'index']);
Route::get('food/search', [\App\Http\Controllers\Food\FoodController::class, 'search']);
Route::get('foods', [\App\Http\Controllers\Food\FoodController::class, 'index']);
Route::get('foods/{id}', [\App\Http\Controllers\Food\FoodController::class, 'show']);
Route::get('food-categories', [\App\Http\Controllers\Food\FoodController::class, 'categories']);

// Authenticated Routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth Management
    Route::prefix('auth')->group(function () {
        Route::post('logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout']);
    });

    // User Profile
    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show']);
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update']);

    // Dashboard Overview
    Route::get('dashboard', [\App\Http\Controllers\HomeController::class, 'index']);

    // Workouts, Routine Days & Exercises
    Route::apiResource('workout', \App\Http\Controllers\Workout\WorkoutController::class);
    Route::apiResource('workout.day', \App\Http\Controllers\Workout\DayController::class);
    Route::apiResource('workout.day.exercise', \App\Http\Controllers\Workout\ExerciseController::class);

    // Exercise Library & Body Anatomy
    Route::apiResource('exercise', \App\Http\Controllers\Exercise\ExerciseController::class);
    Route::apiResource('body', \App\Http\Controllers\Body\BodyController::class);

    // Nutrition & Meal Planner
    Route::apiResource('meal', \App\Http\Controllers\Meal\PlanController::class);
    Route::apiResource('meal.day', \App\Http\Controllers\Meal\DayController::class);
    Route::apiResource('meal.day.entry', \App\Http\Controllers\Meal\EntryController::class);
    Route::apiResource('food', \App\Http\Controllers\Food\FoodController::class);

    // Vitals Tracking, Import & Export
    Route::get('vital/export', [\App\Http\Controllers\Vital\VitalController::class, 'export']);
    Route::post('vital/import', [\App\Http\Controllers\Vital\VitalController::class, 'import']);
    Route::apiResource('vital', \App\Http\Controllers\Vital\VitalController::class);

    // Diseases, Treatments & Symptoms
    Route::get('disease-lookups', [\App\Http\Controllers\Disease\DiseaseController::class, 'lookups']);
    Route::apiResource('disease', \App\Http\Controllers\Disease\DiseaseController::class);
    Route::post('disease/{id}/image', [\App\Http\Controllers\Disease\DiseaseController::class, 'uploadImage']);
    Route::apiResource('treatment', \App\Http\Controllers\Disease\TreatmentController::class);
    Route::apiResource('symptom', \App\Http\Controllers\Disease\SymptomController::class);

    // Media Library & Universal Upload
    Route::get('media/files', [\App\Http\Controllers\Media\MediaController::class, 'index']);
    Route::post('media/upload', [\App\Http\Controllers\Media\MediaController::class, 'upload']);
    Route::delete('media/files/{id}', [\App\Http\Controllers\Media\MediaController::class, 'destroy']);
});

