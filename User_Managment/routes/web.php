<?php
// use App\Http\Controllers\LoginController;
// use App\Models\User;
// use App\Http\Controllers\UserController;

// Route::get('/', fn () => redirect('/login'));

// Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// //Route::middleware(['auth:admin'])->group(function () {
//     Route::get('/emp', function () {
//         $users = User::all();
//         return view('emp.emp', compact('users'));
//     })->name('emp');

//     // Route::post('/users/add', [AdminController::class, 'addUser'])->name('users.add');
//     Route::post('/users/add', [UserController::class, 'addUser'])->name('users.add');
//     Route::put('/users/update/{id}', [UserController::class, 'updateUser'])->name('users.update');
//     Route::put('/users/update-password/{id}', [UserController::class, 'updatePassword'])->name('users.password.update');
//     Route::post('/users/toggle-status/{id}', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');

//     Route::get('/test-mongo', function () {
//     return User::all();
// });

//});

use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BatchesController;

Route::get('/', fn () => redirect('/login'));

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Use the controller method instead of inline function
Route::get('/emp', [UserController::class, 'index'])->name('emp');

// User CRUD routes

Route::post('/users/add', [UserController::class, 'addUser'])->name('users.add');
Route::get('/emp', [UserController::class, 'showUser'])->name('emp.emp');
Route::put('/users/update/{id}', [UserController::class, 'updateUser'])->name('users.update');
Route::put('/users/update-password/{id}', [UserController::class, 'updatePassword'])->name('users.password.update');
Route::post('/users/toggle-status/{id}', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');

//batches page route
Route::get('/batches', [BatchesController::class, 'showBatches'])->name('batches');
Route::post('/batches/add', [BatchesController::class, 'addBatch'])->name('batches.assign');
