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
 
// Inquiry Management Routes
Route::prefix('inquiries')->name('inquiries.')->group(function () {
    Route::get('/', [InquiryController::class, 'index'])->name('index');
    Route::get('/data', [InquiryController::class, 'data'])->name('data');
    Route::get('/{id}', [InquiryController::class, 'show'])->name('show');
    Route::post('/', [InquiryController::class, 'store'])->name('store');
    Route::put('/{id}', [InquiryController::class, 'update'])->name('update');
    Route::delete('/{id}', [InquiryController::class, 'destroy'])->name('destroy');
    Route::post('/upload', [InquiryController::class, 'upload'])->name('upload');
    Route::post('/{id}/onboard', [InquiryController::class, 'processOnboard'])->name('onboard.process');
Route::get('/{id}/edit', [PendingFeesController::class, 'edit'])->name('student.pendingfees.edit');
 
});
 
 
Route::get('/students/pending', [StudentController::class, 'index'])
    ->name('student.student.pending');
 
Route::get('/student/pendingfees', [PendingFeesController::class, 'index'])
    ->name('student.pendingfees.pending');
 
 
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
 
// Fees Master Routes
Route::prefix('fees-master')->name('fees.')->group(function () {
    // List all fees
    Route::get('/', [FeesMasterController::class, 'index'])->name('index');
   
    // Create new fee
    Route::post('/', [FeesMasterController::class, 'store'])->name('store');
   
    // Show single fee details (for View modal)
    Route::get('/{id}', [FeesMasterController::class, 'show'])->name('show');
   
    // Update fee
    Route::patch('/{id}', [FeesMasterController::class, 'update'])->name('update');
   
    // Toggle status (Activate/Deactivate)
    Route::patch('/{fee}/toggle', [FeesMasterController::class, 'toggle'])->name('toggle');
});
 
 
// Other Fees Routes
Route::prefix('master/other_fees')->group(function () {
    Route::get('/', [OtherFeeController::class, 'index'])->name('master.other_fees.index');
    Route::get('/data', [OtherFeeController::class, 'index']);
    Route::get('/{id}', [OtherFeeController::class, 'show']);
    Route::post('/', [OtherFeeController::class, 'store']);
    Route::put('/{id}', [OtherFeeController::class, 'update']);
    Route::post('/{id}/toggle', [OtherFeeController::class, 'toggle']);
    Route::delete('/{id}', [OtherFeeController::class, 'destroy']);
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
 
 
//student onboard-pending inquiries routes
Route::get('/students/pending', [StudentController::class, 'index'])
    ->name('student.student.pending');
 
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
 
// Additional route for active students
Route::get('/students/active', [StudentController::class, 'activeStudents'])
    ->name('students.active');
 
 
 
// Additional route for active students (alternative naming)
Route::get('/students/active', [StudentController::class, 'activeStudents'])
    ->name('students.active');
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
 
 
//pending fees students routes
Route::prefix('student/pendingfees')->name('student.pendingfees.')->group(function () {
    Route::get('/', [App\Http\Controllers\Student\PendingFeesController::class, 'index'])->name('pending');
    Route::get('/{id}/edit', [App\Http\Controllers\Student\PendingFeesController::class, 'edit'])->name('edit');
    Route::put('/{id}', [App\Http\Controllers\Student\PendingFeesController::class, 'update'])->name('update');
    Route::get('/{id}', [App\Http\Controllers\Student\PendingFeesController::class, 'show'])->name('view');
});
 
Route::get('/student/pending', [PendingFeesController::class, 'index'])->name('student.pendingfees.pending');
Route::get('/student/pending', [StudentController::class, 'index'])->name('student.student.pending');
Route::get('/student/edit/{id}', [StudentController::class, 'edit'])->name('student.student.edit');
 
 
// Payment Routes
Route::prefix('student/payment')->name('student.payment.')->group(function () {
    // Show payment page - using 'pay' as the route name
    Route::get('/{id}/pay', [App\Http\Controllers\Student\PaymentController::class, 'showPaymentPage'])
        ->name('pay');
   
    // Process payment
    Route::post('/{id}/process', [App\Http\Controllers\Student\PaymentController::class, 'processPayment'])
        ->name('process');
   
    // View payment history
    Route::get('/{id}/history', [App\Http\Controllers\Student\PaymentController::class, 'viewHistory'])
        ->name('history');
});
 
 
// Onboard Routes
Route::prefix('student/onboard')->name('student.onboard.')->group(function () {
    // List onboarded students
    Route::get('/', [App\Http\Controllers\Student\OnboardController::class, 'index'])
        ->name('index');
   
    // View onboarded student
    Route::get('/{id}', [App\Http\Controllers\Student\OnboardController::class, 'show'])
        ->name('show');
   
    // Edit onboarded student
    Route::get('/{id}/edit', [App\Http\Controllers\Student\OnboardController::class, 'edit'])
        ->name('edit');
   
    // Update onboarded student
    Route::put('/{id}', [App\Http\Controllers\Student\OnboardController::class, 'update'])
        ->name('update');
});
 
// Update the existing onboard route to use the controller
Route::get('/student/onboard', [App\Http\Controllers\Student\OnboardController::class, 'index'])
    ->name('student.onboard');
 
// Update the existing onboard route to use the controller
Route::get('/student/onboard', [App\Http\Controllers\Student\OnboardController::class, 'index'])
    ->name('student.onboard');
 
 
 