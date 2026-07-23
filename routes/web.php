<?php

use Illuminate\Support\Facades\Route;

//Route::view('/', 'welcome')->name('home');
Route::redirect('/', '/dashboard')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/setup/owntracks', \App\Livewire\OwnTracksSetup::class)->name('owntracks.setup');
});

require __DIR__.'/settings.php';
