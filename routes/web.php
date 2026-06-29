<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::get('login/sso', [App\Http\Controllers\Auth\LoginController::class, 'redirectToSSO'])->name('login.sso');
Route::get('callback', [App\Http\Controllers\Auth\LoginController::class, 'handleSSOCallback'])->name('sso.callback');

Route::middleware(['auth', 'sso.verify'])->group(function(){
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');

    Route::resource('workout', \App\Http\Controllers\Workout\WorkoutController::class);
    Route::resource('workout.day', \App\Http\Controllers\Workout\DayController::class);
    Route::resource('workout.day.exercise', \App\Http\Controllers\Workout\ExerciseController::class);
    Route::resource('meal', \App\Http\Controllers\Meal\PlanController::class);
    Route::resource('meal.day', \App\Http\Controllers\Meal\DayController::class);
    Route::resource('meal.day.entry', \App\Http\Controllers\Meal\EntryController::class);
    Route::resource('exercise', \App\Http\Controllers\Exercise\ExerciseController::class);
    Route::resource('body', \App\Http\Controllers\Body\BodyController::class);
    Route::get('vital/export', [\App\Http\Controllers\Vital\VitalController::class, 'export'])->name('vital.export');
    Route::post('vital/import', [\App\Http\Controllers\Vital\VitalController::class, 'import'])->name('vital.import');
    Route::resource('vital', \App\Http\Controllers\Vital\VitalController::class);
    Route::get('food/search', [\App\Http\Controllers\Food\FoodController::class, 'search'])->name('food.search');
});

