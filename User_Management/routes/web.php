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
use App\Http\Controllers\study_material\Unitscontroller;
use App\Http\Controllers\study_material\DispatchController;
use App\Http\Controllers\Reports\AttendanceReportController;



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
    Route::get('/{id}', [OnboardController::class, 'show'])->name('show'); //   Remove /view
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

// ========================================
// 4. ACTIVE STUDENTS (SM Students)
// ========================================

// Student Management Routes with Course Collection Support
Route::prefix('smstudents')->name('smstudents.')->group(function () {
    Route::get('/export', [SmStudentsController::class, 'export'])->name('export');

    Route::get('/', [SmStudentsController::class, 'index'])->name('index');
    Route::get('/course/{courseName}', [SmStudentsController::class, 'showByCourse'])->name('byCourse');
    Route::get('/statistics', [SmStudentsController::class, 'getCourseStatistics'])->name('statistics');
    Route::get('/collections', [SmStudentsController::class, 'listCourseCollections'])->name('collections');
    Route::get('/{id}', [SmStudentsController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [SmStudentsController::class, 'edit'])->name('edit');
    Route::put('/{id}', [SmStudentsController::class, 'update'])->name('update');
    Route::post('/{id}/update-password', [SmStudentsController::class, 'updatePassword'])->name('updatePassword');
    Route::post('/{id}/update-shift', [SmStudentsController::class, 'updateShift'])->name('updateShift');
    Route::post('/{id}/update-batch', [SmStudentsController::class, 'updateBatch'])->name('updateBatch');
    Route::get('/{id}/history', [SmStudentsController::class, 'getHistory'])->name('history');
Route::get('/student/{id}/attendance', [SmStudentsController::class, 'studentAttendance'])
    ->name('smstudents.attendance');
    Route::get('/{id}/attendance/data', [SmStudentsController::class, 'getStudentAttendanceData'])
        ->name('attendance.data');
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


// In routes/web.php
Route::prefix('test-series')->name('test_series.')->group(function () {
    // Main index page (Test Master - Image 1)
    Route::get('/', [TestSeriesController::class, 'index'])->name('index');

    Route::get('/create', [TestSeriesController::class, 'create'])->name('create');
    Route::post('/', [TestSeriesController::class, 'store'])->name('store');
    Route::get('/{course}', [TestSeriesController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [TestSeriesController::class, 'edit'])->name('edit');

    
    // Course specific test series page (Image 2)
    Route::get('/course/{courseName}', [TestSeriesController::class, 'show'])->name('show');
    
    // Create new test series (Image 3 modal)
    Route::post('/', [TestSeriesController::class, 'store'])->name('store');
    
    // Get test data for edit modal (Image 4)
    Route::get('/{id}/edit', [TestSeriesController::class, 'edit'])->name('edit');
    
    // Update test series (Image 4 modal submit)

    Route::put('/{id}', [TestSeriesController::class, 'update'])->name('update');
    
    // View enrolled students (Image 5)
    Route::get('/{id}/students', [TestSeriesController::class, 'viewStudents'])->name('view_students');
    
    // Delete test series
    Route::delete('/{id}', [TestSeriesController::class, 'destroy'])->name('destroy');
    
    // For viewing test details
    Route::get('/view/{id}', [TestSeriesController::class, 'viewTest'])->name('viewTest');
    // AJAX endpoint - Get course subjects
    Route::get('/course/{courseId}/subjects', [TestSeriesController::class, 'getCourseSubjects'])->name('course_subjects');
    
    // Syllabus Management Routes
    Route::post('/{id}/upload-syllabus', [TestSeriesController::class, 'uploadSyllabus'])->name('upload_syllabus');
    Route::get('/{id}/download-syllabus', [TestSeriesController::class, 'downloadSyllabus'])->name('download_syllabus');
    Route::delete('/{id}/delete-syllabus', [TestSeriesController::class, 'deleteSyllabus'])->name('delete_syllabus');
    
    // Result Management Routes
    Route::post('/upload-result/{id}', [TestSeriesController::class, 'uploadResult'])->name('upload_result');
    Route::post('/lock-result/{id}', [TestSeriesController::class, 'lockResult'])->name('lock_result');
    Route::get('/generate-template/{id}', [TestSeriesController::class, 'generateResultTemplate'])->name('generate_template');

    Route::post('/{testSeriesId}/tests', [TestSeriesController::class, 'storeTest'])->name('store_test');
    Route::put('/tests/{testId}', [TestSeriesController::class, 'updateTest'])->name('update_test');
    Route::delete('/tests/{testId}', [TestSeriesController::class, 'destroy_test'])->name('destroy_test');
    
    Route::get('/course/{courseId}/batches', [TestSeriesController::class, 'getCourseBatches'])
        ->name('course_batches');

     Route::post('/{testSeriesId}/tests/multiple', [TestSeriesController::class, 'storeMultipleTests'])
        ->name('store_multiple_tests');
        // In routes/web.php or routes/api.php
Route::get('/courses/{courseId}/batches', [TestSeriesController::class, 'getCourseBatches'])
    ->name('courses.batches');
    });
    

/*
|--------------------------------------------------------------------------
| Units Routes
|--------------------------------------------------------------------------
*/

Route::prefix('study_material')->group(function () {
    Route::get('/units/get-data', [Unitscontroller::class, 'getData'])->name('units.getData');
    Route::get('/units', [Unitscontroller::class, 'index'])->name('units.index');
    Route::post('/units/store', [Unitscontroller::class, 'store'])->name('units.store');
    Route::get('/units/{id}', [Unitscontroller::class, 'show'])->name('units.show');
    Route::post('/units/edit/{id}', [Unitscontroller::class, 'update'])->name('units.update');
    Route::delete('/units/{id}', [Unitscontroller::class, 'destroy'])->name('units.destroy');
});


/*
|--------------------------------------------------------------------------
| Study Material - Dispatch Routes
|--------------------------------------------------------------------------
*/
// Add this route group
Route::prefix('study_material/dispatch')->name('dispatch.')->group(function () {
    Route::get('/', [DispatchController::class, 'index'])->name('index');
    Route::get('/get-batches', [DispatchController::class, 'getBatches'])->name('getBatches');
    Route::get('/get-students', [DispatchController::class, 'getStudents'])->name('getStudents');
    Route::post('/dispatch-material', [DispatchController::class, 'dispatchMaterial'])->name('dispatchMaterial');
    Route::get('/dispatch-history', [DispatchController::class, 'getDispatchHistory'])->name('getDispatchHistory');
    Route::post('/bulk-delete', [DispatchController::class, 'bulkDelete'])->name('bulkDelete');
    Route::delete('/{id}', [DispatchController::class, 'destroy'])->name('destroy');

});

Route::prefix('study_material')->name('study_material.')->group(function () {
    Route::resource('Dispatch', DispatchController::class);
});

/*
|--------------------------------------------------------------------------
| Fees Management Routes - COMPLETE VERSION
|--------------------------------------------------------------------------
*/
Route::prefix('fees-management')->group(function () {
    Route::get('/', [FeesManagementController::class, 'index'])->name('fees.management.index');
    Route::post('collect-fees/search', [FeesManagementController::class, 'searchStudent'])->name('fees.collect.search');
    Route::post('status/search', [FeesManagementController::class, 'searchByStatus'])->name('fees.status.search');
    Route::post('transactions/filter', [FeesManagementController::class, 'filterTransactions'])->name('fees.transaction.filter');
    Route::get('student-details/{id}', [FeesManagementController::class, 'getStudentDetails']);
    Route::get('installment-history/{id}', [FeesManagementController::class, 'getInstallmentHistory']);
    Route::get('other-charges/{id}', [FeesManagementController::class, 'getOtherCharges']);
    Route::get('transaction-history/{id}', [FeesManagementController::class, 'getTransactionHistory']);
    Route::post('add-other-charges', [FeesManagementController::class, 'addOtherCharges']);
    Route::post('process-refund', [FeesManagementController::class, 'processRefund']);
    Route::post('apply-scholarship', [FeesManagementController::class, 'applyScholarship']);
    Route::get('export-pending-fees', [FeesManagementController::class, 'exportPendingFees'])->name('fees.export');
});

Route::prefix('reports')->name('reports.')->group(function () {
    // Walk-in Reports
    Route::get('/walkin', [App\Http\Controllers\Reports\WalkinController::class, 'index'])
        ->name('walkin.index');
    
    Route::get('/walkin/export', [App\Http\Controllers\Reports\WalkinController::class, 'export'])
        ->name('walkin.export');

    // Attendance Reports - Student
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/student', [App\Http\Controllers\Reports\AttendanceReportController::class, 'studentIndex'])
            ->name('student.index');
        
        Route::get('/student/data', [App\Http\Controllers\Reports\AttendanceReportController::class, 'getStudentData'])
            ->name('student.data');
        
        Route::get('/student/batches', [App\Http\Controllers\Reports\AttendanceReportController::class, 'getBatchesByCourse'])
            ->name('student.batches');
        
        Route::get('/student/rolls', [App\Http\Controllers\Reports\AttendanceReportController::class, 'getRollsByBatch'])
            ->name('student.rolls');
    });
});