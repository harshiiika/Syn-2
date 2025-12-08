<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\Student;
use Illuminate\Http\Request;
use App\Models\Student\Onboard;
use App\Models\Student\SMstudents;
use App\Models\Student\PendingFee; 
use App\Models\Master\Batch;
use App\Models\Master\Courses;
use App\Models\Master\Scholarship;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class OnboardController extends Controller
{
    /**
     * Display listing of onboarded students
     */
    public function index()
    {
        try {
            $students = Onboard::orderBy('created_at', 'desc')->get();
            
            return view('student.onboard.onboard', compact('students'));
        } catch (\Exception $e) {
            Log::error('Error loading onboarded students: ' . $e->getMessage());
            return back()->with('error', 'Failed to load students');
        }
    }

    /**
     * Display the specified onboarded student with COMPLETE details
     */
    public function show($id)
    {
        try {
            Log::info('=== SHOW METHOD CALLED ===', ['id' => $id]);
            
            $student = Onboard::find($id);
            
            if (!$student) {
                return redirect()->route('student.onboard.onboard')
                    ->with('error', 'Student not found');
            }
            
            Log::info('Student found with history', [
                'name' => $student->name,
                'history_count' => count($student->history ?? [])
            ]);
            
            // Don't load Batch or Courses - just pass empty arrays
            $batches = collect([]);
            $courses = collect([]);
            
            // Simple scholarship check without complex queries
            $scholarshipEligible = [
                'eligible' => ($student->eligible_for_scholarship ?? 'No') === 'Yes',
                'reason' => $student->scholarship_name ?? 'Not Eligible',
                'discountPercent' => floatval($student->discount_percentage ?? 0)
            ];
            
            Log::info('About to render view');
            
            return view('student.onboard.view', compact('student', 'batches', 'courses', 'scholarshipEligible'));
            
        } catch (\Exception $e) {
            Log::error('SHOW ERROR', [
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->route('student.onboard.onboard')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing onboarded student
     */
   public function edit($id)
{
    try {
        $student = Onboard::findOrFail($id);
        
        //  DEBUG: Log ALL student course-related fields
        Log::info('=== STUDENT COURSE DEBUG ===', [
            'student_id' => $id,
            'courseName' => $student->courseName ?? 'NOT SET',
            'course' => $student->course ?? 'NOT SET',
            'course_name' => $student->course_name ?? 'NOT SET',
            'course_id' => $student->course_id ?? 'NOT SET',
        ]);
        
        // Try multiple field names to get the course
        $studentCourseName = $student->courseName 
                          ?? $student->course_name 
                          ?? $student->course 
                          ?? null;
        
        Log::info('Selected course name for filtering', [
            'course_name' => $studentCourseName
        ]);
        
        // Get ALL active batches first for debugging
        $allBatches = Batch::where('status', 'Active')
            ->orderBy('batch_id', 'asc')
            ->get(['batch_id', 'course', 'name']);
        
        Log::info('=== ALL ACTIVE BATCHES ===', [
            'total_count' => $allBatches->count(),
            'sample_batches' => $allBatches->take(3)->map(function($b) {
                return [
                    'batch_id' => $b->batch_id,
                    'course' => $b->course ?? 'NO COURSE SET'
                ];
            })->toArray()
        ]);
        
        // Filter batches by student's course
        if ($studentCourseName) {
            $batches = Batch::where('status', 'Active')
                ->where('course', $studentCourseName)
                ->orderBy('batch_id', 'asc')
                ->get();
            
            Log::info('=== FILTERED BATCHES ===', [
                'filter_course' => $studentCourseName,
                'filtered_count' => $batches->count(),
                'filtered_batch_codes' => $batches->pluck('batch_id')->toArray()
            ]);
        } else {
            $batches = $allBatches;
            Log::warning('  Student course not found, showing all active batches');
        }
        
        $courses = Courses::all();
        
        return view('student.onboard.edit', compact('student', 'batches', 'courses'));
        
    } catch (\Exception $e) {
        Log::error('Error loading onboard edit form: ' . $e->getMessage());
        return back()->with('error', 'Failed to load student data');
    }
}

    /**
     * AJAX endpoint to get batches filtered by course
     * Call this when course is changed in the frontend
     */
    public function getBatchesByCourse(Request $request)
    {
        try {
            $courseName = $request->input('course');
            
            if (empty($courseName)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course name is required'
                ], 400);
            }
            
            $batches = Batch::where('status', 'Active')
                ->where('course', $courseName)
                ->orderBy('batch_id', 'asc')
                ->get(['_id', 'batch_id', 'name', 'shift', 'mode', 'start_date']);
            
            Log::info('Batches fetched by course', [
                'course' => $courseName,
                'batch_count' => $batches->count()
            ]);
            
            return response()->json([
                'success' => true,
                'batches' => $batches
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching batches by course', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch batches: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Track MEANINGFUL changes + Handle file uploads + Preserve existing history
     */
    public function update(Request $request, $id)
    {
        try {
            $student = Onboard::findOrFail($id);
            
            // Get original data for comparison
            $originalData = $student->getOriginal();
            
            // Update fields from request
            $updateData = $request->except(['_token', '_method']);
            
            //   CRITICAL  : Ensure batch_id is properly set
            if (!empty($updateData['batchName'])) {
                $batch = Batch::where('name', $updateData['batchName'])
                    ->orWhere('batch_id', $updateData['batchName'])
                    ->first();
                
                if ($batch) {
                    $updateData['batch_id'] = (string)$batch->_id;
                    $updateData['batch'] = $batch->batch_id ?? $batch->name;
                    
                    Log::info('  Batch data set during update', [
                        'batch_id' => $updateData['batch_id'],
                        'batch_name' => $updateData['batch']
                    ]);
                }
            }
            
            //   CRITICAL  : Ensure course_id is properly set
            if (!empty($updateData['courseName'])) {
                $course = Courses::where('name', $updateData['courseName'])
                    ->orWhere('course_name', $updateData['courseName'])
                    ->first();
                
                if ($course) {
                    $updateData['course_id'] = (string)$course->_id;
                    $updateData['course'] = $course->name ?? $course->course_name;
                    
                    Log::info('  Course data set during update', [
                        'course_id' => $updateData['course_id'],
                        'course_name' => $updateData['course']
                    ]);
                }
            }
            
            // Track meaningful changes
            $meaningfulChanges = [];
            
            $importantFields = [
                'name' => 'Student Name',
                'father' => 'Father Name',
                'mother' => 'Mother Name',
                'mobileNumber' => 'Mobile Number',
                'alternateMobileNumber' => 'Alternate Mobile',
                'email' => 'Email',
                'courseName' => 'Course Name',
                'deliveryMode' => 'Delivery Mode',
                'courseContent' => 'Course Content',
                'batchName' => 'Batch Name',
                'category' => 'Category',
                'address' => 'Address',
                'percentage' => 'Percentage',
                'eligible_for_scholarship' => 'Scholarship Eligibility',
                'scholarship_name' => 'Scholarship Name',
                'discount_percentage' => 'Discount Percentage'
            ];
            
            foreach ($importantFields as $field => $label) {
                $oldValue = $originalData[$field] ?? null;
                $newValue = $updateData[$field] ?? null;
                
                if ($oldValue != $newValue && !is_null($newValue) && $newValue !== '') {
                    $meaningfulChanges[$label] = [
                        'from' => $oldValue ?? 'Not Set',
                        'to' => $newValue
                    ];
                }
            }
            
            // Handle file uploads
            $fileFields = [
                'passport_photo' => 'documents/passport',
                'marksheet' => 'documents/marksheet',
                'caste_certificate' => 'documents/caste',
                'scholarship_proof' => 'documents/scholarship',
                'secondary_marksheet' => 'documents/secondary',
                'senior_secondary_marksheet' => 'documents/senior_secondary'
            ];
            
            foreach ($fileFields as $field => $path) {
                if ($request->hasFile($field)) {
                    $updateData[$field] = $request->file($field)->store($path, 'public');
                    $meaningfulChanges[ucwords(str_replace('_', ' ', $field))] = [
                        'from' => 'Previous File',
                        'to' => 'New File Uploaded'
                    ];
                }
            }
            
            // Preserve existing history
            $existingHistory = $student->history ?? [];
            
            if (!empty($meaningfulChanges)) {
                $historyEntry = [
                    'action' => 'Student Details Updated',
                    'description' => 'Important student information was modified',
                    'changes' => $meaningfulChanges,
                    'changed_by' => auth()->user()->name ?? auth()->user()->email ?? 'Admin',
                    'timestamp' => now()->toIso8601String(),
                    'date' => now()->format('d M Y, h:i A')
                ];
                
                array_unshift($existingHistory, $historyEntry);
                
                if (count($existingHistory) > 50) {
                    $existingHistory = array_slice($existingHistory, 0, 50);
                }
                
                $updateData['history'] = $existingHistory;
            }
            
            // Update the student
            $student->update($updateData);
            
            return redirect()->route('student.onboard.onboard')
                ->with('success', 'Student details updated successfully');
                
        } catch (\Exception $e) {
            Log::error('Error updating onboarded student: ' . $e->getMessage());
            return back()->with('error', 'Failed to update student: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Transfer student from Onboard to Pending Fees
     * Preserves existing history and adds transfer entry
     */
    public function transfer(Request $request, $id)
    {
        try {
            $student = Onboard::findOrFail($id);
            
            Log::info('Starting transfer to pending fees', [
                'student_id' => $id,
                'student_name' => $student->name
            ]);
            
            // Prepare data
            $pendingFeesData = $student->toArray();
            unset($pendingFeesData['_id']);
            
            // Set transfer metadata
            $pendingFeesData['status'] = 'pending_fees';
            $pendingFeesData['transferred_from'] = 'onboard';
            $pendingFeesData['transferred_at'] = now();
            $pendingFeesData['transferred_by'] = auth()->user()->email ?? 'Admin';
            
            // Initialize payment fields
            $totalFeesInclusive = $pendingFeesData['total_fees_inclusive_tax'] ?? 
                                 ($pendingFeesData['total_fees'] ?? 0);
            
            $pendingFeesData['paid_fees'] = 0;
            $pendingFeesData['remaining_fees'] = $totalFeesInclusive;
            $pendingFeesData['fee_status'] = 'pending';
            $pendingFeesData['paymentHistory'] = [];
            
            // Preserve existing history and add transfer entry
            $existingHistory = $pendingFeesData['history'] ?? [];
            
            $transferEntry = [
                'action' => 'Student Transferred to Pay Fees',
                'description' => 'Admin transferred student ' . $student->name . ' to accounts section.',
                'user' => auth()->user()->name ?? 'Admin',
                'timestamp' => now()->toIso8601String(),
                'created_at' => now()->toDateTimeString()
            ];
            
            array_unshift($existingHistory, $transferEntry);
            $pendingFeesData['history'] = $existingHistory;
            
            // Create in pending_fees
            $pendingFeeStudent = PendingFee::create($pendingFeesData);
            
            // Delete from onboard
            $student->delete();
            
            Log::info('  Transfer to pending fees successful', [
                'new_id' => $pendingFeeStudent->_id,
                'student_name' => $pendingFeeStudent->name
            ]);
            
            return redirect()->route('student.onboard.onboard')
                ->with('success', "Student '{$pendingFeeStudent->name}' transferred to Pending Fees successfully");
                
        } catch (\Exception $e) {
            Log::error('Transfer to pending fees failed', [
                'student_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to transfer student: ' . $e->getMessage());
        }
    }

    /**
     * Transfer student from Pending to Onboard
     */
    public function transferToOnboard(Request $request, $id)
    {
        try {
            Log::info('=== TRANSFER TO ONBOARD START ===', ['pending_id' => $id]);
            
            $pendingStudent = Student::findOrFail($id);
            
            // Prepare onboard data
            $onboardData = $pendingStudent->toArray();
            unset($onboardData['_id']);
            
            $now = Carbon::now('Asia/Kolkata');
            
            // Fix missing batch_id
            if (empty($onboardData['batch_id'])) {
                if (!empty($onboardData['batchName']) || !empty($onboardData['batch'])) {
                    $batchName = $onboardData['batchName'] ?? $onboardData['batch'];
                    $batch = Batch::where('name', $batchName)
                        ->orWhere('batch_id', $batchName)
                        ->first();
                    
                    if ($batch) {
                        $onboardData['batch_id'] = (string)$batch->_id;
                        $onboardData['batch'] = $batch->batch_id ?? $batch->name;
                    }
                }
            }
            
            // Fix missing course_id
            if (empty($onboardData['course_id'])) {
                if (!empty($onboardData['courseName']) || !empty($onboardData['course'])) {
                    $courseName = $onboardData['courseName'] ?? $onboardData['course'];
                    $course = Courses::where('name', $courseName)
                        ->orWhere('course_name', $courseName)
                        ->first();
                    
                    if ($course) {
                        $onboardData['course_id'] = (string)$course->_id;
                        $onboardData['course'] = $course->name ?? $course->course_name;
                    }
                }
            }
            
            $onboardData['status'] = 'onboarded';
            $onboardData['transferred_from'] = 'pending';
            $onboardData['onboardedAt'] = $now;
            $onboardData['transferred_at'] = $now;
            $onboardData['transferred_by'] = auth()->user()->email ?? 'Admin';
            
            // Build history
            $completeHistory = $pendingStudent->history ?? [];
            
            $onboardHistoryEntry = [
                'action' => 'Student Onboarded',
                'description' => 'Student successfully onboarded and transferred to onboarding collection',
                'changed_by' => auth()->user()->name ?? auth()->user()->email ?? 'Admin',
                'timestamp' => $now->toIso8601String(),
                'date' => $now->format('d M Y, h:i A')
            ];
            
            array_unshift($completeHistory, $onboardHistoryEntry);
            $onboardData['history'] = $completeHistory;
            
            $onboardStudent = Onboard::create($onboardData);
            $pendingStudent->delete();
            
            return redirect()->route('student.onboard.onboard')
                ->with('success', "Student '{$onboardStudent->name}' successfully onboarded!");
                
        } catch (\Exception $e) {
            Log::error(' Transfer to onboard failed', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to onboard student: ' . $e->getMessage());
        }
    }

    /**
     * Initialize history for existing students (run once)
     */
    public function initializeHistory()
    {
        try {
            $students = Onboard::all();
            $count = 0;
            
            foreach ($students as $student) {
                if (!isset($student->history) || empty($student->history)) {
                    $initialHistory = [
                        [
                            'action' => 'Student Onboarded',
                            'description' => 'Initial student onboarding record',
                            'changed_by' => 'System',
                            'timestamp' => $student->created_at ? $student->created_at->toIso8601String() : now()->toIso8601String(),
                            'date' => $student->created_at ? $student->created_at->format('d M Y, h:i A') : now()->format('d M Y, h:i A')
                        ]
                    ];
                    
                    $student->update(['history' => $initialHistory]);
                    $count++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "Successfully initialized history for {$count} students"
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error initializing history: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check scholarship eligibility
     */
    private function checkScholarshipEligibility($student)
    {
        $result = [
            'eligible' => false,
            'reason' => 'Not Eligible',
            'discountPercent' => 0
        ];

        if (in_array(strtolower($student->eligible_for_scholarship ?? ''), ['yes', 'true', '1'])) {
            $result['eligible'] = true;
            $result['reason'] = $student->scholarship_name ?? 'Scholarship Applied';
            $result['discountPercent'] = floatval($student->discount_percentage ?? 0);
            return $result;
        }

        $courseName = $student->course_name ?? $student->courseName ?? $student->course->name ?? null;
        $category = $student->category ?? 'General';

        if (in_array(strtolower($student->scholarshipTest ?? $student->scholarship_test ?? ''), ['yes'])) {
            $testPercentage = floatval($student->scholarship_percentage ?? 0);
            
            $scholarship = Scholarship::getByTestScore($testPercentage, $courseName, $category);
            
            if ($scholarship) {
                return [
                    'eligible' => true,
                    'reason' => $scholarship->scholarship_name ?? 'Scholarship Test',
                    'discountPercent' => floatval($scholarship->discount_percentage)
                ];
            }
        }

        if (!empty($student->percentage) || !empty($student->board_percentage)) {
            $boardPercent = floatval($student->percentage ?? $student->board_percentage ?? 0);
            
            $scholarship = Scholarship::getByPercentage($boardPercent, $courseName, $category);
            
            if ($scholarship) {
                return [
                    'eligible' => true,
                    'reason' => $scholarship->scholarship_name ?? 'Board Exam Merit',
                    'discountPercent' => floatval($scholarship->discount_percentage)
                ];
            }
        }

        return $result;
    }

    /**
     * Get history for a student (API endpoint)
     */
    public function getHistory($id)
    {
        try {
            $student = Onboard::find($id);
            
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found'
                ], 404);
            }
            
            $history = $student->history ?? [];
            
            if (empty($history)) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }
            
            usort($history, function($a, $b) {
                $timeA = strtotime($a['timestamp'] ?? '');
                $timeB = strtotime($b['timestamp'] ?? '');
                return $timeB - $timeA;
            });
            
            return response()->json([
                'success' => true,
                'data' => $history
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get history error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch history: ' . $e->getMessage()
            ], 500);
        }
    }
}