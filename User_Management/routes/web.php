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
Route::get('/batches', [BatchesController::class, 'showBatches'])->name('user.batches.batches');
Route::post('/batches/add', [BatchesController::class, 'addBatch'])->name('batches.assign');
Route::post('/batches/toggle-status/{id}', [BatchesController::class, 'toggleStatus'])->name('batches.toggleStatus');

/*
|--------------------------------------------------------------------------
| Courses Routes
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
| Batches (In Master) Routes
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
| Fees Master Routes
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
| Other Fees Routes
|--------------------------------------------------------------------------
*/
Route::prefix('master/other_fees')->group(function () {
    Route::get('/', [OtherFeeController::class, 'index'])->name('master.other_fees.index');
    Route::get('/data', [OtherFeeController::class, 'index']);
    Route::get('/{id}', [OtherFeeController::class, 'show']);
    Route::post('/', [OtherFeeController::class, 'store']);
    Route::put('/{id}', [OtherFeeController::class, 'update']);
    Route::post('/{id}/toggle', [OtherFeeController::class, 'toggle']);
    Route::delete('/{id}', [OtherFeeController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Branch Routes
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
| Calendar Management Routes
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
| Scholarship Routes
|--------------------------------------------------------------------------
*/
Route::prefix('master')->name('master.')->group(function () {
    Route::get('/scholarship', [ScholarshipController::class, 'index'])->name('scholarship.index');
    Route::get('/scholarship/data', [ScholarshipController::class, 'index'])->name('scholarship.data');
    Route::post('/scholarship', [ScholarshipController::class, 'store'])->name('scholarship.store');
    Route::get('/scholarship/{id}', [ScholarshipController::class, 'show'])->name('scholarship.show');
    Route::put('/scholarship/{id}', [ScholarshipController::class, 'update'])->name('scholarship.update');
    Route::patch('/scholarship/{id}/toggle-status', [ScholarshipController::class, 'toggleStatus']);
    Route::delete('/scholarship/{id}', [ScholarshipController::class, 'destroy'])->name('scholarship.destroy');
});

/*
|--------------------------------------------------------------------------
| STUDENT MANAGEMENT ROUTES
|--------------------------------------------------------------------------
*/

// ========================================
// 1. PENDING INQUIRY STUDENTS (status = 'pending_fees', incomplete forms)
// ========================================
Route::prefix('students')->name('student.student.')->group(function () {
    Route::get('/pending', [StudentController::class, 'index'])->name('pending');
    Route::get('/{id}/edit', [StudentController::class, 'edit'])->name('edit');
    Route::put('/{id}', [StudentController::class, 'update'])->name('update');
    Route::get('/{id}', [StudentController::class, 'show'])->name('show');
});

// ========================================
// 2. ONBOARDED STUDENTS
// ========================================
Route::prefix('student/onboard')->name('student.onboard.')->group(function () {
    Route::get('/', [OnboardController::class, 'index'])->name('onboard');
    Route::get('/{id}', [OnboardController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [OnboardController::class, 'edit'])->name('edit');
    Route::put('/{id}', [OnboardController::class, 'update'])->name('update');
});

// ========================================
// 3. PENDING FEES STUDENTS
// ========================================
Route::prefix('student/pendingfees')->name('student.pendingfees.')->group(function () {
    Route::get('/', [PendingFeesController::class, 'index'])->name('pending');
    Route::get('/{id}/edit', [PendingFeesController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PendingFeesController::class, 'update'])->name('update');
    Route::get('/{id}', [PendingFeesController::class, 'view'])->name('view');
});

// ========================================
// 4. ACTIVE STUDENTS
// ========================================
Route::get('/students/active', [StudentController::class, 'activeStudents'])->name('students.active');

// Onboarded Students Routes (from onboarded_students collection)
Route::prefix('student/onboard')->name('student.onboard.')->group(function () {
    Route::get('/', [OnboardController::class, 'index'])->name('onboard');
    Route::get('/{id}', [OnboardController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [OnboardController::class, 'edit'])->name('edit');
    Route::put('/{id}', [OnboardController::class, 'update'])->name('update');
});

//transfer Logic 

// Individual student transfer
Route::post('/student/onboard/{id}/transfer', [OnboardController::class, 'transferToPending'])
    ->name('student.onboard.transfer');

// Bulk transfer all students
Route::post('/student/onboard/transfer-all', [OnboardController::class, 'transferAllToPending'])
    ->name('student.onboard.transfer-all');

// Students Management Routes
Route::prefix('smstudents')->name('smstudents.')->group(function () {
    
    // List all students
    Route::get('/', [SmStudentsController::class, 'index'])->name('index');
    
    // Export students to CSV
    Route::get('/export', [SmStudentsController::class, 'export'])->name('export');
    
    // *** ADD THIS NEW ROUTE FOR EDIT FORM ***
    Route::get('/{id}/edit', [SmStudentsController::class, 'edit'])->name('edit');
    
    // View single student details
    Route::get('/{id}', [SmStudentsController::class, 'show'])->name('show');
    
    // Update student details
    Route::post('/{id}/update', [SmStudentsController::class, 'update'])->name('update');
    
    // Update student password
    Route::post('/{id}/password', [SmStudentsController::class, 'updatePassword'])->name('updatePassword');
    
    // Update student batch
    Route::post('/{id}/batch', [SmStudentsController::class, 'updateBatch'])->name('updateBatch');
    
    // Deactivate student
    Route::post('/{id}/deactivate', [SmStudentsController::class, 'deactivate'])->name('deactivate');
    
    // Student history
    Route::get('/{id}/history', [SmStudentsController::class, 'history'])->name('history');
});

Route::post('/students/store', [StudentController::class, 'store'])->name('students.store');
Route::post('/students/{id}/update-fees', [StudentController::class, 'updateFees'])->name('students.updateFees');
Route::post('/students/convert/{inquiryId}', [StudentController::class, 'convertFromInquiry'])->name('students.convertFromInquiry');

Route::get('/inquiries/{id}', [InquiryController::class, 'view'])->name('inquiries.view');
Route::get('/inquiries/{id}/edit', [InquiryController::class, 'edit'])->name('inquiries.edit');
Route::put('/inquiries/{id}', [InquiryController::class, 'update'])->name('inquiries.update');
<<<<<<< HEAD
=======

Route::get('/inquiries/{id}/scholarship', [InquiryController::class, 'showScholarshipDetails'])
    ->name('inquiries.scholarship.show');

Route::put('/inquiries/{id}/scholarship', [InquiryController::class, 'updateScholarshipDetails'])
    ->name('inquiries.scholarship.update');

// Your existing inquiry routes
Route::get('/inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
Route::get('/inquiries/{id}/edit', [InquiryController::class, 'edit'])->name('inquiries.edit');
Route::put('/inquiries/{id}', [InquiryController::class, 'update'])->name('inquiries.update');

// Show fees and batches details 
Route::get('/inquiries/{id}/fees-batches', [InquiryController::class, 'showFeesBatchesDetails'])
    ->name('inquiries.fees-batches.show');

// Update fees and batches (if you need to save batch selection later)
Route::put('/inquiries/{id}/fees-batches', [InquiryController::class, 'updateFeesBatches'])
    ->name('inquiries.fees-batches.update');

    
// ========================================
// 5. PAYMENT ROUTES
// ========================================

// Payment Routes
Route::prefix('student/payment')->name('student.payment.')->group(function () {
    // Show payment page
    Route::get('/{id}', [PaymentController::class, 'showPaymentPage'])->name('show');
    
    // Process payment
    Route::post('/{id}/process', [PaymentController::class, 'processPayment'])->name('process');
    
    // View payment history
    Route::get('/{id}/history', [PaymentController::class, 'viewHistory'])->name('history');
    
    // Download receipt
    Route::get('/receipt/{paymentId}', [PaymentController::class, 'downloadReceipt'])->name('receipt');
});

// Debug route - add this temporarily to test
Route::get('/test-payment/{id}', function($id) {
    return "Payment route working for ID: " . $id;
})->name('test.payment');
>>>>>>> f511f3813e8a0d0efe4af9d77513ecef0a386326
