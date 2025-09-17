<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Student\InquiryController;
use App\Http\Controllers\Session\SessionController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\BatchesController;

 
/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
 
/*
|--------------------------------------------------------------------------
| Default Route
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('inquiries.index')
        : redirect()->route('login');
})->name('home');
 
/*
|--------------------------------------------------------------------------
| Inquiry Routes (requires authentication)
|--------------------------------------------------------------------------
*/
// Route::middleware('auth')->prefix('inquiries')->group(function () {
$idPattern = '([0-9]+|[0-9a-fA-F]{24})';
 
    Route::get('/',              [InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/list',          [InquiryController::class, 'list'])->name('inquiries.list');
    Route::get('/create',        [InquiryController::class, 'create'])->name('inquiries.create');
    Route::post('/',             [InquiryController::class, 'store'])->name('inquiries.store');
    Route::get('/{inquiry}/edit',[InquiryController::class, 'edit'])->where('inquiry', $idPattern)->name('inquiries.edit');
    Route::put('/{inquiry}',     [InquiryController::class, 'update'])->where('inquiry', $idPattern)->name('inquiries.update');
    Route::delete('/{inquiry}',  [InquiryController::class, 'destroy'])->where('inquiry', $idPattern)->name('inquiries.destroy');
    Route::get('/{inquiry}',     [InquiryController::class, 'show'])->where('inquiry', $idPattern)->name('inquiries.show');
    Route::post('/{inquiry}/status', [InquiryController::class, 'setStatus'])->where('inquiry', $idPattern)->name('inquiries.setStatus');


    // });
 
/*
|--------------------------------------------------------------------------
| Session Management Routes
|--------------------------------------------------------------------------
*/
Route::prefix('session')->group(function () {
    Route::get('/',        [SessionController::class, 'index'])->name('sessions.create');
    Route::get('/',        [SessionController::class, 'index'])->name('sessions.index');
    Route::post('/',       [SessionController::class, 'store'])->name('sessions.store');
    Route::get('/{id}',    [SessionController::class, 'show'])->name('sessions.show');
    Route::put('/{id}',    [SessionController::class, 'update'])->name('sessions.update');
    Route::post('/{id}',   [SessionController::class, 'update'])->name('sessions.update'); // for method spoofing
    Route::post('/{id}/end', [SessionController::class, 'end'])->name('sessions.end');
});
 
/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
// Route::get('/emp', [UserController::class, 'index'])->name('emp');
// Route::get('/emp/list', [UserController::class, 'showUser'])->name('emp.emp'); // separated to avoid conflict
// Route::post('/users/add', [UserController::class, 'addUser'])->name('users.add');
// Route::put('/users/update/{id}', [UserController::class, 'updateUser'])->name('users.update');
// Route::put('/users/update-password/{id}', [UserController::class, 'updatePassword'])->name('users.password.update');
// Route::post('/users/toggle-status/{id}', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
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
// Route::get('/batches', [BatchesController::class, 'showBatches'])->name('batches');
// Route::post('/batches/add', [BatchesController::class, 'addBatch'])->name('batches.assign');

Route::prefix('batches')->name('batches.')->group(function () {
    Route::get('/', [BatchesController::class, 'showBatches'])->name('index');
    Route::post('/add', [BatchesController::class, 'addBatch'])->name('add');
    Route::post('/{id}/update-status', [BatchesController::class, 'updateStatus'])->name('updateStatus');
    Route::get('/{id}/edit', [BatchesController::class, 'edit'])->name('edit');
    Route::put('/{id}', [BatchesController::class, 'update'])->name('update');
    Route::delete('/{id}', [BatchesController::class, 'destroy'])->name('destroy');
});
