<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\InquiryController;

Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('inquiries.index')
        : redirect()->route('login');
})->name('home');

Route::middleware('auth')->prefix('inquiries')->group(function () {
    Route::get('/',        [InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/list',    [InquiryController::class, 'list'])->name('inquiries.list');
    Route::get('/create',  [InquiryController::class, 'create'])->name('inquiries.create');
    Route::post('/',       [InquiryController::class, 'store'])->name('inquiries.store');

    $idPattern = '([0-9]+|[0-9a-fA-F]{24})';
    Route::get('/{inquiry}/edit', [InquiryController::class, 'edit'])->where('inquiry', $idPattern)->name('inquiries.edit');
    Route::put('/{inquiry}',      [InquiryController::class, 'update'])->where('inquiry', $idPattern)->name('inquiries.update');
    Route::delete('/{inquiry}',   [InquiryController::class, 'destroy'])->where('inquiry', $idPattern)->name('inquiries.destroy');
    Route::get('/{inquiry}',      [InquiryController::class, 'show'])->where('inquiry', $idPattern)->name('inquiries.show');
    Route::post('/{inquiry}/status', [InquiryController::class, 'setStatus'])->where('inquiry', $idPattern)->name('inquiries.setStatus');
});
