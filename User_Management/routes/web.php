<?php
 
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
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
use App\Http\Controllers\Student\PendingController;
use App\Http\Controllers\Student\SmStudentsController;
use App\Http\Controllers\Student\PaymentController;
use App\Http\Controllers\Attendance\EmployeeController;
use App\Http\Controllers\FeesManagementController;
use App\Http\Controllers\Attendance\StudentAController;
use App\Http\Controllers\TestSeries\TestSeriesController;



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

Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

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
Route::get('/emp', [UserController::class, 'index'])->name('user.emp.emp');
Route::post('/users/add', [UserController::class, 'addUser'])->name('users.add');
Route::put('/users/update/{id}', [UserController::class, 'updateUser'])->name('users.update');
Route::put('/users/{id}/update-password', [UserController::class, 'updatePassword'])->name('users.password.update');
Route::post('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
Route::post('/users/store', [UserController::class, 'addUser'])->name('users.store');
Route::get('/users/export', [UserController::class, 'exportToExcel'])->name('users.export');
Route::get('/users/sample-download', [UserController::class, 'downloadSample'])->name('users.downloadSample');
Route::post('/users/import', [UserController::class, 'import'])->name('users.import');

/*
|--------------------------------------------------------------------------
| Batches (In User Management) Routes
|--------------------------------------------------------------------------
*/
Route::get('/batches', [BatchesController::class, 'showBatches'])->name('user.batches.batches');
Route::post('/batches/add', [BatchesController::class, 'addBatch'])->name('batches.assign');
Route::post('/batches/{id}/toggle-status', [BatchesController::class, 'toggleStatus'])->name('user.batches.toggleStatus');

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
| Batches (In Master) Routes - Using batches.index for compatibility
|--------------------------------------------------------------------------
*/
Route::prefix('master/batch')->name('batches.')->group(function () {
    Route::get('/', [BatchController::class, 'index'])->name('index');
    Route::post('/add', [BatchController::class, 'store'])->name('add');
    Route::put('/{id}/update', [BatchController::class, 'update'])->name('update');
   Route::post('/{id}/toggle-status', [BatchController::class, 'toggleStatus'])->name('toggleStatus');
    Route::get('/export', [BatchController::class, 'exportToExcel'])->name('export');
    Route::get('/download-sample', [BatchController::class, 'downloadSample'])->name('downloadSample');
    Route::post('/import', [BatchController::class, 'import'])->name('import');
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
    Route::put('/{id}', [FeesMasterController::class, 'update'])->name('update');
    Route::patch('/{id}/toggle', [FeesMasterController::class, 'toggle'])->name('toggle');
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
Route::prefix('master/branch')->group(function () {
    Route::get('/', [BranchController::class, 'index'])->name('branches.index');
    Route::post('/add', [BranchController::class, 'store'])->name('branches.add');
    Route::put('/{id}', [BranchController::class, 'update'])->name('branches.update');
    Route::post('/{id}/toggle-status', [BranchController::class, 'toggleStatus'])->name('branches.toggleStatus');
    Route::get('/sample-download', [BranchController::class, 'downloadSample'])->name('branches.downloadSample');
    Route::post('/import', [BranchController::class, 'import'])->name('branches.import');
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
| Student Management Routes
|--------------------------------------------------------------------------
*/

// PENDING STUDENTS (Incomplete Onboarding Forms)
Route::prefix('student/pending')->name('student.student.')->group(function () {
    Route::get('/', [StudentController::class, 'index'])->name('pending');
    Route::get('/{id}/edit', [StudentController::class, 'edit'])->name('edit');
    Route::put('/{id}', [StudentController::class, 'update'])->name('update');
});

// ONBOARDED STUDENTS (Complete Forms, Ready for Fees)
Route::prefix('student/onboard')->name('student.onboard.')->group(function () {
    Route::get('/', [OnboardController::class, 'index'])->name('onboard');
    Route::get('/{id}', [OnboardController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [OnboardController::class, 'edit'])->name('edit');
    Route::put('/{id}', [OnboardController::class, 'update'])->name('update');
    Route::post('/{id}/transfer', [OnboardController::class, 'transfer'])->name('transfer');
});

Route::get('/initialize-onboard-history', [OnboardController::class, 'initializeHistory'])->name('onboard.initialize.history');

// PENDING FEES STUDENTS
Route::prefix('student/pendingfees')->name('student.pendingfees.')->group(function () {
    Route::get('/', [PendingFeesController::class, 'index'])->name('pending'); 
    Route::get('/{id}/view', [PendingFeesController::class, 'view'])->name('view');
    Route::get('/{id}/edit', [PendingFeesController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PendingFeesController::class, 'update'])->name('update');
    Route::get('/{id}/history', [PendingFeesController::class, 'getHistory'])->name('history');
    Route::get('/{id}/pay', [PendingFeesController::class, 'pay'])->name('pay');
    Route::post('/{id}/pay', [PendingFeesController::class, 'processPayment'])->name('processPayment');
});

// ACTIVE STUDENTS (SM Students)
Route::prefix('smstudents')->name('smstudents.')->group(function () {
    Route::get('/', [SmStudentsController::class, 'index'])->name('index');
    Route::get('/export', [SmStudentsController::class, 'export'])->name('export');
    Route::get('/{id}/edit', [SmStudentsController::class, 'edit'])->name('edit');
    Route::get('/{id}/history', [SmStudentsController::class, 'history'])->name('history');
    Route::get('/{id}/testseries', [SmStudentsController::class, 'testSeries'])->name('testseries');
    Route::get('/{id}/debug', [SmStudentsController::class, 'debug'])->name('debug');
    Route::put('/{id}', [SmStudentsController::class, 'update'])->name('update');
    Route::post('/{id}/update-batch', [SmStudentsController::class, 'updateBatch'])->name('updateBatch');
    Route::post('/{id}/update-shift', [SmStudentsController::class, 'updateShift'])->name('updateShift');
    Route::post('/{id}/update-password', [SmStudentsController::class, 'updatePassword'])->name('updatePassword');
    Route::post('/{id}/deactivate', [SmStudentsController::class, 'deactivate'])->name('deactivate');
    Route::get('/{id}', [SmStudentsController::class, 'show'])->name('show');
});

Route::get('/onboard/transfer/{id}', [OnboardController::class, 'transferToStudents'])->name('onboard.transfer');

/*
|--------------------------------------------------------------------------
| Inquiry Management Routes
|--------------------------------------------------------------------------
*/
Route::prefix('inquiries')->name('inquiries.')->group(function () {
    Route::get('/', [InquiryController::class, 'index'])->name('index');
    Route::get('/data', [InquiryController::class, 'data'])->name('data');
    Route::get('/get-data', [InquiryController::class, 'getData'])->name('get-data');
    Route::post('/upload', [InquiryController::class, 'upload'])->name('upload');
    Route::post('/', [InquiryController::class, 'store'])->name('store');
    Route::post('/bulk-onboard', [InquiryController::class, 'bulkOnboard'])->name('bulk-onboard');
    Route::post('/{id}/single-onboard', [InquiryController::class, 'singleOnboard'])->name('single-onboard');
    Route::get('/{id}/onboard', [InquiryController::class, 'showOnboardForm'])->name('onboard');
    Route::get('/{id}/history', [InquiryController::class, 'getHistory'])->name('history');
    Route::get('/{id}/edit', [InquiryController::class, 'edit'])->name('edit');
    Route::get('/{id}/scholarship', [InquiryController::class, 'showScholarshipDetails'])->name('scholarship.show');
    Route::put('/{id}/scholarship', [InquiryController::class, 'updateScholarshipDetails'])->name('scholarship.update');
    Route::get('/{id}/fees-batches', [InquiryController::class, 'showFeesBatchesDetails'])->name('fees-batches.show');
    Route::put('/{id}/fees-batches', [InquiryController::class, 'updateFeesBatches'])->name('fees-batches.update');
    Route::get('/{id}/view', [InquiryController::class, 'view'])->name('view');
    Route::get('/{id}', [InquiryController::class, 'show'])->name('show');
    Route::put('/{id}', [InquiryController::class, 'update'])->name('update');
    Route::delete('/{id}', [InquiryController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Attendance Management Routes
|--------------------------------------------------------------------------
*/
Route::prefix('attendance/employee')->name('attendance.employee.')->group(function () {
    
    // Main index page
    Route::get('/', [EmployeeController::class, 'index'])
        ->name('index');
    
    // Get attendance data (AJAX)
    Route::get('/data', [EmployeeController::class, 'getData'])
        ->name('data');
    
    // Mark individual attendance
    Route::post('/mark', [EmployeeController::class, 'markAttendance'])
        ->name('mark');
    
    // Mark all attendance (bulk)
    Route::post('/mark-all', [EmployeeController::class, 'markAllAttendance'])
        ->name('mark.all');
    
    // Export attendance (optional - for future)
    Route::get('/export', [EmployeeController::class, 'exportAttendance'])
        ->name('export');

       // Monthly Attendance (Simple Table View)
        Route::get('/monthly', [EmployeeController::class, 'monthly'])->name('monthly');
        Route::get('/monthly/data', [EmployeeController::class, 'getMonthlyData'])->name('monthly.data');
        Route::get('/monthly/details', [EmployeeController::class, 'monthlyDetails'])->name('monthly.details');
});

/*
|--------------------------------------------------------------------------
| Fees Management Routes
|--------------------------------------------------------------------------
*/
// Main page - accessible at /fees-management
Route::get('/fees-management', [FeesManagementController::class, 'index'])->name('fees.management.index');

// Search Student
Route::post('/fees-management/search-student', function (\Illuminate\Http\Request $request) {
    $searchTerm = $request->input('search');
    
    // Add your search logic here
    $students = \DB::table('students')
        ->where('name', 'LIKE', "%{$searchTerm}%")
        ->orWhere('roll_no', 'LIKE', "%{$searchTerm}%")
        ->get();
    
    return response()->json([
        'success' => true,
        'data' => $students
    ]);
})->name('fees.collect.search');

// Search by Status
Route::post('/fees-management/search-status', function (\Illuminate\Http\Request $request) {
    $courseId = $request->input('course_id');
    $batchId = $request->input('batch_id');
    $feeStatus = $request->input('fee_status');
    
    // Add your search logic here
    $students = [];
    
    return response()->json([
        'success' => true,
        'data' => $students
    ]);
})->name('fees.status.search');

// Filter Transactions
Route::post('/fees-management/filter-transactions', function (\Illuminate\Http\Request $request) {
    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');
    
    // Add your filter logic here
    $transactions = [];
    
    return response()->json([
        'success' => true,
        'data' => $transactions
    ]);
})->name('fees.transaction.filter');

// Export Pending Fees
Route::get('/fees-management/export-pending', function () {
    return response()->json([
        'message' => 'Export functionality will be added soon'
    ]);
})->name('fees.export');


// Fees Management Routes
Route::prefix('fees')->name('fees.')->group(function () {
    Route::post('/collect/search', [FeesManagementController::class, 'searchStudent'])->name('collect.search');
    Route::post('/status/search', [FeesManagementController::class, 'searchByStatus'])->name('status.search');
    Route::post('/transaction/filter', [FeesManagementController::class, 'filterTransactions'])->name('transaction.filter');
    Route::post('/batches-by-course', [FeesManagementController::class, 'getBatchesByCourse'])->name('batches.by.course');
    Route::get('/export', [FeesManagementController::class, 'exportPendingFees'])->name('export');


});
// Profile Routes
Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
});



Route::prefix('attendance/student')->name('attendance.student.')->group(function () {
    
    // Main index page (Daily Attendance)
    Route::get('/', [StudentAController::class, 'index'])
        ->name('index');
    
    // Get attendance data (AJAX)
    Route::get('/data', [StudentAController::class, 'getData'])
        ->name('data');
    
    // Mark individual attendance
    Route::post('/mark', [StudentAController::class, 'markAttendance'])
        ->name('mark');
    
    // Mark all attendance (bulk)
    Route::post('/mark-all', [StudentController::class, 'markAllAttendance'])
        ->name('mark.all');
    
    // Monthly Attendance (Simple Table View)
    Route::get('/monthly', [StudentAController::class, 'monthly'])
        ->name('monthly');
    
    Route::get('/monthly/data', [StudentAController::class, 'getMonthlyData'])
        ->name('monthly.data');
    
    Route::get('/monthly/details', [StudentAController::class, 'monthlyDetails'])
        ->name('monthly.details');
    
    // Export attendance (optional - for future)
    Route::get('/export', [StudentAController::class, 'exportAttendance'])
        ->name('export');
});


Route::prefix('test-series')->name('test_series.')->group(function () {
    Route::get('/', [TestSeriesController::class, 'index'])->name('index');
    Route::get('/course/{courseName}', [TestSeriesController::class, 'show'])->name('show');
    Route::post('/', [TestSeriesController::class, 'store'])->name('store');
    Route::put('/{id}', [TestSeriesController::class, 'update'])->name('update');
    Route::delete('/{id}', [TestSeriesController::class, 'destroy'])->name('destroy');
});