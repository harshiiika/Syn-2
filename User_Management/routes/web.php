<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Student\InquiryController;
use App\Http\Controllers\Session\SessionController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Master\CoursesController;
use App\Http\Controllers\User\BatchesController;
use App\Http\Controllers\fees\FeesMasterController;
use App\Http\Controllers\Master\BatchController;
use App\Http\Controllers\Master\BranchController;
use App\Http\Controllers\Master\CalendarController;
use App\Http\Controllers\Master\StudentController;
use App\Http\Controllers\Master\StudentOnboardController;



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
| Inquiry Routes
|--------------------------------------------------------------------------
*/

Route::prefix('inquiries')->group(function () { 
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
    Route::post('/bulk-onboard', [InquiryController::class, 'bulkOnboard'])
        ->name('inquiries.bulkOnboard');
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
| Batches (In User Management) Routes
|--------------------------------------------------------------------------
*/
Route::get('/batches', [BatchesController::class, 'showBatches'])
    ->name('user.batches.batches');
Route::post('/batches/add', [BatchesController::class, 'addBatch'])->name('batches.assign');
Route::post('/batches/toggle-status/{id}', [BatchesController::class, 'toggleStatus'])
    ->name('batches.toggleStatus');

Route::prefix('courses')->name('courses.')->group(function () {
    Route::get('/', [CoursesController::class, 'index'])->name('index');
    Route::get('/create', [CoursesController::class, 'create'])->name('create');
    Route::post('/store', [CoursesController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [CoursesController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [CoursesController::class, 'update'])->name('update');
    Route::delete('/destroy/{id}', [CoursesController::class, 'destroy'])->name('destroy');
});

//Batches (In master) Routes
Route::prefix('master/batch')->name('batches.')->group(function () {
    // Display all batches
    Route::get('/', [BatchController::class, 'index'])->name('index');
    
    // Download sample Excel file (BEFORE {id} route)
    Route::get('/download/sample', [BatchController::class, 'downloadSample'])->name('downloadSample');
    
    // Add new batch
    Route::post('/add', [BatchController::class, 'store'])->name('add');
    
    // Update batch details
    Route::put('/{id}/update', [BatchController::class, 'update'])->name('update');
    
    // Toggle batch status (Active/Inactive)
    Route::post('/{id}/toggle-status', [BatchController::class, 'toggleStatus'])->name('toggleStatus');
    
});

//feesmaster//

Route::prefix('fees')->name('fees.')->group(function () {
    Route::get('/', [FeesMasterController::class, 'index'])->name('index');
    Route::post('/', [FeesMasterController::class, 'store'])->name('store');
    Route::get('/{fee}', [FeesMasterController::class, 'show'])->name('show');
    Route::patch('/{fee}', [FeesMasterController::class, 'update'])->name('update');
    Route::patch('/{fee}/toggle-status', [FeesMasterController::class, 'toggleStatus'])->name('toggle');
});

//Batches Routes
Route::prefix('master/branch')->name('branches.')->group(function () {
    // Display all branches
    Route::get('/', [BranchController::class, 'index'])->name('index');

    // Add new branch
    Route::post('/add', [BranchController::class, 'store'])->name('add');

    // Update branch details
    Route::put('/{id}/update', [BranchController::class, 'update'])->name('update');

    // Toggle branch status (Active/Inactive)
    Route::post('/{id}/toggle-status', [BranchController::class, 'toggleStatus'])->name('toggleStatus');

});

// Calendar Management Routes
Route::prefix('calendar')->name('calendar.')->group(function () {
    // Main calendar page
    Route::get('/', [CalendarController::class, 'index'])->name('index');
   
    // Get all events for FullCalendar
    Route::get('/events', [CalendarController::class, 'getEvents'])->name('events');
   
    // Holiday routes
    Route::post('/holidays', [CalendarController::class, 'storeHoliday'])->name('holidays.store');
    Route::delete('/holidays/{id}', [CalendarController::class, 'deleteHoliday'])->name('holidays.delete');
   
    // Test routes (PLURAL - important!)
    Route::post('/tests', [CalendarController::class, 'storeTest'])->name('tests.store');
    Route::delete('/tests/{id}', [CalendarController::class, 'deleteTest'])->name('tests.delete');
   
    // Mark all Sundays
    Route::post('/mark-sundays', [CalendarController::class, 'markSundays'])->name('mark.sundays');
});
 

// Route::prefix('pending')->name('pending.')->group(function () {
//     Route::get('/', [StudentController::class, 'index'])->name('index');
//     Route::post('/', [StudentController::class, 'store'])->name('store');
//     Route::get('/search/query', [StudentController::class, 'search'])->name('search');
//     Route::get('/{id}', [StudentController::class, 'show'])->name('show');
//     Route::put('/{student}', [StudentController::class, 'update'])->name('update');
// });


Route::prefix('pending')->name('pending.')->group(function () {
    // Display all pending students
    Route::get('/', [StudentController::class, 'index'])->name('index');
    
    // Store new student (direct entry)
    Route::post('/', [StudentController::class, 'store'])->name('store');
    
    // Search students
    Route::get('/search/query', [StudentController::class, 'search'])->name('search');
    
    // Show student details
    Route::get('/{id}', [StudentController::class, 'show'])->name('show');
    
    // Update student
    Route::put('/{student}', [StudentController::class, 'update'])->name('update');
});

/*
|--------------------------------------------------------------------------
| Student Management Routes (Main Flow)
|--------------------------------------------------------------------------
*/
// Student Onboard Routes (after converting from inquiry)
Route::prefix('students')->name('students.')->group(function () {
    // View all onboarded students (pending fees)
    Route::get('/pending', [StudentController::class, 'index'])->name('pending');
    
    // View students with completed fees
    Route::get('/active', [StudentController::class, 'activeStudents'])->name('active');
    
    // Show individual student details
    Route::get('/{id}', [StudentController::class, 'show'])->name('show');
    
    // Update student details
    Route::put('/{id}', [StudentController::class, 'update'])->name('update');
    
    // Update fees
    Route::post('/{id}/update-fees', [StudentController::class, 'updateFees'])->name('update_fees');
});

// Keep this route for the main pending page
Route::get('/student-management/pending', [StudentController::class, 'index'])
    ->name('master.student.pending');

// Route for onboarded students page
Route::get('/student-management/onboard', [StudentController::class, 'activeStudents'])
    ->name('student.onboard');


Route::prefix('student-management')->group(function () {
    Route::get('/onboard', [StudentOnboardController::class, 'index'])->name('student.onboard');
    Route::get('/onboard/{id}', [StudentOnboardController::class, 'show'])->name('students.show');
    Route::get('/onboard/{id}/edit', [StudentOnboardController::class, 'edit'])->name('students.edit');
    Route::put('/onboard/{id}', [StudentOnboardController::class, 'update'])->name('students.update');
    Route::get('/onboard/{id}/transfer', [StudentOnboardController::class, 'transfer'])->name('students.transfer');
    Route::get('/onboard/{id}/history', [StudentOnboardController::class, 'history'])->name('students.history');
});

Route::get('/pending', [StudentController::class, 'index'])->name('master.student.pending');


