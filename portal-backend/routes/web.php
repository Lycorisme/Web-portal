<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard Routes (protected by auth middleware when auth is implemented)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Logout route (placeholder - will be implemented with auth)
Route::post('/logout', function () {
    // Auth::logout();
    return redirect('/');
})->name('logout');
