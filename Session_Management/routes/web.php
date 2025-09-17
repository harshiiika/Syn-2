<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});


// Session management routes
//list
Route::get('/session', [SessionController::class, 'index'])->name('sessions.index');
//create
Route::post('/session', [SessionController::class, 'store'])->name('sessions.store');
// show (AJAX)
Route::get('/session/{id}', [SessionController::class, 'show'])->name('sessions.show');
// update (POST + _method=PUT spoofed by form)
Route::put('/session/{id}', [SessionController::class, 'update'])->name('sessions.update');
Route::post('/session/{id}', [SessionController::class, 'update'])->name('sessions.update'); // For method spoofing
Route::post('/session/{id}/end', [SessionController::class, 'end'])->name('sessions.end');

// Route::get('/session', [SessionController::class, 'index'])->name('users.index');
//cal routes