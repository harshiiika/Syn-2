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

// -------------------------
// Authentication Routes+++++
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
// Download sample Excel file
Route::get('/users/sample-download', [UserController::class, 'downloadSample'])->name('users.downloadSample');
// Import employees from Excel/CSV
Route::post('/users/import', [UserController::class, 'import'])->name('users.import');

/*
|--------------------------------------------------------------------------
| Batches (In User Management) Routes
|--------------------------------------------------------------------------
*/
Route::get('/batches', [BatchesController::class, 'showBatches'])->name('user.batches.batches');
Route::post('/batches/add', [BatchesController::class, 'addBatch'])->name('batches.assign');
Route::post('/batches/{id}/toggle-status', [BatchesController::class, 'toggleStatus'])->name('batches.toggleStatus');
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
    Route::post('/add', [BatchController::class, 'store'])->name('add');
    Route::put('/{id}/update', [BatchController::class, 'update'])->name('update');
    Route::post('/{id}/toggle-status', [BatchController::class, 'toggleStatus'])->name('toggleStatus');
    
    //Export/Import Routes
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
    
    // Upload/Import routes
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
| STUDENT MANAGEMENT ROUTES
|--------------------------------------------------------------------------
*/

// ========================================
// 1. PENDING STUDENTS (Incomplete Profiles) 
// Changed to /student/pending to match route list
// ========================================
Route::prefix('student')->name('student.')->group(function () {

    /**   PENDING INQUIRY STUDENTS (Incomplete forms) */
    Route::get('/pending', [StudentController::class, 'index'])
        ->name('student.pending');  // student.student.pending
     
    /**   VIEW / EDIT A PENDING STUDENT */
    Route::get('/{id}/edit', [StudentController::class, 'edit'])
        ->name('student.edit');
    Route::put('/{id}', [StudentController::class, 'update'])
        ->name('student.update');

    /**   ONBOARDED STUDENTS (Complete form, not active yet) */
    Route::get('/onboarded', [StudentController::class, 'onboardedStudents'])
        ->name('onboard.onboard');  // student.onboard.onboard

    /**   VIEW SINGLE STUDENT DETAILS */
    Route::get('/view/{id}', [StudentController::class, 'show'])
        ->name('student.view');  // student.student.view

    /**   PENDING FEES STUDENTS */
    Route::get('/fees/pending', [StudentController::class, 'pendingFees'])
        ->name('fees.pending'); // student.fees.pending

    /**   STUDENTS */
    Route::get('/active', [StudentController::class, 'activeStudents'])
        ->name('active'); // student.active

    /**   CONVERT INQUIRY → STUDENT */
    Route::post('/convert/{id}', [StudentController::class, 'convertFromInquiry'])
        ->name('convert');

    /**   UPDATE FEES */
    Route::post('/fees/update/{id}', [StudentController::class, 'updateFees'])
        ->name('fees.update');
});

// ========================================
// 2. ONBOARDED STUDENTS (Complete Profiles)
// ========================================
Route::prefix('student/onboard')->name('student.onboard.')->group(function () {
    Route::get('/', [OnboardController::class, 'index'])->name('onboard');
    Route::get('/{id}', [OnboardController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [OnboardController::class, 'edit'])->name('edit');
    Route::put('/{id}', [OnboardController::class, 'update'])->name('update');
    
    // Transfer to Pending Fees
    Route::post('/{id}/transfer', [OnboardController::class, 'transfer'])->name('transfer');
});

// ========================================
// 3. PENDING FEES STUDENTS
// ========================================
Route::prefix('student/pendingfees')->name('student.pendingfees.')->group(function () {
    Route::get('/', [PendingFeesController::class, 'index'])->name('pending'); 
    Route::get('/{id}/view', [PendingFeesController::class, 'view'])->name('view');
    Route::get('/{id}/edit', [PendingFeesController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PendingFeesController::class, 'update'])->name('update');
    Route::get('/{id}/history', [PendingFeesController::class, 'getHistory'])->name('history'); // ⭐ ADDED - History route

    // Payment routes
    Route::get('/{id}/pay', [PendingFeesController::class, 'pay'])->name('pay');
    Route::post('/{id}/pay', [PendingFeesController::class, 'processPayment'])->name('processPayment');
});

// ========================================
// 4. ACTIVE STUDENTS (SM Students)
// ========================================
// Route::prefix('smstudents')
//     ->name('smstudents.')
//     ->group(function () {
//         //   List and export FIRST (before ID routes)
//         Route::get('/', [SmStudentsController::class, 'index'])->name('index');
//         Route::get('/export', [SmStudentsController::class, 'export'])->name('export');
        
//         //   Specific action routes BEFORE generic {id} routes
//         Route::get('/{id}/edit', [SmStudentsController::class, 'edit'])->name('edit');
//         Route::get('/{id}/history', [SmStudentsController::class, 'history'])->name('history');
        
//         //   POST/PUT routes
//         Route::put('/{id}', [SmStudentsController::class, 'update'])->name('update');
// Route::post('/{id}/update-batch', [SmStudentsController::class, 'updateBatch'])->name('updateBatch');
// Route::post('/{id}/update-shift', [SmStudentsController::class, 'updateShift'])->name('updateShift');        Route::post('/{id}/update-password', [SmStudentsController::class, 'updatePassword'])->name('updatePassword');
//         Route::post('/{id}/deactivate', [SmStudentsController::class, 'deactivate'])->name('deactivate');
        
//         //   Generic show route LAST
//         Route::get('/{id}', [SmStudentsController::class, 'show'])->name('show');
//         Route::get('/{id}/history', [SmStudentsController::class, 'history'])->name('history');
//         Route::get('/{id}/debug', [SmStudentsController::class, 'debug'])->name('debug');
//     });

Route::prefix('smstudents')
    ->name('smstudents.')
    ->group(function () {
        // List and export routes
        Route::get('/', [SmStudentsController::class, 'index'])->name('index');
        Route::get('/export', [SmStudentsController::class, 'export'])->name('export');
        
        // Specific actions
        Route::get('/{id}/edit', [SmStudentsController::class, 'edit'])->name('edit');
        Route::get('/{id}/history', [SmStudentsController::class, 'history'])->name('history');
        
        // Update & actions
        Route::put('/{id}', [SmStudentsController::class, 'update'])->name('update');
        Route::post('/{id}/update-batch', [SmStudentsController::class, 'updateBatch'])->name('updateBatch');
        Route::post('/{id}/update-shift', [SmStudentsController::class, 'updateShift'])->name('updateShift');
        Route::post('/{id}/update-password', [SmStudentsController::class, 'updatePassword'])->name('updatePassword');
        Route::post('/{id}/deactivate', [SmStudentsController::class, 'deactivate'])->name('deactivate');
        
        // View routes
        Route::get('/{id}', [SmStudentsController::class, 'show'])->name('show');
        Route::get('/{id}/debug', [SmStudentsController::class, 'debug'])->name('debug');
    });

//  Onboard transfer route OUTSIDE smstudents group
Route::get('/onboard/transfer/{id}', [OnboardController::class, 'transferToStudents'])
    ->name('onboard.transfer');
    
// ========================================
// 5. INQUIRY MANAGEMENT
// ========================================
Route::prefix('inquiries')->name('inquiries.')->group(function () {
    Route::get('/', [InquiryController::class, 'index'])->name('index');
    Route::get('/data', [InquiryController::class, 'data'])->name('data');
        Route::get('/{id}/history', [InquiryController::class, 'getHistory'])->name('history');
    Route::post('/bulk-onboard', [InquiryController::class, 'bulkOnboard'])->name('bulkOnboard');
    Route::post('/upload', [InquiryController::class, 'upload'])->name('upload');
    Route::post('/', [InquiryController::class, 'store'])->name('store');
    
    // ⭐ SPECIFIC ROUTES MUST COME BEFORE GENERIC {id} ROUTES
    Route::get('/{id}/scholarship', [InquiryController::class, 'showScholarshipDetails'])->name('scholarship.show');
    Route::put('/{id}/scholarship', [InquiryController::class, 'updateScholarshipDetails'])->name('scholarship.update');
    Route::get('/{id}/fees-batches', [InquiryController::class, 'showFeesBatchesDetails'])->name('fees-batches.show');
    Route::put('/{id}/fees-batches', [InquiryController::class, 'updateFeesBatches'])->name('fees-batches.update');
    Route::get('/{id}/edit', [InquiryController::class, 'edit'])->name('edit');
    
    // ⭐ ADD THIS: View route for displaying inquiry details
    Route::get('/{id}', [InquiryController::class, 'view'])->name('view');
    
    // Generic routes for update and delete
    Route::put('/{id}', [InquiryController::class, 'update'])->name('update');
    Route::delete('/{id}', [InquiryController::class, 'destroy'])->name('destroy');
});