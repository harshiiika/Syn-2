<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
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

// Courses Routes
Route::prefix('courses')->name('courses.')->group(function () {
    Route::get('/', [CoursesController::class, 'index'])->name('index');
    Route::get('/create', [CoursesController::class, 'create'])->name('create');
    Route::post('/store', [CoursesController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [CoursesController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [CoursesController::class, 'update'])->name('update');
    Route::delete('/destroy/{id}', [CoursesController::class, 'destroy'])->name('destroy');
    
    // Bulk import routes
    Route::get('/download-sample', [CoursesController::class, 'downloadSampleFile'])->name('downloadSample');
    Route::post('/import', [CoursesController::class, 'importCourses'])->name('import');
    
    // Subject validation routes
    Route::get('/subject-suggestions', [CoursesController::class, 'getSubjectSuggestions'])->name('subjectSuggestions');
    Route::get('/valid-subjects', [CoursesController::class, 'getValidSubjects'])->name('validSubjects'); // NEW
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

//branch Routes
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
 


Route::get('/students/pending', [StudentController::class, 'index'])
    ->name('master.student.pending');

// Active/Onboarded Students (fully paid students)
Route::get('/students/onboard', [StudentController::class, 'activeStudents'])
    ->name('student.onboard');

// Pending Fees Students (partial payment students)
Route::get('/students/pending-fees', [StudentController::class, 'pendingFees'])
    ->name('students.pending_fees');

// Show single student details
Route::get('/students/{id}', [StudentController::class, 'show'])
    ->name('students.show');

// Store new student (direct entry)
Route::post('/students/store', [StudentController::class, 'store'])
    ->name('students.store');

// Update student details
Route::put('/students/{id}/update', [StudentController::class, 'update'])
    ->name('students.update');

// Update student fees (collect payment)
Route::post('/students/{id}/update-fees', [StudentController::class, 'updateFees'])
    ->name('students.updateFees');

// Convert inquiry to student
Route::post('/students/convert/{inquiryId}', [StudentController::class, 'convertFromInquiry'])
    ->name('students.convertFromInquiry');

// Additional route for active students (alternative naming)
Route::get('/students/active', [StudentController::class, 'activeStudents'])
    ->name('students.active');