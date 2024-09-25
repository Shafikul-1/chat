<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->controller(ChatController::class)->name('chat.')->group(function () {
    Route::get('all-users', 'allUsers')->name('allUsers');
    Route::get('chat/{id?}', 'index')->name('index');
    Route::post('chat/{id}', 'storeMessage')->name('storeMessage');
    Route::post('chat/user/{id}', 'invite')->name('invite');
    Route::post('chat/invite/{id}', 'inviteStatus')->name('inviteStatus');
    Route::delete('chat/message/{id}', 'messageDelete')->name('messageDelete');
    Route::post('chat/message/{id}', 'messageUpdate')->name('messageUpdate');
});

Route::get('check/{id}', [Chatcontroller::class, 'check'])->name('check');
require __DIR__.'/auth.php';
