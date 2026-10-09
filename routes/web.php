<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| The backend serves strictly as a headless API. Web requests are redirected
| to the SPA frontend or return API status info.
|
*/

Route::get('/', function () {
    return response()->json([
        'name' => config('app.name'),
        'api_status' => 'operational',
        'api_version' => '1.0.0',
        'frontend_url' => env('FRONTEND_URL', 'http://localhost:5173'),
    ]);
});

Route::get('/login', function () {
    return redirect(env('FRONTEND_URL', 'http://localhost:5173') . '/auth/login');
})->name('login');
