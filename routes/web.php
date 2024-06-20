<?php

use App\Livewire\PlayerProfile;
use App\Livewire\AdminDashboard;
use App\Livewire\PlayerDashboard;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/player-dashboard', PlayerDashboard::class)->name('player-dashboard');
    Route::get('/players/{player}/profile', PlayerProfile::class)->name('player.profile');
});

Route::middleware('admin')->group(function () {
    Route::get('/admin-dashboard', AdminDashboard::class, 'admin')->name('admin-dashboard');
});

require __DIR__.'/auth.php';
