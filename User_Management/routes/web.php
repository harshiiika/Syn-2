<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Student\InquiryController;
use App\Http\Controllers\Session\SessionController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\BatchesController;
use App\Http\Controllers\Master\FeesMasterController;
use App\Http\Controllers\Master\OtherFeeController;

use App\Http\Controllers\Master\ScholarshipController;
// -------------------------
// Authentication Routes
// -------------------------

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// -------------------------
// Default Route
// -------------------------

// -------------------------
// -------------------------
// Default Route
Route::get('/', function () {
    return redirect()->route('login'); // always send root to login
})->name('home');

// -------------------------
// Dashboard (after login)
// -------------------------

Route::get('/dashboard', function () {
    return view('auth.dashboard');   // make sure you have resources/views/auth/dashboard.blade.php
})->name('dashboard');

 
/*
|--------------------------------------------------------------------------
| Inquiry Routes (no middleware now)
|--------------------------------------------------------------------------
*/

// Inquiry Management Routes
Route::prefix('inquiries')->name('inquiries.')->group(function () {
    Route::get('/', [App\Http\Controllers\Student\InquiryController::class, 'index'])->name('index');
    Route::get('/data', [App\Http\Controllers\Student\InquiryController::class, 'data'])->name('data');
    Route::get('/{id}', [App\Http\Controllers\Student\InquiryController::class, 'show'])->name('show');
    Route::post('/', [App\Http\Controllers\Student\InquiryController::class, 'store'])->name('store');
    Route::put('/{id}', [App\Http\Controllers\Student\InquiryController::class, 'update'])->name('update');
    Route::delete('/{id}', [App\Http\Controllers\Student\InquiryController::class, 'destroy'])->name('destroy');
    Route::post('/upload', [App\Http\Controllers\Student\InquiryController::class, 'upload'])->name('upload');
});






/*
|--------------------------------------------------------------------------
| Session Management Routes
|--------------------------------------------------------------------------
*/

Route::prefix('session')->group(function () {
    Route::get('/', [SessionController::class, 'index'])->name('sessions.index');
    Route::get('/create', [SessionController::class, 'create'])->name('sessions.create');
    Route::post('/', [SessionController::class, 'store'])->name('sessions.store');
    Route::post('/update/{session}', [SessionController::class, 'update'])->name('sessions.update');
    Route::post('/end/{session}', [SessionController::class, 'end'])->name('sessions.end');
    Route::delete('/{session}', [SessionController::class, 'destroy'])->name('sessions.destroy');
});

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

Route::get('/emp', [UserController::class, 'index'])->name('emp');
Route::get('/emp/list', [UserController::class, 'showUser'])->name('user.emp.emp');
Route::post('/users/add', [UserController::class, 'addUser'])->name('users.add');
Route::put('/users/update/{id}', [UserController::class, 'updateUser'])->name('users.update');
Route::put('/users/update-password/{id}', [UserController::class, 'updatePassword'])->name('users.password.update');
Route::post('/users/toggle-status/{id}', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
Route::post('/users/store', [UserController::class, 'addUser'])->name('users.store');

/*
|--------------------------------------------------------------------------
| Batches Routes
|--------------------------------------------------------------------------
*/
Route::get('/batches', [BatchesController::class, 'showBatches'])
    ->name('user.batches.batches');
Route::post('/batches/add', [BatchesController::class, 'addBatch'])->name('batches.assign');


// Fees Master Routes
Route::get('/fees-master', [FeesMasterController::class, 'index'])->name('fees.master.index');
Route::post('/fees-master/store', [FeesMasterController::class, 'store'])->name('fees.master.store');
Route::get('/fees-master/{id}', [FeesMasterController::class, 'show'])->name('fees.master.show');
Route::put('/fees-master/{id}', [FeesMasterController::class, 'update'])->name('fees.master.update');
Route::post('/fees-master/{id}/activate', [FeesMasterController::class, 'activate'])->name('fees.master.activate');
Route::post('/fees-master/{id}/deactivate', [FeesMasterController::class, 'deactivate'])->name('fees.master.deactivate');

// Other Fees Routes - NO MIDDLEWARE
Route::prefix('master/other_fees')->group(function () {
    Route::get('/', [OtherFeeController::class, 'index'])->name('master.other_fees.index');
    Route::get('/data', [OtherFeeController::class, 'index']);
    Route::get('/{id}', [OtherFeeController::class, 'show']);
    Route::post('/', [OtherFeeController::class, 'store']);
    Route::put('/{id}', [OtherFeeController::class, 'update']);
    Route::post('/{id}/toggle', [OtherFeeController::class, 'toggle']);
    Route::delete('/{id}', [OtherFeeController::class, 'destroy']);
});
// Scholarship Routes 
Route::prefix('master')->name('master.')->group(function () {
    // List scholarships (GET) - returns JSON or view
    Route::get('/scholarship', [ScholarshipController::class, 'index'])->name('scholarship.index');
    
    // Alternative endpoint for getting paginated data
    Route::get('/scholarship/data', [ScholarshipController::class, 'index'])->name('scholarship.data');
    
    // Create scholarship (POST)
    Route::post('/scholarship', [ScholarshipController::class, 'store'])->name('scholarship.store');
    
    // Show single scholarship (GET)
    Route::get('/scholarship/{id}', [ScholarshipController::class, 'show'])->name('scholarship.show');
    
    // Update scholarship (PUT)
    Route::put('/scholarship/{id}', [ScholarshipController::class, 'update'])->name('scholarship.update');
    
    // Toggle status (PATCH)
    Route::patch('/scholarship/{id}/toggle-status', [ScholarshipController::class, 'toggleStatus']);
    // Delete scholarship (DELETE)
    Route::delete('/scholarship/{id}', [ScholarshipController::class, 'destroy'])->name('scholarship.destroy');
});