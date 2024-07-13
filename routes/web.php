<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\AdminDashboard;
use App\Livewire\HomePage;
use App\Livewire\PlayerDashboard;
use App\Livewire\PlayerProfile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/home', HomePage::class)->name('home');
    Route::get('/players/{player}/player-dashboard', PlayerDashboard::class)->name('player-dashboard');
    Route::get('/players/{player}/profile', PlayerProfile::class)->name('player.profile');
});

Route::middleware('admin')->group(function () {
    Route::get('/games/{game}/admin-dashboard', AdminDashboard::class, 'admin')->name('admin-dashboard');
});

require __DIR__.'/auth.php';
