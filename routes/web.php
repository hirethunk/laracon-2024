<?php

use App\Livewire\HomePage;
use App\Livewire\Autocomplete;
use App\Livewire\PlayerProfile;
use App\Livewire\AdminDashboard;
use App\Livewire\SecretCodePage;
use App\Livewire\PlayerDashboard;
use App\Livewire\SecretAlliancePage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/autocomplete', Autocomplete::class)->name('autocomplete');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/home', HomePage::class)->name('home');
    Route::get('/secret-code', SecretCodePage::class)->name('secret-code');
    Route::get('/secret-alliance', SecretAlliancePage::class)->name('secret-alliance');
    Route::get('/players/{player}/player-dashboard', PlayerDashboard::class)->name('player-dashboard');
    Route::get('/players/{player}/profile', PlayerProfile::class)->name('player.profile');
});

Route::middleware('admin')->group(function () {
    Route::get('/games/{game}/admin-dashboard', AdminDashboard::class, 'admin')->name('admin-dashboard');
});

require __DIR__.'/auth.php';
