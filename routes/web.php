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

Auth::routes();

Route::middleware('auth')->group(function(){
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');

    Route::resource('workout', \App\Http\Controllers\Workout\WorkoutController::class);
    Route::resource('workout.exercise', \App\Http\Controllers\Workout\ExerciseController::class);

    Route::resource('exercise', \App\Http\Controllers\Exercise\ExerciseController::class);

});

