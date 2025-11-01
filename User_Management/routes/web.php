<?php
 
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Student\InquiryController;
use App\Http\Controllers\Session\SessionController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Master\CoursesController;
use App\Http\Controllers\User\BatchesController;
use App\Http\Controllers\Master\FeesMasterController;
use App\Http\Controllers\Master\BatchController;
use App\Http\Controllers\Master\BranchController;
use App\Http\Controllers\Master\CalendarController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Master\OtherFeeController;
use App\Http\Controllers\Master\ScholarshipController;
use App\Http\Controllers\Student\PendingFeesController;
use App\Http\Controllers\Student\OnboardController;
use App\Http\Controllers\Student\PaymentController;
use App\Http\Controllers\Student\SmStudentsController;

// -------------------------
// Authentication Routes
// -------------------------
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// -------------------------
// Default Route
// -------------------------
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

// -------------------------
// Dashboard
// -------------------------
Route::get('/dashboard', function () {
    return view('auth.dashboard');
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| Session Management Routes
|--------------------------------------------------------------------------
*/
Route::prefix('session')->name('sessions.')->group(function () {
    Route::get('/', [SessionController::class, 'index'])->name('index');
    Route::get('/create', [SessionController::class, 'create'])->name('create');
    Route::post('/', [SessionController::class, 'store'])->name('store');
    Route::post('/update/{session}', [SessionController::class, 'update'])->name('update');
    Route::post('/end/{session}', [SessionController::class, 'end'])->name('end');
    Route::delete('/{session}', [SessionController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| User Management Routes
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
| Batches Assignment (In User Management)
|--------------------------------------------------------------------------
*/
Route::get('/batches', [BatchesController::class, 'showBatches'])->name('user.batches.batches');
Route::post('/batches/add', [BatchesController::class, 'addBatch'])->name('batches.assign');
Route::post('/batches/toggle-status/{id}', [BatchesController::class, 'toggleStatus'])->name('batches.toggleStatus');

/*
|--------------------------------------------------------------------------
| Master - Courses Routes
|--------------------------------------------------------------------------
*/
Route::prefix('courses')->name('courses.')->group(function () {
    Route::get('/', [CoursesController::class, 'index'])->name('index');
    Route::get('/create', [CoursesController::class, 'create'])->name('create');
    Route::post('/store', [CoursesController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [CoursesController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [CoursesController::class, 'update'])->name('update');
    Route::delete('/destroy/{id}', [CoursesController::class, 'destroy'])->name('destroy');
    Route::get('/download-sample', [CoursesController::class, 'downloadSampleFile'])->name('downloadSample');
    Route::post('/import', [CoursesController::class, 'importCourses'])->name('import');
    Route::get('/subject-suggestions', [CoursesController::class, 'getSubjectSuggestions'])->name('subjectSuggestions');
    Route::get('/valid-subjects', [CoursesController::class, 'getValidSubjects'])->name('validSubjects');
});

/*
|--------------------------------------------------------------------------
| Master - Batches Routes
|--------------------------------------------------------------------------
*/
Route::prefix('master/batch')->name('batches.')->group(function () {
    Route::get('/', [BatchController::class, 'index'])->name('index');
    Route::get('/download/sample', [BatchController::class, 'downloadSample'])->name('downloadSample');
    Route::post('/add', [BatchController::class, 'store'])->name('add');
    Route::put('/{id}/update', [BatchController::class, 'update'])->name('update');
    Route::post('/{id}/toggle-status', [BatchController::class, 'toggleStatus'])->name('toggleStatus');
});

/*
|--------------------------------------------------------------------------
| Master - Scholarship Routes
|--------------------------------------------------------------------------
*/
Route::prefix('master/scholarship')->name('master.scholarship.')->group(function () {
    Route::get('/', [ScholarshipController::class, 'index'])->name('index');
    Route::get('/data', [ScholarshipController::class, 'index'])->name('data');
    Route::post('/', [ScholarshipController::class, 'store'])->name('store');
    Route::get('/{id}', [ScholarshipController::class, 'show'])->name('show');
    Route::put('/{id}', [ScholarshipController::class, 'update'])->name('update');
    Route::patch('/{id}/toggle-status', [ScholarshipController::class, 'toggleStatus'])->name('toggleStatus');
    Route::delete('/{id}', [ScholarshipController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Master - Fees Master Routes
|--------------------------------------------------------------------------
*/
Route::prefix('fees-master')->name('fees.')->group(function () {
    Route::get('/', [FeesMasterController::class, 'index'])->name('index');
    Route::post('/', [FeesMasterController::class, 'store'])->name('store');
    Route::get('/{id}', [FeesMasterController::class, 'show'])->name('show');
    Route::patch('/{id}', [FeesMasterController::class, 'update'])->name('update');
    Route::patch('/{fee}/toggle', [FeesMasterController::class, 'toggle'])->name('toggle');
});

/*
|--------------------------------------------------------------------------
| Master - Other Fees Routes
|--------------------------------------------------------------------------
*/
Route::prefix('master/other_fees')->name('master.other_fees.')->group(function () {
    Route::get('/', [OtherFeeController::class, 'index'])->name('index');
    Route::get('/data', [OtherFeeController::class, 'index'])->name('data');
    Route::get('/{id}', [OtherFeeController::class, 'show'])->name('show');
    Route::post('/', [OtherFeeController::class, 'store'])->name('store');
    Route::put('/{id}', [OtherFeeController::class, 'update'])->name('update');
    Route::post('/{id}/toggle', [OtherFeeController::class, 'toggle'])->name('toggle');
    Route::delete('/{id}', [OtherFeeController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Master - Branch Management Routes
|--------------------------------------------------------------------------
*/
Route::prefix('master/branch')->name('branches.')->group(function () {
    Route::get('/', [BranchController::class, 'index'])->name('index');
    Route::post('/add', [BranchController::class, 'store'])->name('add');
    Route::put('/{id}/update', [BranchController::class, 'update'])->name('update');
    Route::post('/{id}/toggle-status', [BranchController::class, 'toggleStatus'])->name('toggleStatus');
});

/*
|--------------------------------------------------------------------------
| Session Management - Calendar Routes
|--------------------------------------------------------------------------
*/
Route::prefix('calendar')->name('calendar.')->group(function () {
    Route::get('/', [CalendarController::class, 'index'])->name('index');
    Route::get('/events', [CalendarController::class, 'getEvents'])->name('events');
    Route::post('/holidays', [CalendarController::class, 'storeHoliday'])->name('holidays.store');
    Route::delete('/holidays/{id}', [CalendarController::class, 'deleteHoliday'])->name('holidays.delete');
    Route::post('/tests', [CalendarController::class, 'storeTest'])->name('tests.store');
    Route::delete('/tests/{id}', [CalendarController::class, 'deleteTest'])->name('tests.delete');
    Route::post('/mark-sundays', [CalendarController::class, 'markSundays'])->name('mark.sundays');
});

/*
|--------------------------------------------------------------------------
| STUDENT MANAGEMENT ROUTES
|--------------------------------------------------------------------------
*/

// ========================================
// 1. Inquiry Management
// ========================================
Route::prefix('inquiries')->name('inquiries.')->group(function () {
    Route::get('/', [InquiryController::class, 'index'])->name('index');
    Route::get('/data', [InquiryController::class, 'data'])->name('data');
    Route::post('/', [InquiryController::class, 'store'])->name('store');
    Route::get('/{id}', [InquiryController::class, 'show'])->name('show');
    Route::get('/{id}/view', [InquiryController::class, 'view'])->name('view');
    Route::get('/{id}/edit', [InquiryController::class, 'edit'])->name('edit');
    Route::put('/{id}', [InquiryController::class, 'update'])->name('update');
    Route::delete('/{id}', [InquiryController::class, 'destroy'])->name('destroy');
    Route::post('/bulk-onboard', [InquiryController::class, 'bulkOnboard'])->name('bulkOnboard');
    
    // Scholarship routes
    Route::get('/{id}/scholarship', [InquiryController::class, 'showScholarshipDetails'])->name('scholarship.show');
    Route::put('/{id}/scholarship', [InquiryController::class, 'updateScholarshipDetails'])->name('scholarship.update');
    
    // Fees and batches routes
    Route::get('/{id}/fees-batches', [InquiryController::class, 'showFeesBatchesDetails'])->name('fees-batches.show');
    Route::put('/{id}/fees-batches', [InquiryController::class, 'updateFeesBatches'])->name('fees-batches.update');
});

// ========================================
// 2. Student Onboard Routes - DEFINED SEPARATELY (OUTSIDE prefix groups)
// ========================================
// This creates: student.onboard (for dashboard line 333)
Route::get('/student/onboard', [OnboardController::class, 'index'])->name('student.onboard');

// This creates: master.student.pending (for dashboard line 330)
Route::get('/master/student/pending', [OnboardController::class, 'index'])->name('master.student.pending');

// ========================================
// 3. Student Routes with Prefix
// ========================================
Route::prefix('student')->name('student.')->group(function () {
    
    // Creates: student.student.pending (for dashboard line 141)
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/pending', [OnboardController::class, 'index'])->name('pending');
    });
    
    // Onboard with full CRUD functionality - Creates: student.onboard.onboard
    Route::prefix('onboard')->name('onboard.')->group(function () {
        Route::get('/', [OnboardController::class, 'index'])->name('onboard');
        Route::get('/{id}', [OnboardController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [OnboardController::class, 'edit'])->name('edit');
        Route::put('/{id}', [OnboardController::class, 'update'])->name('update');
        
        // *** ADDED: Transfer student route ***
        Route::post('/{id}/transfer', [OnboardController::class, 'transfer'])->name('transfer');
    });
    
    // Pending Fees Students
    Route::prefix('pendingfees')->name('pendingfees.')->group(function () {
        Route::get('/', [PendingFeesController::class, 'index'])->name('pending');
        Route::get('/{id}', [PendingFeesController::class, 'view'])->name('view');
        Route::get('/{id}/edit', [PendingFeesController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PendingFeesController::class, 'update'])->name('update');
        
        // Payment routes
        Route::get('/{id}/pay', [PendingFeesController::class, 'pay'])->name('pay');
        Route::post('/{id}/pay', [PendingFeesController::class, 'processPayment'])->name('processPayment');
    });
    
    // Payment Routes (for Fees Collection)
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/{id}/pay', [PaymentController::class, 'pay'])->name('pay');
        Route::post('/{id}/pay', [PaymentController::class, 'processPayment'])->name('processPayment');
    });
});

// ========================================
// 4. Active Students Routes
// ========================================
Route::prefix('students')->name('students.')->group(function () {
    Route::get('/active', [StudentController::class, 'activeStudents'])->name('active');
    Route::post('/store', [StudentController::class, 'store'])->name('store');
    Route::post('/{id}/update-fees', [StudentController::class, 'updateFees'])->name('updateFees');
    Route::post('/convert/{inquiryId}', [StudentController::class, 'convertFromInquiry'])->name('convertFromInquiry');
});

// ========================================
// 5. SM Students (Student Management)
// ========================================
Route::prefix('smstudents')->name('smstudents.')->group(function () {
    Route::get('/', [SmStudentsController::class, 'index'])->name('index');
    Route::get('/export', [SmStudentsController::class, 'export'])->name('export');
    Route::get('/{id}', [SmStudentsController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [SmStudentsController::class, 'edit'])->name('edit');
    Route::put('/{id}', [SmStudentsController::class, 'update'])->name('update');
    Route::post('/{id}/update-password', [SmStudentsController::class, 'updatePassword'])->name('updatePassword');
    Route::post('/{id}/update-batch', [SmStudentsController::class, 'updateBatch'])->name('updateBatch');
    Route::post('/{id}/deactivate', [SmStudentsController::class, 'deactivate'])->name('deactivate');
    Route::get('/{id}/history', [SmStudentsController::class, 'history'])->name('history');
});