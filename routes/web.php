<?php

use Illuminate\Support\Facades\Route;


//Route::view('/', 'welcome')->name('home');
Route::redirect('/', '/dashboard')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::view('/setup/owntracks', 'owntracks-setup')->name('owntracks.setup');
});

Route::get('/playbook', function () {
    return view('team-playbook');
})->middleware(['auth'])->name('playbook');

Route::get('/bonus-checkpoints', function () {
    return view('bonus-checkpoints');
})->middleware(['auth'])->name('bonus.checkpoints');

require __DIR__.'/settings.php';
