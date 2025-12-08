<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student\SMstudents;
use App\Models\Master\Batch;
use App\Models\Master\Courses;
use App\Models\Student\Shift; 
use App\Models\TestSeries\TestSeries; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SmStudentsController extends Controller
{


private function getStudentActivities($student)
{
    $rawData = $student->getAttributes();
    $activities = [];
    
    // Get stored activities from database
    $storedActivities = $rawData['activities'] ?? [];
    if (is_string($storedActivities)) {
        $storedActivities = json_decode($storedActivities, true) ?? [];
    }
    
    if (is_array($storedActivities) && !empty($storedActivities)) {
        foreach ($storedActivities as $activity) {
            $activities[] = [
                'title' => $activity['title'] ?? 'Activity',
                'description' => $activity['description'] ?? 'performed an action',
                'performed_by' => $activity['performed_by'] ?? 'Admin',
                'created_at' => isset($activity['created_at']) ? 
                    Carbon::parse($activity['created_at']) : 
                    Carbon::now()
            ];
        }
    }
    
    // Sort by date (newest first)
    if (!empty($activities)) {
        usort($activities, function($a, $b) {
            $timeA = strtotime($a['created_at']->toDateTimeString() ?? '1970-01-01');
            $timeB = strtotime($b['created_at']->toDateTimeString() ?? '1970-01-01');
            return $timeB - $timeA; // DESCENDING order (newest to oldest)
        });
    }
    
    return $activities;
}
    /**
     * Display a listing of students
     */
public function index(Request $request)
    {
        try {
            $courseFilter = $request->get('course_filter');
            $collectionFilter = $request->get('collection', 'main'); // 'main', 'course', or 'all'
            
            $query = null;
            
            // Option 1: Show only main collection
            if ($collectionFilter === 'main') {
                $query = SMstudents::with(['batch', 'course', 'shift']);
                Log::info('  Querying main collection (s_mstudents)');
            }
            
            // Option 2: Show specific course collection
            elseif ($collectionFilter === 'course' && $courseFilter) {
                $query = SMstudents::byCourse($courseFilter)->with(['batch', 'course', 'shift']);
                Log::info('  Querying course-specific collection', ['course' => $courseFilter]);
            }
            
            // Option 3: Show ALL students from all collections (merged)
            elseif ($collectionFilter === 'all') {
                $students = SMstudents::getAllFromAllCollections();
                $batches = Batch::where('status', 'Active')->orderBy('name')->get();
                $courses = Courses::all();
                $shifts = Shift::where('is_active', true)->get();
                
                Log::info('  Querying ALL collections', ['total_students' => $students->count()]);
                
                return view('student.smstudents.smstudents', compact('students', 'batches', 'courses', 'shifts'));
            }
            
            // Default: main collection
            else {
                $query = SMstudents::with(['batch', 'course', 'shift']);
            }
            
            $students = $query->orderBy('created_at', 'desc')->get();
            $batches = Batch::where('status', 'Active')->orderBy('name')->get();
            $courses = Courses::all();
            $shifts = Shift::where('is_active', true)->get();

            return view('student.smstudents.smstudents', compact('students', 'batches', 'courses', 'shifts'));
            
        } catch (\Exception $e) {
            Log::error('Error loading students: ' . $e->getMessage());
            return back()->with('error', 'Failed to load students');
        }
    }
/**
 *   Display the test series for a specific student
 */
public function testSeries($id)
{
    try {
        // Find the student by ID with better error handling
        $student = SMstudents::with(['batch', 'course', 'shift'])->find($id);
        
        if (!$student) {
            Log::error('Test Series - Student not found:', ['id' => $id]);
            return redirect()->route('smstudents.index')
                ->with('error', 'Student not found with ID: ' . $id);
        }
        
        Log::info('Test Series - Loading for student:', [
            'student_id' => $id,
            'student_name' => $student->student_name ?? $student->name ?? 'N/A'
        ]);
        
        $rawData = $student->getAttributes();
        
        // Initialize test series data structure
        $testSeries = [
            'general' => [
                'board' => [],
                'neet' => [],
                'iit' => []
            ],
            'spr' => [
                'board' => [],
                'neet' => [],
                'iit' => []
            ]
        ];
        
        // Get test series data from student record if exists
        if (isset($rawData['test_series']) && !empty($rawData['test_series'])) {
            $storedTestSeries = is_string($rawData['test_series']) 
                ? json_decode($rawData['test_series'], true) 
                : $rawData['test_series'];
            
            if (is_array($storedTestSeries)) {
                $testSeries = array_merge($testSeries, $storedTestSeries);
            }
        }
        
        // Calculate attendance percentages
        $attendancePercentageGeneral = 0;
        $attendancePercentageSpr = 0;
        
        // Calculate for General tests
        $generalTests = array_merge(
            $testSeries['general']['board'] ?? [],
            $testSeries['general']['neet'] ?? [],
            $testSeries['general']['iit'] ?? []
        );
        
        if (count($generalTests) > 0) {
            $attendedGeneral = count(array_filter($generalTests, function($test) {
                return isset($test['obtained_marks']) && $test['obtained_marks'] > 0;
            }));
            $attendancePercentageGeneral = ($attendedGeneral / count($generalTests)) * 100;
        }
        
        // Calculate for SPR tests
        $sprTests = array_merge(
            $testSeries['spr']['board'] ?? [],
            $testSeries['spr']['neet'] ?? [],
            $testSeries['spr']['iit'] ?? []
        );
        
        if (count($sprTests) > 0) {
            $attendedSpr = count(array_filter($sprTests, function($test) {
                return isset($test['obtained_marks']) && $test['obtained_marks'] > 0;
            }));
            $attendancePercentageSpr = ($attendedSpr / count($sprTests)) * 100;
        }
        
        // Calculate overall statistics for General tests
        $overallRankGeneral = 0;
        $overallPercentageGeneral = 0;
        
        if (count($generalTests) > 0) {
            $totalRank = 0;
            $totalPercentage = 0;
            $validTests = 0;
            
            foreach ($generalTests as $test) {
                if (isset($test['overall_rank']) && $test['overall_rank'] > 0) {
                    $totalRank += $test['overall_rank'];
                    $validTests++;
                }
                if (isset($test['percentage'])) {
                    $totalPercentage += $test['percentage'];
                }
            }
            
            if ($validTests > 0) {
                $overallRankGeneral = $totalRank / $validTests;
            }
            if (count($generalTests) > 0) {
                $overallPercentageGeneral = $totalPercentage / count($generalTests);
            }
        }
        
        // Calculate overall statistics for SPR tests
        $overallRankSpr = 0;
        $overallPercentageSpr = 0;
        
        if (count($sprTests) > 0) {
            $totalRank = 0;
            $totalPercentage = 0;
            $validTests = 0;
            
            foreach ($sprTests as $test) {
                if (isset($test['overall_rank']) && $test['overall_rank'] > 0) {
                    $totalRank += $test['overall_rank'];
                    $validTests++;
                }
                if (isset($test['percentage'])) {
                    $totalPercentage += $test['percentage'];
                }
            }
            
            if ($validTests > 0) {
                $overallRankSpr = $totalRank / $validTests;
            }
            if (count($sprTests) > 0) {
                $overallPercentageSpr = $totalPercentage / count($sprTests);
            }
        }
        
        Log::info('Test Series - Data prepared successfully:', [
            'general_tests' => count($generalTests),
            'spr_tests' => count($sprTests),
            'attendance_general' => $attendancePercentageGeneral,
            'attendance_spr' => $attendancePercentageSpr
        ]);
        
        return view('student.smstudents.testseries', compact(
            'student',
            'testSeries',
            'attendancePercentageGeneral',
            'attendancePercentageSpr',
            'overallRankGeneral',
            'overallPercentageGeneral',
            'overallRankSpr',
            'overallPercentageSpr'
        ));
        
    } catch (\Exception $e) {
        Log::error('Error loading test series:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'student_id' => $id
        ]);
        
        return redirect()->route('smstudents.index')
            ->with('error', 'Error loading test series: ' . $e->getMessage());
    }
}


public function showByCourse($courseName)
    {
        try {
            $course = Courses::where('course_name', $courseName)->first();
            
            if (!$course) {
                return redirect()->route('smstudents.index')
                    ->with('error', 'Course not found');
            }
            
            $students = SMstudents::byCourse($courseName)
                ->with(['batch', 'shift'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            $batches = Batch::where('status', 'Active')->orderBy('name')->get();
            $courses = Courses::all();
            $shifts = Shift::where('is_active', true)->get();
            
            Log::info('  Showing students for course', [
                'course' => $courseName,
                'collection' => $course->getStudentCollectionName(),
                'count' => $students->count()
            ]);
            
            return view('student.smstudents.smstudents', compact(
                'students', 
                'batches', 
                'courses', 
                'shifts', 
                'course'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error loading course students: ' . $e->getMessage());
            return back()->with('error', 'Failed to load students');
        }
    }

    /**
     *   ENHANCED: Create activity log entry
     */
    private function createActivityLog($student, $title, $description, $additionalData = [])
    {
        try {
            $rawData = $student->getAttributes();
            $activities = $rawData['activities'] ?? [];
            
            // Decode if string
            if (is_string($activities)) {
                $activities = json_decode($activities, true) ?? [];
            }
            
            // Ensure it's an array
            if (!is_array($activities)) {
                $activities = [];
            }
            
            // Create new activity
            $newActivity = array_merge([
                'title' => $title,
                'description' => $description,
                'performed_by' => auth()->user()->name ?? auth()->user()->email ?? 'Admin',
                'performed_by_email' => auth()->user()->email ?? 'admin@system.com',
                'created_at' => now()->toDateTimeString(),
                'timestamp' => now()->timestamp,
                'ip_address' => request()->ip(),
            ], $additionalData);
            
            // Add to beginning of array (newest first)
            array_unshift($activities, $newActivity);
            
            // Keep only last 100 activities
            $activities = array_slice($activities, 0, 100);
            
            // Update student record
            $student->update(['activities' => $activities]);
            
            Log::info('Activity log created:', [
                'student_id' => (string)$student->_id,
                'title' => $title,
                'performed_by' => $newActivity['performed_by']
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to create activity log:', [
                'error' => $e->getMessage(),
                'student_id' => $student->_id ?? 'unknown',
                'title' => $title
            ]);
        }
    }

 public function getCourseStatistics()
    {
        try {
            $courses = Courses::all();
            $statistics = [];
            
            foreach ($courses as $course) {
                $collectionName = $course->getStudentCollectionName();
                
                try {
                    $totalStudents = $course->getStudentsCount();
                    $paidStudents = $course->getFullyPaidStudentsCount();
                    
                    $statistics[] = [
                        'course_name' => $course->course_name,
                        'collection_name' => $collectionName,
                        'total_students' => $totalStudents,
                        'fully_paid' => $paidStudents,
                        'pending_payment' => $totalStudents - $paidStudents
                    ];
                } catch (\Exception $e) {
                    Log::warning("Could not get stats for {$collectionName}");
                    $statistics[] = [
                        'course_name' => $course->course_name,
                        'collection_name' => $collectionName,
                        'total_students' => 0,
                        'fully_paid' => 0,
                        'pending_payment' => 0,
                        'error' => $e->getMessage()
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'statistics' => $statistics
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified student
     */
    public function update(Request $request, $id)
    {
        $student = SMstudents::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'roll_no' => 'nullable|unique:smstudents,roll_no,' . $id . ',_id',
            'student_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:smstudents,email,' . $id . ',_id',
            'phone' => 'required|string|max:15',
            'shift_id' => 'nullable|exists:shifts,_id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Validation failed');
        }

        try {
            // Track changes
            $changes = [];
            $originalData = $student->getAttributes();
            
            $shiftId = null;
            $shiftName = null;
            if ($request->filled('shift_id')) {
                $shiftId = $request->shift_id;
                $shift = Shift::find($shiftId);
                $shiftName = $shift ? $shift->name : null;
            }

            $updateData = $request->all();
            $updateData['shift_id'] = $shiftId;
            $updateData['shift'] = $shiftName;

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
                $changes[] = 'password';
            }

            // Track what changed
            foreach ($updateData as $key => $value) {
                if (isset($originalData[$key]) && $originalData[$key] != $value && $key !== 'password') {
                    $changes[] = $key;
                }
            }

            $student->update($updateData);
            
            //   LOG ACTIVITY
            if (!empty($changes)) {
                $this->createActivityLog(
                    $student,
                    'Student Details Updated',
                    'Updated fields: ' . implode(', ', $changes),
                    ['changed_fields' => $changes]
                );
            }

            return redirect()->route('smstudents.index')->with('success', 'Student updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating student: ' . $e->getMessage());
            return back()->with('error', 'Failed to update student: ' . $e->getMessage())->withInput();
        }
    }

    /**
     *   ENHANCED: Update student password with activity logging
     */
    public function updatePassword(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Password validation failed');
        }

        try {
            $student = SMstudents::findOrFail($id);
            
            // Update password
            $student->update([
                'password' => Hash::make($request->password)
            ]);
            
            //   LOG ACTIVITY
            $this->createActivityLog(
                $student,
                'Password Updated',
                'Student password was changed by ' . (auth()->user()->name ?? auth()->user()->email ?? 'Admin')
            );

            return redirect()->route('smstudents.index')
                ->with('success', 'Password updated successfully for ' . ($student->student_name ?? 'student'));
                
        } catch (\Exception $e) {
            Log::error('Error updating password:', [
                'error' => $e->getMessage(),
                'student_id' => $id
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to update password.');
        }
    }

    /**
     *  Update student shift with activity logging
     */
    public function updateShift(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'shift_id' => 'required|exists:shifts,_id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Please select a valid shift');
        }

        try {
            $student = SMstudents::findOrFail($id);
            
            $currentShiftId = (string)($student->shift_id ?? '');
            $newShiftId = (string)($request->shift_id);
            
            if ($currentShiftId === $newShiftId) {
                return redirect()->back()
                    ->with('warning', 'Student is already in this shift.');
            }
            
            $oldShiftName = $student->shift->name ?? 'N/A';
            $newShift = Shift::findOrFail($request->shift_id);
            
            $student->update([
                'shift_id' => $request->shift_id,
            ]);
            
            //   LOG ACTIVITY
            $this->createActivityLog(
                $student,
                'Shift Updated',
                'Shift changed from "' . $oldShiftName . '" to "' . $newShift->name . '"',
                [
                    'old_shift' => $oldShiftName,
                    'new_shift' => $newShift->name,
                    'old_shift_id' => $currentShiftId,
                    'new_shift_id' => $newShiftId
                ]
            );
            
            $student = SMstudents::with(['shift', 'batch', 'course'])->findOrFail($id);
            
            return redirect()->route('smstudents.index')
                ->with('success', 'Shift updated from "' . $oldShiftName . '" to "' . $newShift->name . '"');
                
        } catch (\Exception $e) {
            Log::error('Error updating shift:', [
                'error' => $e->getMessage(),
                'student_id' => $id
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to update shift: ' . $e->getMessage());
        }
    }

    /**
     *   Update student batch with activity logging
     */
    public function updateBatch(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'batch_id' => 'required|exists:batches,_id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Please select a valid batch');
        }

        try {
            $student = SMstudents::findOrFail($id);
            
            $currentBatchId = (string)($student->batch_id);
            $newBatchIdInput = (string)($request->batch_id);
            
            if ($currentBatchId === $newBatchIdInput) {
                return redirect()->back()
                    ->with('warning', 'Student is already in this batch.');
            }
            
            $oldBatchName = $student->batch->batch_id ?? $student->batch_name ?? 'N/A';
            $newBatch = Batch::findOrFail($request->batch_id);
            
            $course = null;
            if ($newBatch->course) {
                $course = Courses::where('name', $newBatch->course)->first();
            }
            
            $updateData = [
                'batch_id' => $request->batch_id,
                'batch_name' => $newBatch->batch_id ?? $newBatch->name,
                'course_name' => $newBatch->course,
                'delivery_mode' => $newBatch->mode,
            ];
            
            if ($course) {
                $updateData['course_id'] = $course->_id ?? $course->id;
            }
            
            if (!empty($newBatch->shift)) {
                $updateData['shift'] = $newBatch->shift;
            }
            
            $student->update($updateData);
            
            //   LOG ACTIVITY
            $this->createActivityLog(
                $student,
                'Batch Updated',
                'Batch changed from "' . $oldBatchName . '" to "' . ($newBatch->batch_id ?? $newBatch->name) . '"',
                [
                    'old_batch' => $oldBatchName,
                    'new_batch' => $newBatch->batch_id ?? $newBatch->name,
                    'old_batch_id' => $currentBatchId,
                    'new_batch_id' => $newBatchIdInput,
                    'new_course' => $newBatch->course
                ]
            );
            
            $student->refresh();
            $student->unsetRelation('batch');
            $student->unsetRelation('course');
            $student->load(['batch', 'course']);
            
            return redirect()->route('smstudents.index')
                ->with('success', 'Batch updated from "' . $oldBatchName . '" to "' . ($newBatch->batch_id ?? $newBatch->name) . '"');
                
        } catch (\Exception $e) {
            Log::error('Error updating batch:', [
                'error' => $e->getMessage(),
                'student_id' => $id
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to update batch: ' . $e->getMessage());
        }
    }

   /**
 *   Display the specified student with COMPLETE data from all previous stages
 */
public function show($id)
{
    try {
        $student = SMstudents::with(['batch', 'course', 'shift'])->find($id);
        
        if (!$student) {
            return back()->with('error', 'Student not found with ID: ' . $id);
        }
        
        $rawData = $student->getAttributes();
        
        // Create safe student object
        $safeStudent = new \stdClass();
        
        // ==================== BASIC INFO ====================
        $safeStudent->_id = $student->_id ?? $id;
        $safeStudent->roll_no = $rawData['roll_no'] ?? $rawData['rollNo'] ?? 'Not Assigned';
        $safeStudent->student_name = $rawData['student_name'] ?? $rawData['name'] ?? $rawData['studentName'] ?? 'N/A';
        $safeStudent->email = $rawData['email'] ?? 'N/A';
        $safeStudent->phone = $rawData['phone'] ?? $rawData['mobileNumber'] ?? $rawData['studentContact'] ?? 'N/A';
        
        // ==================== PERSONAL DETAILS ====================
        // Father Name - check all possible field names
        $safeStudent->father_name = $rawData['father_name'] ?? 
                                    $rawData['father'] ?? 
                                    $rawData['fatherName'] ?? 
                                    $rawData['fathers_name'] ?? 'N/A';
        
        // Mother Name - check all possible field names
        $safeStudent->mother_name = $rawData['mother_name'] ?? 
                                    $rawData['mother'] ?? 
                                    $rawData['motherName'] ?? 
                                    $rawData['mothers_name'] ?? 'N/A';
        
        // Handle DOB - Multiple formats
        $safeStudent->dob = $this->parseDateSafely($rawData['dob'] ?? $rawData['dateOfBirth'] ?? null);
        
        // Contact Numbers
        $safeStudent->father_contact = $rawData['father_contact'] ?? 
                                       $rawData['fatherContact'] ?? 
                                       $rawData['mobileNumber'] ?? 'N/A';
        
        $safeStudent->father_whatsapp = $rawData['father_whatsapp'] ?? 
                                        $rawData['fatherWhatsapp'] ?? 
                                        $rawData['whatsappNumber'] ?? 'N/A';
        
        $safeStudent->mother_contact = $rawData['mother_contact'] ?? 
                                       $rawData['motherContact'] ?? 
                                       $rawData['mother_phone'] ?? 'N/A';
        
        // Category & Demographics
        $safeStudent->category = $rawData['category'] ?? $rawData['caste'] ?? 'N/A';
        $safeStudent->gender = $rawData['gender'] ?? 'N/A';
        
        // Occupations
        $safeStudent->father_occupation = $rawData['father_occupation'] ?? 
                                          $rawData['fatherOccupation'] ?? 'N/A';
        
        $safeStudent->mother_occupation = $rawData['mother_occupation'] ?? 
                                          $rawData['motherOccupation'] ?? 'N/A';
        
        // ==================== ADDRESS ====================
        $safeStudent->state = $rawData['state'] ?? 'N/A';
        $safeStudent->city = $rawData['city'] ?? 'N/A';
        $safeStudent->pincode = $rawData['pincode'] ?? $rawData['pinCode'] ?? 'N/A';
        $safeStudent->address = $rawData['address'] ?? $rawData['full_address'] ?? 'N/A';
        
        // ==================== ADDITIONAL INFO ====================
        $safeStudent->belongs_other_city = $rawData['belongs_other_city'] ?? 
                                           $rawData['belongToOtherCity'] ?? 
                                           $rawData['belongs_to_other_city'] ?? 'No';
        
        $safeStudent->economic_weaker_section = $rawData['economic_weaker_section'] ?? 
                                                $rawData['economicWeakerSection'] ?? 
                                                $rawData['ews'] ?? 'No';
        
        $safeStudent->army_police_background = $rawData['army_police_background'] ?? 
                                               $rawData['armyPoliceBackground'] ?? 
                                               $rawData['army_background'] ?? 'No';
        
        $safeStudent->specially_abled = $rawData['specially_abled'] ?? 
                                        $rawData['speciallyAbled'] ?? 
                                        $rawData['pwd'] ?? 'No';
        
        // ==================== COURSE DETAILS ====================
        $safeStudent->course_type = $rawData['course_type'] ?? 
                                    $rawData['courseType'] ?? 'N/A';
        
        $safeStudent->course_name = $rawData['course_name'] ?? 
                                    $rawData['courseName'] ?? 
                                    ($student->course->name ?? 'N/A');
        
        $safeStudent->delivery = $rawData['delivery'] ?? 
                                 $rawData['delivery_mode'] ?? 
                                 $rawData['deliveryMode'] ?? 
                                 $rawData['mode'] ?? 'N/A';
        
        $safeStudent->medium = $rawData['medium'] ?? 
                               $rawData['courseMedium'] ?? 
                               $rawData['course_medium'] ?? 'N/A';
        
        $safeStudent->board = $rawData['board'] ?? 
                              $rawData['courseBoard'] ?? 
                              $rawData['course_board'] ?? 'N/A';
        
        $safeStudent->course_content = $rawData['course_content'] ?? 
                                       $rawData['courseContent'] ?? 'N/A';
        
        // ==================== ACADEMIC DETAILS ====================
        $safeStudent->previous_class = $rawData['previous_class'] ?? 
                                       $rawData['previousClass'] ?? 
                                       $rawData['lastClass'] ?? 'N/A';
        
        $safeStudent->academic_medium = $rawData['academic_medium'] ?? 
                                        $rawData['previousMedium'] ?? 
                                        $rawData['previous_medium'] ?? 'N/A';
        
        $safeStudent->school_name = $rawData['school_name'] ?? 
                                    $rawData['schoolName'] ?? 
                                    $rawData['previous_school'] ?? 'N/A';
        
        $safeStudent->academic_board = $rawData['academic_board'] ?? 
                                       $rawData['previousBoard'] ?? 
                                       $rawData['previous_board'] ?? 'N/A';
        
        $safeStudent->passing_year = $rawData['passing_year'] ?? 
                                     $rawData['passingYear'] ?? 
                                     $rawData['year_of_passing'] ?? 'N/A';
        
        // Percentage - Convert safely
        $safeStudent->percentage = $this->parsePercentageSafely(
            $rawData['percentage'] ?? 
            $rawData['last_percentage'] ?? 
            $rawData['marks_percentage'] ?? null
        );
        
        // ==================== SCHOLARSHIP ELIGIBILITY ====================
        $safeStudent->scholarship_test = $rawData['scholarship_test'] ?? 
                                         $rawData['scholarshipTest'] ?? 
                                         $rawData['appeared_scholarship_test'] ?? 'No';
        
        $safeStudent->board_percentage = $this->parsePercentageSafely(
            $rawData['board_percentage'] ?? 
            $rawData['last_board_percentage'] ?? 
            $rawData['lastBoardPercentage'] ?? null
        );
        
        $safeStudent->is_repeater = $rawData['is_repeater'] ?? 
                                    $rawData['isRepeater'] ?? 
                                    $rawData['repeater'] ?? 'No';
        
        $safeStudent->competition_exam = $rawData['competition_exam'] ?? 
                                         $rawData['competitionExam'] ?? 
                                         $rawData['appeared_competition'] ?? 'No';
        
        // ==================== DOCUMENTS ====================
        $safeStudent->passport_photo = $this->getDocumentData($rawData, 'passport_photo', 'passportPhoto', 'photo');
        $safeStudent->marksheet = $this->getDocumentData($rawData, 'marksheet', 'last_marksheet', 'lastMarksheet');
        $safeStudent->caste_certificate = $this->getDocumentData($rawData, 'caste_certificate', 'casteCertificate');
        $safeStudent->scholarship_proof = $this->getDocumentData($rawData, 'scholarship_proof', 'scholarshipProof', 'scholarship_document');
        $safeStudent->secondary_marksheet = $this->getDocumentData($rawData, 'secondary_marksheet', 'secondaryMarksheet', 'class_10_marksheet');
        $safeStudent->senior_secondary_marksheet = $this->getDocumentData($rawData, 'senior_secondary_marksheet', 'seniorSecondaryMarksheet', 'class_12_marksheet');
        
        // ==================== BATCH ALLOCATION ====================
        $safeStudent->batch_name = $rawData['batch_name'] ?? 
                                   $rawData['batchName'] ?? 
                                   ($student->batch->name ?? ($student->batch->batch_id ?? 'N/A'));
        
        $safeStudent->batch = $student->batch;
        $safeStudent->course = $student->course;
        $safeStudent->shift = $student->shift;
        
        // ==================== STATUS ====================
        $safeStudent->status = $rawData['status'] ?? 'active';
        $safeStudent->created_at = $student->created_at;
        $safeStudent->updated_at = $student->updated_at;
        
        // ==================== FEES DATA ====================
        $this->processFeesDataSafe($safeStudent, $rawData);
        
        // ==================== CALCULATE FEES SUMMARY ====================
        $feeSummary = $this->calculateFeeSummary($rawData);
        
        // ==================== SCHOLARSHIP DATA ====================
        $scholarshipData = $this->getScholarshipData($rawData, $feeSummary);
        $scholarshipEligible = [
            'eligible' => in_array(strtolower($rawData['eligible_for_scholarship'] ?? 'no'), ['yes', 'true', '1']),
            'reason' => $rawData['scholarship_name'] ?? 'N/A',
            'discountPercent' => floatval($rawData['discount_percentage'] ?? 0)
        ];
        
        // ==================== ACTIVITY HISTORY ====================
        $activities = $this->getStudentActivities($student);
        $safeStudent->activities = $activities;
        
        Log::info('Student View Data Prepared:', [
            'student_id' => $id,
            'name' => $safeStudent->student_name,
            'father_name' => $safeStudent->father_name,
            'mother_name' => $safeStudent->mother_name,
            'dob' => $safeStudent->dob,
            'documents_count' => count(array_filter([
                $safeStudent->passport_photo,
                $safeStudent->marksheet,
                $safeStudent->caste_certificate
            ]))
        ]);
        
        return view('student.smstudents.view', compact('safeStudent', 'feeSummary', 'scholarshipEligible', 'scholarshipData'))
            ->with('student', $safeStudent);
    
    } catch (\Exception $e) {
        Log::error('Error showing student: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        return back()->with('error', 'Error loading student: ' . $e->getMessage());
    }
}

/**
 *   HELPER: Parse date safely from multiple formats
 */
private function parseDateSafely($dateValue)
{
    if (!$dateValue || $dateValue === 'N/A' || empty($dateValue)) {
        return 'N/A';
    }
    
    try {
        if (is_string($dateValue)) {
            return Carbon::parse($dateValue)->format('d-m-Y');
        } elseif ($dateValue instanceof Carbon) {
            return $dateValue->format('d-m-Y');
        } elseif ($dateValue instanceof \MongoDB\BSON\UTCDateTime) {
            return $dateValue->toDateTime()->format('d-m-Y');
        }
    } catch (\Exception $e) {
        Log::warning('Date parsing failed:', ['value' => $dateValue, 'error' => $e->getMessage()]);
    }
    
    return 'N/A';
}

/**
 *   HELPER: Parse percentage safely
 */
private function parsePercentageSafely($value)
{
    if (!$value || $value === 'N/A' || empty($value)) {
        return 'N/A';
    }
    
    try {
        return floatval($value);
    } catch (\Exception $e) {
        return 'N/A';
    }
}

/**
 *   HELPER: Get document data from multiple possible field names
 */
private function getDocumentData($rawData, ...$possibleFields)
{
    foreach ($possibleFields as $field) {
        if (isset($rawData[$field]) && $rawData[$field] && $rawData[$field] !== 'N/A') {
            return $rawData[$field];
        }
    }
    return null;
}


 public function listCourseCollections()
    {
        try {
            $collections = Courses::getAllStudentCollections();
            
            $collectionsWithInfo = [];
            foreach ($collections as $collectionName) {
                try {
                    $count = \DB::connection('mongodb')
                        ->getCollection($collectionName)
                        ->count();
                    
                    // Extract course name from collection name
                    $courseName = str_replace('students_', '', $collectionName);
                    $courseName = str_replace('_', ' ', $courseName);
                    $courseName = ucwords($courseName);
                    
                    $collectionsWithInfo[] = [
                        'collection_name' => $collectionName,
                        'course_name' => $courseName,
                        'student_count' => $count
                    ];
                } catch (\Exception $e) {
                    Log::warning("Could not get count for {$collectionName}");
                }
            }
            
            return response()->json([
                'success' => true,
                'collections' => $collectionsWithInfo,
                'total_collections' => count($collectionsWithInfo)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
/**
 *   HELPER: Calculate complete fee summary
 */
private function calculateFeeSummary($rawData)
{
    $paymentHistory = $rawData['paymentHistory'] ?? [];
    if (is_string($paymentHistory)) {
        $paymentHistory = json_decode($paymentHistory, true) ?? [];
    }
    
    $totalPaidFromHistory = 0;
    if (is_array($paymentHistory)) {
        foreach ($paymentHistory as $payment) {
            $totalPaidFromHistory += floatval($payment['amount'] ?? 0);
        }
    }
    
    $totalFeesInclusive = floatval($rawData['total_fees_inclusive_tax'] ?? 0);
    $totalFeesBeforeTax = floatval($rawData['total_fees'] ?? 0);
    $gstAmount = floatval($rawData['gst_amount'] ?? 0);
    
    if ($gstAmount == 0 && $totalFeesBeforeTax > 0) {
        $gstAmount = $totalFeesBeforeTax * 0.18;
    }
    
    if ($totalFeesInclusive == 0 && $totalFeesBeforeTax > 0) {
        $totalFeesInclusive = $totalFeesBeforeTax + $gstAmount;
    }
    
    $totalPaid = floatval($rawData['paid_fees'] ?? $rawData['paidAmount'] ?? $totalPaidFromHistory);
    $remainingBalance = max(0, $totalFeesInclusive - $totalPaid);
    
    return [
        'fees' => [
            'total' => $totalFeesBeforeTax,
            'discount' => floatval($rawData['total_fee_before_discount'] ?? 0) - $totalFeesBeforeTax,
            'paid' => $totalPaid,
            'pending' => $remainingBalance
        ],
        'other_fees' => [
            'total' => 0,
            'paid' => 0,
            'pending' => 0
        ],
        'grand' => [
            'total' => $totalFeesInclusive,
            'paid' => $totalPaid,
            'pending' => $remainingBalance
        ]
    ];
}

/**
 *  Generate unique payment key to detect duplicates
 */
private function generatePaymentKey($entry)
{
    $timestamp = $this->normalizeTimestamp($entry);
    $amount = floatval($entry['amount'] ?? $entry['details']['amount'] ?? 0);
    $installment = $entry['installment_number'] ?? $entry['details']['installment_number'] ?? 'none';
    
    // Round timestamp to nearest hour to catch near-duplicate entries
    $hourTimestamp = floor($timestamp / 3600) * 3600;
    
    return $hourTimestamp . '_' . $amount . '_' . $installment;
}

/**
 * Generate truly unique key for history entries
 */
private function generateUniqueHistoryKey($entry)
{
    $timestamp = $entry['normalized_timestamp'] ?? 0;
    $action = strtolower(trim($entry['action'] ?? ''));
    $description = strtolower(trim($entry['description'] ?? ''));
    
    // For payment entries, use specific details
    if (stripos($action, 'fee paid') !== false || stripos($action, 'payment') !== false) {
        // Extract amount
        $amount = '0';
        if (isset($entry['details']['amount'])) {
            $amount = $entry['details']['amount'];
        } elseif (isset($entry['amount'])) {
            $amount = $entry['amount'];
        } elseif (preg_match('/₹([\d,\.]+)/', $description, $amountMatches)) {
            $amount = str_replace(',', '', $amountMatches[1]);
        }
        
        // Extract installment number
        $installment = 'none';
        if (isset($entry['details']['installment_number'])) {
            $installment = $entry['details']['installment_number'];
        } elseif (isset($entry['installment_number'])) {
            $installment = $entry['installment_number'];
        } elseif (preg_match('/installment #?(\d+)/i', $description, $installmentMatches)) {
            $installment = $installmentMatches[1];
        }
        
        // Extract payment method
        $method = 'unknown';
        if (isset($entry['details']['payment_method'])) {
            $method = strtolower($entry['details']['payment_method']);
        } elseif (isset($entry['payment_method'])) {
            $method = strtolower($entry['payment_method']);
        } elseif (isset($entry['method'])) {
            $method = strtolower($entry['method']);
        }
        
        // Round timestamp to nearest 5 minutes to catch near-duplicates
        $roundedTime = floor($timestamp / 300) * 300;
        
        $key = 'payment_' . $roundedTime . '_' . $amount . '_' . $installment . '_' . $method;
        \Log::info('Generated payment key', [
            'key' => $key, 
            'timestamp' => $timestamp,
            'amount' => $amount,
            'installment' => $installment,
            'method' => $method
        ]);
        return $key;
    }
    
    // For other entries, use action + timestamp + first 50 chars of description
    $roundedTime = floor($timestamp / 60) * 60; // Round to nearest minute
    $key = $action . '_' . $roundedTime . '_' . substr($description, 0, 50);
    
    return md5($key); // Use hash for consistent length
}

private function getScholarshipData($rawData, $feeSummary)
{
    return [
        'eligible' => $rawData['eligible_for_scholarship'] ?? 'No',
        'scholarship_name' => $rawData['scholarship_name'] ?? 'N/A',
        'total_before_discount' => floatval($rawData['total_fee_before_discount'] ?? $feeSummary['fees']['total']),
        'discount_percentage' => floatval($rawData['discount_percentage'] ?? 0),
        'has_discretionary' => ($rawData['discretionary_discount'] ?? 'No') === 'Yes',
        'discretionary_type' => $rawData['discretionary_discount_type'] ?? null,
        'discretionary_value' => floatval($rawData['discretionary_discount_value'] ?? 0),
        'discretionary_reason' => $rawData['discretionary_discount_reason'] ?? null,
    ];
}



/**
 *   Helper: Generate unique roll number
 */
private function generateRollNumber()
{
    $year = date('y');
    $lastStudent = SMstudents::orderBy('created_at', 'desc')->first();
    
    if ($lastStudent && isset($lastStudent->roll_no)) {
        $lastNumber = intval(substr($lastStudent->roll_no, -4));
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    } else {
        $newNumber = '0001';
    }
    
    return 'STU' . $year . $newNumber;
}

    /**
     * Get status badge
     */
    private function getStatusBadge($status)
    {
        $status = strtolower($status ?? 'pending');
        
        return match($status) {
            'paid' => 'success',
            'partial', 'partially_paid' => 'warning',
            'pending' => 'danger',
            'overdue' => 'dark',
            'cancelled' => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Deactivate student
     */
    public function deactivate($id)
    {
        try {
            $student = SMstudents::findOrFail($id);
            $student->update(['status' => 'inactive']);
            
            //   LOG ACTIVITY
            $this->createActivityLog(
                $student,
                'Student Deactivated',
                'Student account was deactivated by ' . (auth()->user()->name ?? 'Admin')
            );

            return redirect()->route('smstudents.index')->with('success', 'Student deactivated successfully');
        } catch (\Exception $e) {
            Log::error('Error deactivating student: ' . $e->getMessage());
            return back()->with('error', 'Failed to deactivate student: ' . $e->getMessage());
        }
    }

    /**
     * Export students data
     */
    public function export(Request $request)
    {
        try {
            $students = SMstudents::with(['batch', 'course', 'shift'])->get();
            
            $filename = 'students_' . date('Y-m-d_His') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($students) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Roll No','Student Name','Email','Phone','Batch Name','Course Name','Course Content','Delivery Mode','Shift','Status']);
                foreach ($students as $student) {
                    fputcsv($file, [
                        $student->roll_no,
                        $student->student_name ?? $student->name,
                        $student->email,
                        $student->phone,
                        $student->batch->name ?? 'N/A',
                        $student->course->name ?? 'N/A',
                        $student->course_content,
                        $student->delivery_mode,
                        $student->shift->name ?? $student->shift ?? 'N/A',
                        ucfirst($student->status)
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            Log::error('Error exporting students: ' . $e->getMessage());
            return back()->with('error', 'Failed to export students: ' . $e->getMessage());
        }
    }

    /**
     * Edit student form
     */
    public function edit($id)
    {
        try {
            $student = SMstudents::with(['batch', 'course', 'shift'])->findOrFail($id);
            $batches = Batch::all();
            $courses = Courses::all();
            $shifts = Shift::where('is_active', true)->get();

            return view('student.smstudents.edit', compact('student', 'batches', 'courses', 'shifts'));
        } catch (\Exception $e) {
            Log::error('Error loading edit form: ' . $e->getMessage());
            return back()->with('error', 'Failed to load student data');
        }
    }

    /**
     * Get student history
     */
    public function history($id)
    {
        try {
            $student = SMstudents::with(['batch', 'course', 'shift'])->findOrFail($id);
            return view('student.smstudents.history', compact('student'));
        } catch (\Exception $e) {
            Log::error('Error loading history: ' . $e->getMessage());
            return back()->with('error', 'Failed to load history');
        }
    }

    /**
     *   Add a fee payment with activity logging
     */
    public function addFee(Request $request, $id)
    {
        $student = SMstudents::findOrFail($id);
        $fees = $student->fees ?? [];
        
        $newFee = [
            'installment_number' => count($fees) + 1,
            'fee_type' => $request->fee_type ?? 'tuition',
            'actual_amount' => floatval($request->actual_amount),
            'discount_amount' => floatval($request->discount_amount ?? 0),
            'paid_amount' => floatval($request->paid_amount ?? 0),
            'due_date' => $request->due_date,
            'paid_date' => $request->paid_date ?? null,
            'status' => $request->status ?? 'pending',
            'payment_method' => $request->payment_method ?? null,
            'transaction_id' => $request->transaction_id ?? null,
            'remarks' => $request->remarks ?? null,
            'created_at' => now()->toDateTimeString()
        ];
        
        $fees[] = $newFee;
        $student->fees = $fees;
        $student->save();
        
        //   LOG ACTIVITY
        $this->createActivityLog(
            $student,
            'Fee Entry Added',
            'New fee installment added: ₹' . number_format($newFee['actual_amount'], 2) . ' (' . ucfirst($newFee['fee_type']) . ')',
            ['fee_details' => $newFee]
        );

        return redirect()->back()->with('success', 'Fee added successfully');
    }

    /**
     *   Add a transaction with activity logging
     */
    public function addTransaction(Request $request, $id)
    {
        $student = SMstudents::findOrFail($id);
        $transactions = $student->transactions ?? [];
        
        $newTransaction = [
            'transaction_id' => $request->transaction_id ?? 'TXN' . time(),
            'fee_type' => $request->fee_type,
            'amount' => floatval($request->amount),
            'payment_method' => $request->payment_method,
            'payment_date' => now()->toDateTimeString(),
            'received_by' => auth()->user()->name ?? 'Admin',
            'remarks' => $request->remarks ?? null
        ];
        
        $transactions[] = $newTransaction;
        $student->transactions = $transactions;
        $student->save();
        
        //   LOG ACTIVITY
        $this->createActivityLog(
            $student,
            'Payment Received',
            'Payment of ₹' . number_format($newTransaction['amount'], 2) . ' received via ' . ucfirst($newTransaction['payment_method']),
            ['transaction_details' => $newTransaction]
        );

        return redirect()->back()->with('success', 'Transaction recorded successfully');
    }

/**
 * Override getAttribute to check multiple field names
 */
public function getAttribute($key)
{
    // Field name mappings
    $mappings = [
        'student_name' => ['student_name', 'name'],
        'father_name' => ['father_name', 'father'],
        'mother_name' => ['mother_name', 'mother'],
        'father_contact' => ['father_contact', 'mobileNumber'],
        'board_percentage' => ['board_percentage', 'last_board_percentage', 'lastBoardPercentage'],
    ];
    
    // Check if this key has mappings
    if (isset($mappings[$key])) {
        foreach ($mappings[$key] as $field) {
            $value = parent::getAttribute($field);
            if ($value !== null && $value !== 'N/A' && $value !== '') {
                return $value;
            }
        }
    }
    
    return parent::getAttribute($key);
}


  public function store(Request $request)
    {
        try {
            Log::info('=== Test Series Store - START ===', $request->all());
            
            $rules = [
                'course_id' => 'required|string',
                'test_type' => 'required|in:Type1,Type2',
                'subject_type' => 'required|in:Single,Double',
                'test_count' => 'required|integer|min:1',
                'subjects' => 'required|array|min:1',
            ];

            if ($request->test_type === 'Type1') {
                $rules['test_series_name'] = 'required|string|max:255';
            }

            $validated = $request->validate($rules);

            $course = Courses::find($validated['course_id']);
            if (!$course) {
                return back()->withInput()
                    ->with('error', 'Course not found!');
            }

            //   : Always get the course name properly
            $courseName = $course->course_name ?? $course->name;
            
            Log::info('Course details', [
                'course_id' => $validated['course_id'],
                'course_name' => $courseName
            ]);
            
            // Get next test number for this course
            $lastTest = TestSeries::where('course_id', $validated['course_id'])
                ->where('test_type', $validated['test_type'])
                ->orderBy('test_number', 'desc')
                ->first();
            
            $testNumber = ($lastTest ? $lastTest->test_number : 0) + 1;

            // Build test name
            if ($request->test_type === 'Type1') {
                $testName = $courseName . '/Type1/' . $validated['test_series_name'] . '/' . str_pad($testNumber, 3, '0', STR_PAD_LEFT);
            } else {
                $testName = $courseName . '/Type2/' . str_pad($testNumber, 3, '0', STR_PAD_LEFT);
            }

            // Get students enrolled in this course
            $students = SMstudents::where('course_id', $validated['course_id'])
                ->where('status', 'active')
                ->pluck('_id')
                ->toArray();

            $testSeriesData = [
                'course_id' => $validated['course_id'],
                'course_name' => $courseName, //   : Always set course_name
                'test_name' => $testName,
                'test_type' => $validated['test_type'],
                'subject_type' => $validated['subject_type'],
                'test_count' => $validated['test_count'],
                'test_number' => $testNumber,
                'status' => 'Pending',
                'subjects' => $validated['subjects'],
                'students_enrolled' => $students,
                'students_count' => count($students),
                'created_by' => Auth::check() ? Auth::user()->name : 'Admin',
            ];

            if ($request->test_type === 'Type1') {
                $testSeriesData['test_series_name'] = $validated['test_series_name'];
            }

            $testSeries = TestSeries::create($testSeriesData);

            Log::info('Test Series Created Successfully', ['id' => $testSeries->_id]);

            return redirect()
                ->route('test_series.show', urlencode($courseName))
                ->with('success', 'Test Series created successfully!');

        } catch (\Exception $e) {
            Log::error('Test Series Store ERROR: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }


/**
 * Get student history with NO DUPLICATES
 */
public function getHistory($id)
{
    try {
        \Log::info('=== GET HISTORY CALLED (SMStudents) ===', ['id' => $id]);
        
        $student = SMstudents::find($id);
        
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], 404);
        }
        
        \Log::info('Student found', ['name' => $student->student_name ?? $student->name]);
        
        $rawData = $student->getAttributes();
        
        //  SINGLE SOURCE OF TRUTH
        $allEntries = [];
        $seenSignatures = []; // Use unique signatures instead of keys
        
        // 1.  PRIMARY SOURCE: Get from 'history' field FIRST
        if (isset($rawData['history']) && !empty($rawData['history'])) {
            $storedHistory = is_string($rawData['history']) 
                ? json_decode($rawData['history'], true) 
                : $rawData['history'];
            
            if (is_array($storedHistory)) {
                foreach ($storedHistory as $entry) {
                    if (!is_array($entry)) continue;
                    
                    $entry['normalized_timestamp'] = $this->normalizeTimestamp($entry);
                    $signature = $this->generateEntrySignature($entry);
                    
                    if (!isset($seenSignatures[$signature])) {
                        $seenSignatures[$signature] = true;
                        $allEntries[] = $entry;
                        \Log::info('  Added from history', [
                            'action' => $entry['action'] ?? 'N/A',
                            'signature' => substr($signature, 0, 50)
                        ]);
                    } else {
                        \Log::info('  Skipped duplicate from history', [
                            'action' => $entry['action'] ?? 'N/A'
                        ]);
                    }
                }
            }
        }
        
        // 2. Get from 'activities' field (only if NOT in history)
        if (isset($rawData['activities']) && !empty($rawData['activities'])) {
            $activities = is_string($rawData['activities'])
                ? json_decode($rawData['activities'], true)
                : $rawData['activities'];
            
            if (is_array($activities)) {
                foreach ($activities as $activity) {
                    if (!is_array($activity)) continue;
                    
                    $entry = [
                        'action' => $activity['title'] ?? 'Activity',
                        'description' => $activity['description'] ?? 'Activity recorded',
                        'user' => $activity['performed_by'] ?? 'Admin',
                        'timestamp' => $activity['created_at'] ?? $activity['timestamp'] ?? now()->toIso8601String(),
                        'created_at' => $activity['created_at'] ?? now()->toDateTimeString(),
                    ];
                    
                    $entry['normalized_timestamp'] = $this->normalizeTimestamp($entry);
                    $signature = $this->generateEntrySignature($entry);
                    
                    if (!isset($seenSignatures[$signature])) {
                        $seenSignatures[$signature] = true;
                        $allEntries[] = $entry;
                        \Log::info('  Added from activities', ['action' => $entry['action']]);
                    } else {
                        \Log::info('  Skipped duplicate from activities', ['action' => $entry['action']]);
                    }
                }
            }
        }
        
        // 3.  DO NOT USE paymentHistory - it's already in history!
        // The payment entries are added to 'history' when fees are paid,
        // so we skip paymentHistory completely to avoid duplicates
        \Log::info('  Skipping paymentHistory to prevent duplicates');
        
        //  Sort by timestamp (DESCENDING = newest first)
        usort($allEntries, function($a, $b) {
            return ($b['normalized_timestamp'] ?? 0) <=> ($a['normalized_timestamp'] ?? 0);
        });
        
        \Log::info('  History sorted successfully', [
            'total_entries' => count($allEntries),
            'first_entry' => $allEntries[0]['action'] ?? 'N/A',
            'first_timestamp' => $allEntries[0]['normalized_timestamp'] ?? 'N/A',
        ]);
        
        // Calculate fees summary
        $totalFees = floatval($rawData['total_fees_inclusive_tax'] ?? 0);
        $totalPaid = floatval($rawData['paid_fees'] ?? $rawData['paidAmount'] ?? 0);
        $remaining = max(0, $totalFees - $totalPaid);
        
        return response()->json([
            'success' => true,
            'data' => $allEntries,
            'student_name' => $student->student_name ?? $student->name ?? 'N/A',
            'roll_no' => $student->roll_no ?? 'N/A',
            'total_paid' => $totalPaid,
            'remaining' => $remaining,
            'total_fees' => $totalFees
        ]);
        
    } catch (\Exception $e) {
        \Log::error(' History error: ' . $e->getMessage(), [
            'line' => $e->getLine(),
            'student_id' => $id ?? 'unknown'
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Error loading history: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Generate unique signature for each entry
 * This creates a fingerprint based on content, not just metadata
 */
private function generateEntrySignature($entry)
{
    $action = strtolower(trim($entry['action'] ?? ''));
    $description = strtolower(trim($entry['description'] ?? ''));
    $user = strtolower(trim($entry['user'] ?? $entry['performed_by'] ?? ''));
    $timestamp = $entry['normalized_timestamp'] ?? $entry['timestamp'] ?? 0;
    
    // For payment entries, extract key details
    if (stripos($action, 'fee') !== false || stripos($action, 'payment') !== false) {
        // Extract amount from description or details
        $amount = 0;
        if (isset($entry['details']['amount'])) {
            $amount = $entry['details']['amount'];
        } elseif (preg_match('/₹\s*([\d,]+(?:\.\d{2})?)/', $description, $matches)) {
            $amount = str_replace(',', '', $matches[1]);
        }
        
        // Extract installment number
        $installment = 'none';
        if (isset($entry['details']['installment_number'])) {
            $installment = $entry['details']['installment_number'];
        } elseif (preg_match('/installment\s*#?(\d+)/i', $description, $matches)) {
            $installment = $matches[1];
        }
        
        // Extract method
        $method = 'unknown';
        if (isset($entry['details']['payment_method'])) {
            $method = strtolower($entry['details']['payment_method']);
        } elseif (preg_match('/via\s+(\w+)/i', $description, $matches)) {
            $method = strtolower($matches[1]);
        }
        
        // Round timestamp to nearest 10 minutes for payment matching
        $roundedTime = floor($timestamp / 600) * 600;
        
        // Create signature
        $signature = sprintf(
            'payment_%s_%s_%s_%s',
            $roundedTime,
            $amount,
            $installment,
            $method
        );
        
        \Log::info('Payment signature generated', [
            'signature' => $signature,
            'amount' => $amount,
            'installment' => $installment,
            'method' => $method
        ]);
        
        return md5($signature);
    }
    
    // For non-payment entries
    // Use first 100 chars of description + action + rounded time
    $roundedTime = floor($timestamp / 300) * 300; // 5 minute window
    $signature = $action . '_' . $roundedTime . '_' . substr($description, 0, 100) . '_' . $user;
    
    return md5($signature);
}

/**
 * Keep normalizeTimestamp
 */
private function normalizeTimestamp($entry)
{
    $timestamp = $entry['timestamp'] ?? $entry['created_at'] ?? $entry['date'] ?? $entry['payment_date'] ?? null;
    
    if (!$timestamp) {
        return time();
    }
    
    if (is_numeric($timestamp) && $timestamp > 1000000000 && $timestamp < 9999999999) {
        return intval($timestamp);
    }
    
    try {
        if ($timestamp instanceof \MongoDB\BSON\UTCDateTime) {
            return $timestamp->toDateTime()->getTimestamp();
        }
        
        $time = strtotime($timestamp);
        if ($time !== false && $time > 0) {
            return $time;
        }
        
        return \Carbon\Carbon::parse($timestamp)->timestamp;
        
    } catch (\Exception $e) {
        \Log::warning('Failed to normalize timestamp', [
            'timestamp' => $timestamp,
            'error' => $e->getMessage()
        ]);
        return time();
    }
}

/**
 * ENHANCED: Process fees data safely with all previous data
 */
private function processFeesDataSafe(&$safeStudent, $rawData)
{
    // Get fees from student object or raw data
    $rawFees = $safeStudent->fees ?? $rawData['fees'] ?? [];
    $rawOtherFees = $safeStudent->other_fees ?? $rawData['other_fees'] ?? [];
    $rawTransactions = $safeStudent->transactions ?? $rawData['transactions'] ?? [];
    
    if (is_string($rawFees)) {
        $rawFees = json_decode($rawFees, true) ?? [];
    }
    if (is_string($rawOtherFees)) {
        $rawOtherFees = json_decode($rawOtherFees, true) ?? [];
    }
    if (is_string($rawTransactions)) {
        $rawTransactions = json_decode($rawTransactions, true) ?? [];
    }
    
    $safeStudent->fees = collect(is_array($rawFees) ? $rawFees : []);
    $safeStudent->other_fees = collect(is_array($rawOtherFees) ? $rawOtherFees : []);
    $safeStudent->transactions = collect(is_array($rawTransactions) ? $rawTransactions : []);
    
    // Process fees collection
    if ($safeStudent->fees->isNotEmpty()) {
        $safeStudent->fees = $safeStudent->fees->map(function ($fee) {
            if (is_string($fee)) {
                try {
                    $fee = json_decode($fee, true);
                } catch (\Exception $e) {
                    return null;
                }
            }
            
            if (!is_array($fee)) {
                return null;
            }
            
            // Parse dates
            $fee['due_date'] = $this->parseDateSafely($fee['due_date'] ?? null);
            $fee['paid_date'] = $this->parseDateSafely($fee['paid_date'] ?? null);
            
            // Calculate remaining amount
            $actualAmount = floatval($fee['actual_amount'] ?? 0);
            $discountAmount = floatval($fee['discount_amount'] ?? 0);
            $paidAmount = floatval($fee['paid_amount'] ?? 0);
            $fee['remaining_amount'] = $actualAmount - $discountAmount - $paidAmount;
            
            // Set status
            if (!isset($fee['status'])) {
                if ($paidAmount >= ($actualAmount - $discountAmount)) {
                    $fee['status'] = 'paid';
                } elseif ($paidAmount > 0) {
                    $fee['status'] = 'partial';
                } else {
                    $fee['status'] = 'pending';
                }
            }
            
            $fee['status_badge'] = $this->getStatusBadge($fee['status']);
            
            return $fee;
        })->filter();
    }
    
    // Process other_fees collection
    if ($safeStudent->other_fees->isNotEmpty()) {
        $safeStudent->other_fees = $safeStudent->other_fees->map(function ($fee) {
            if (is_string($fee)) {
                try {
                    $fee = json_decode($fee, true);
                } catch (\Exception $e) {
                    return null;
                }
            }
            
            if (!is_array($fee)) {
                return null;
            }
            
            // Parse dates
            $fee['due_date'] = $this->parseDateSafely($fee['due_date'] ?? null);
            $fee['paid_date'] = $this->parseDateSafely($fee['paid_date'] ?? null);
            
            // Calculate remaining amount
            $actualAmount = floatval($fee['actual_amount'] ?? 0);
            $paidAmount = floatval($fee['paid_amount'] ?? 0);
            $fee['remaining_amount'] = $actualAmount - $paidAmount;
            
            // Set status
            if (!isset($fee['status'])) {
                if ($paidAmount >= $actualAmount) {
                    $fee['status'] = 'paid';
                } elseif ($paidAmount > 0) {
                    $fee['status'] = 'partial';
                } else {
                    $fee['status'] = 'pending';
                }
            }
            
            $fee['status_badge'] = $this->getStatusBadge($fee['status']);
            
            return $fee;
        })->filter();
    }
    
    // Process transactions collection
    if ($safeStudent->transactions->isNotEmpty()) {
        $safeStudent->transactions = $safeStudent->transactions->map(function ($transaction) {
            if (is_string($transaction)) {
                try {
                    $transaction = json_decode($transaction, true);
                } catch (\Exception $e) {
                    return null;
                }
            }
            
            if (!is_array($transaction)) {
                return null;
            }
            
            // Parse payment date
            $transaction['payment_date'] = $this->parseDateSafely($transaction['payment_date'] ?? null);
            
            return $transaction;
        })->filter();
    }
}

/**
 * Display student's personal attendance view
 */
public function studentAttendance($id)
{
    try {
        $student = SMstudents::with(['batch', 'course', 'shift'])->findOrFail($id);
        
        // Get attendance records for this student
        $currentMonth = request()->get('month', date('Y-m'));
        
        Log::info('Loading student attendance view', [
            'student_id' => $id,
            'month' => $currentMonth
        ]);
        
        return view('student.smstudents.attendance', compact('student', 'currentMonth'));
        
    } catch (\Exception $e) {
        Log::error('Error loading student attendance: ' . $e->getMessage());
        return back()->with('error', 'Failed to load attendance data');
    }
}

/**
 * Get individual student's attendance data
 */
public function getStudentAttendance(Request $request, $id)
{
    try {
        $student = SMstudents::findOrFail($id);
        $month = $request->get('month', date('Y-m'));
        
        // Parse month
        $year = (int) substr($month, 0, 4);
        $monthNum = (int) substr($month, 5, 2);
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $monthNum, $year);
        
        $firstDate = sprintf('%04d-%02d-01', $year, $monthNum);
        $lastDate = sprintf('%04d-%02d-%02d', $year, $monthNum, $daysInMonth);
        
        // Fetch attendance records
        $attendanceRecords = \App\Models\Attendance\Student::where('student_id', (string)$id)
            ->where('date', '>=', $firstDate)
            ->where('date', '<=', $lastDate)
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date');
        
        // Calculate statistics
        $presentCount = $attendanceRecords->where('status', 'present')->count();
        $absentCount = $attendanceRecords->where('status', 'absent')->count();
        
        // Calculate working days (exclude weekends)
        $totalWorkingDays = 0;
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf('%04d-%02d-%02d', $year, $monthNum, $day);
            $dayOfWeek = date('w', strtotime($date));
            if ($dayOfWeek != 0 && $dayOfWeek != 6) {
                $totalWorkingDays++;
            }
        }
        
        $attendancePercentage = $totalWorkingDays > 0 
            ? round(($presentCount / $totalWorkingDays) * 100, 2) 
            : 0;
        
        // Generate calendar data
        $calendarData = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateStr = sprintf('%04d-%02d-%02d', $year, $monthNum, $day);
            $timestamp = strtotime($dateStr);
            $dayOfWeek = date('w', $timestamp);
            
            $status = 'not-marked';
            if (isset($attendanceRecords[$dateStr])) {
                $status = $attendanceRecords[$dateStr]->status;
            } elseif ($dayOfWeek == 0 || $dayOfWeek == 6) {
                $status = 'weekend';
            }
            
            $calendarData[] = [
                'date' => $dateStr,
                'day' => $day,
                'day_name' => date('D', $timestamp),
                'is_weekend' => ($dayOfWeek == 0 || $dayOfWeek == 6),
                'status' => $status,
                'marked_at' => isset($attendanceRecords[$dateStr]) 
                    ? $attendanceRecords[$dateStr]->marked_at 
                    : null,
                'marked_by' => isset($attendanceRecords[$dateStr]) 
                    ? $attendanceRecords[$dateStr]->marked_by 
                    : null
            ];
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'calendar' => $calendarData,
                'statistics' => [
                    'total_days' => $daysInMonth,
                    'working_days' => $totalWorkingDays,
                    'present' => $presentCount,
                    'absent' => $absentCount,
                    'not_marked' => $totalWorkingDays - $presentCount - $absentCount,
                    'percentage' => $attendancePercentage
                ],
                'student' => [
                    'roll_no' => $student->roll_no,
                    'name' => $student->student_name ?? $student->name,
                    'batch' => $student->batch_name,
                    'course' => $student->course_name
                ]
            ]
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error fetching student attendance: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to load attendance data'
        ], 500);
    }
}

/**
 * Get individual student's attendance data for the view detail page
 */


public function getStudentAttendanceData(Request $request, $id)
{
    try {
        $student = SMstudents::findOrFail($id);
        $month = $request->get('month', date('Y-m'));
        
        \Log::info('  Loading student attendance', [
            'student_id' => $id,
            'month' => $month
        ]);
        
        // Parse month
        $year = (int) substr($month, 0, 4);
        $monthNum = (int) substr($month, 5, 2);
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $monthNum, $year);
        
        $firstDate = sprintf('%04d-%02d-01', $year, $monthNum);
        $lastDate = sprintf('%04d-%02d-%02d', $year, $monthNum, $daysInMonth);
        
        // Fetch attendance records from attendance_students collection
        $attendanceRecords = \App\Models\Attendance\Student::where('student_id', (string)$id)
            ->where('date', '>=', $firstDate)
            ->where('date', '<=', $lastDate)
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date');
        
        \Log::info('  Found attendance records', ['count' => $attendanceRecords->count()]);
        
        // Calculate statistics
        $presentCount = $attendanceRecords->where('status', 'present')->count();
        $absentCount = $attendanceRecords->where('status', 'absent')->count();
        
        // Calculate working days (exclude weekends)
        $totalWorkingDays = 0;
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf('%04d-%02d-%02d', $year, $monthNum, $day);
            $dayOfWeek = date('w', strtotime($date));
            if ($dayOfWeek != 0 && $dayOfWeek != 6) {
                $totalWorkingDays++;
            }
        }
        
        $attendancePercentage = $totalWorkingDays > 0 
            ? round(($presentCount / $totalWorkingDays) * 100, 2) 
            : 0;
        
        // Generate calendar data
        $calendarData = [];
        $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateStr = sprintf('%04d-%02d-%02d', $year, $monthNum, $day);
            $timestamp = strtotime($dateStr);
            $dayOfWeek = date('w', $timestamp);
            
            $status = 'not-marked';
            $markedBy = null;
            $markedAt = null;
            
            if (isset($attendanceRecords[$dateStr])) {
                $record = $attendanceRecords[$dateStr];
                $status = $record->status;
                $markedBy = $record->marked_by;
                $markedAt = $record->marked_at;
            } elseif ($dayOfWeek == 0 || $dayOfWeek == 6) {
                $status = 'weekend';
            }
            
            $calendarData[] = [
                'date' => $dateStr,
                'day' => $day,
                'day_name' => $dayNames[$dayOfWeek],
                'is_weekend' => ($dayOfWeek == 0 || $dayOfWeek == 6),
                'is_today' => $dateStr === date('Y-m-d'),
                'status' => $status,
                'marked_at' => $markedAt,
                'marked_by' => $markedBy
            ];
        }
        
        // Generate monthly summary (all 12 months)
        $monthlySummary = [];
        $months = [
            '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
            '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
            '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
        ];
        
        foreach ($months as $monthKey => $monthName) {
            $monthDays = cal_days_in_month(CAL_GREGORIAN, (int)$monthKey, $year);
            
            // Calculate working days
            $workingDays = 0;
            for ($d = 1; $d <= $monthDays; $d++) {
                $checkDate = sprintf('%04d-%02d-%02d', $year, (int)$monthKey, $d);
                $dow = date('w', strtotime($checkDate));
                if ($dow != 0 && $dow != 6) $workingDays++;
            }
            
            // Get attendance
            $firstDay = sprintf('%04d-%02d-01', $year, (int)$monthKey);
            $lastDay = sprintf('%04d-%02d-%02d', $year, (int)$monthKey, $monthDays);
            
            $monthRecords = \App\Models\Attendance\Student::where('student_id', (string)$id)
                ->where('date', '>=', $firstDay)
                ->where('date', '<=', $lastDay)
                ->get();
            
            $monthPresent = $monthRecords->where('status', 'present')->count();
            $monthAbsent = $monthRecords->where('status', 'absent')->count();
            
            $monthlySummary[] = [
                'month' => $monthName,
                'total_days' => $workingDays,
                'present' => $monthPresent,
                'absent' => $monthAbsent
            ];
        }
        
        // Generate chart data (year-to-date)
        $chartLabels = [];
        $chartData = [];
        $currentMonthNum = (int)date('m');
        
        for ($m = 1; $m <= $currentMonthNum; $m++) {
            $monthKey = str_pad($m, 2, '0', STR_PAD_LEFT);
            $monthDays = cal_days_in_month(CAL_GREGORIAN, $m, $year);
            
            $workingDays = 0;
            for ($d = 1; $d <= $monthDays; $d++) {
                $checkDate = sprintf('%04d-%02d-%02d', $year, $m, $d);
                $dow = date('w', strtotime($checkDate));
                if ($dow != 0 && $dow != 6) $workingDays++;
            }
            
            $firstDay = sprintf('%04d-%02d-01', $year, $m);
            $lastDay = sprintf('%04d-%02d-%02d', $year, $m, $monthDays);
            
            $monthRecords = \App\Models\Attendance\Student::where('student_id', (string)$id)
                ->where('date', '>=', $firstDay)
                ->where('date', '<=', $lastDay)
                ->get();
            
            $monthPresent = $monthRecords->where('status', 'present')->count();
            $percentage = $workingDays > 0 ? round(($monthPresent / $workingDays) * 100, 2) : 0;
            
            $chartLabels[] = date('M', mktime(0, 0, 0, $m, 1));
            $chartData[] = $percentage;
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'calendar' => $calendarData,
                'monthly_summary' => $monthlySummary,
                'chart' => [
                    'labels' => $chartLabels,
                    'data' => $chartData
                ],
                'statistics' => [
                    'month' => date('F Y', strtotime($month . '-01')),
                    'total_days' => $daysInMonth,
                    'working_days' => $totalWorkingDays,
                    'present' => $presentCount,
                    'absent' => $absentCount,
                    'not_marked' => $totalWorkingDays - $presentCount - $absentCount,
                    'percentage' => $attendancePercentage
                ]
            ]
        ]);
        
    } catch (\Exception $e) {
        \Log::error(' Error fetching attendance', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to load attendance: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Get comprehensive fees data for a student
 */
private function getStudentFeesData($student)
{
    // Initialize fees arrays
    $fees = [];
    $otherFees = [];
    $transactions = [];
    
    // Get payment history
    $paymentHistory = $student->paymentHistory ?? [];
    if (!is_array($paymentHistory)) {
        $paymentHistory = [];
    }
    
    // Calculate total fees
    $totalFees = floatval($student->total_fees_inclusive_tax ?? $student->total_fees ?? 0);
    
    // If total fees is 0, try to calculate from installments
    if ($totalFees == 0) {
        $totalFees = floatval($student->installment_1 ?? 0) 
                   + floatval($student->installment_2 ?? 0) 
                   + floatval($student->installment_3 ?? 0);
    }
    
    // Calculate GST if not present
    $gstAmount = floatval($student->gst_amount ?? 0);
    if ($gstAmount == 0 && $totalFees > 0) {
        $baseAmount = $totalFees / 1.18;
        $gstAmount = $totalFees - $baseAmount;
    }
    
    // Calculate total paid from payment history
    $totalPaid = 0;
    foreach ($paymentHistory as $payment) {
        $totalPaid += floatval($payment['amount'] ?? 0);
    }
    
    // Fallback to stored paid amount if no payment history
    if ($totalPaid == 0) {
        $totalPaid = floatval($student->paid_fees ?? $student->paidAmount ?? 0);
    }
    
    $remainingFees = max(0, $totalFees - $totalPaid);
    
    // Define installments (40%, 30%, 30% split)
    $installment1Amount = floatval($student->installment_1 ?? ($totalFees * 0.40));
    $installment2Amount = floatval($student->installment_2 ?? ($totalFees * 0.30));
    $installment3Amount = floatval($student->installment_3 ?? ($totalFees * 0.30));
    
    // Track installment payments
    $inst1Paid = 0;
    $inst2Paid = 0;
    $inst3Paid = 0;
    $inst1Date = null;
    $inst2Date = null;
    $inst3Date = null;
    
    // Process payment history to determine installment status
    foreach ($paymentHistory as $payment) {
        $amount = floatval($payment['amount'] ?? 0);
        $instNum = $payment['installment_number'] ?? null;
        $payDate = $payment['date'] ?? $payment['payment_date'] ?? null;
        
        if ($instNum == 1) {
            $inst1Paid += $amount;
            if (!$inst1Date) $inst1Date = $payDate;
        } elseif ($instNum == 2) {
            $inst2Paid += $amount;
            if (!$inst2Date) $inst2Date = $payDate;
        } elseif ($instNum == 3) {
            $inst3Paid += $amount;
            if (!$inst3Date) $inst3Date = $payDate;
        }
    }
    
    // Build fees array (installments)
    $fees = [
        [
            'installment_number' => 1,
            'fee_type' => 'Course Fee - Installment 1',
            'actual_amount' => $installment1Amount,
            'discount_amount' => 0,
            'paid_amount' => $inst1Paid,
            'remaining_amount' => max(0, $installment1Amount - $inst1Paid),
            'due_date' => null, // You can add due date logic here
            'paid_date' => $inst1Date,
            'status' => $inst1Paid >= $installment1Amount ? 'paid' : ($inst1Paid > 0 ? 'partial' : 'pending'),
            'status_badge' => $inst1Paid >= $installment1Amount ? 'success' : ($inst1Paid > 0 ? 'warning' : 'danger')
        ],
        [
            'installment_number' => 2,
            'fee_type' => 'Course Fee - Installment 2',
            'actual_amount' => $installment2Amount,
            'discount_amount' => 0,
            'paid_amount' => $inst2Paid,
            'remaining_amount' => max(0, $installment2Amount - $inst2Paid),
            'due_date' => null,
            'paid_date' => $inst2Date,
            'status' => $inst2Paid >= $installment2Amount ? 'paid' : ($inst2Paid > 0 ? 'partial' : 'pending'),
            'status_badge' => $inst2Paid >= $installment2Amount ? 'success' : ($inst2Paid > 0 ? 'warning' : 'danger')
        ],
        [
            'installment_number' => 3,
            'fee_type' => 'Course Fee - Installment 3',
            'actual_amount' => $installment3Amount,
            'discount_amount' => 0,
            'paid_amount' => $inst3Paid,
            'remaining_amount' => max(0, $installment3Amount - $inst3Paid),
            'due_date' => null,
            'paid_date' => $inst3Date,
            'status' => $inst3Paid >= $installment3Amount ? 'paid' : ($inst3Paid > 0 ? 'partial' : 'pending'),
            'status_badge' => $inst3Paid >= $installment3Amount ? 'success' : ($inst3Paid > 0 ? 'warning' : 'danger')
        ]
    ];
    
    // Build transactions array from payment history
    foreach ($paymentHistory as $index => $payment) {
        $transactions[] = [
            'transaction_id' => $payment['transaction_id'] ?? 'TXN' . str_pad($index + 1, 6, '0', STR_PAD_LEFT),
            'fee_type' => 'installment',
            'installment_number' => $payment['installment_number'] ?? null,
            'amount' => floatval($payment['amount'] ?? 0),
            'payment_method' => $payment['method'] ?? $payment['payment_type'] ?? 'cash',
            'payment_date' => $payment['date'] ?? $payment['payment_date'] ?? null,
            'received_by' => $payment['recorded_by'] ?? 'Admin',
            'remarks' => $payment['remarks'] ?? '-'
        ];
    }
    
    // Calculate summary
    $feeSummary = [
        'fees' => [
            'total' => $installment1Amount + $installment2Amount + $installment3Amount,
            'paid' => $inst1Paid + $inst2Paid + $inst3Paid,
            'pending' => max(0, ($installment1Amount + $installment2Amount + $installment3Amount) - ($inst1Paid + $inst2Paid + $inst3Paid)),
            'discount' => 0
        ],
        'other_fees' => [
            'total' => 0,
            'paid' => 0,
            'pending' => 0
        ],
        'grand' => [
            'total' => $totalFees,
            'paid' => $totalPaid,
            'pending' => $remainingFees
        ]
    ];
    
    // Scholarship data
    $scholarshipData = [
        'eligible' => ($student->eligible_for_scholarship ?? 'No') === 'Yes' ? 'Yes' : 'No',
        'scholarship_name' => $student->scholarship_name ?? 'N/A',
        'total_before_discount' => floatval($student->total_fee_before_discount ?? $totalFees),
        'discount_percentage' => floatval($student->discount_percentage ?? 0),
        'has_discretionary' => ($student->discretionary_discount ?? 'No') === 'Yes',
        'discretionary_type' => $student->discretionary_discount_type ?? null,
        'discretionary_value' => floatval($student->discretionary_discount_value ?? 0),
        'discretionary_reason' => $student->discretionary_discount_reason ?? null,
    ];
    
    return [
        'fees' => collect($fees),
        'other_fees' => collect($otherFees),
        'transactions' => collect($transactions),
        'feeSummary' => $feeSummary,
        'scholarshipData' => $scholarshipData,
        'scholarshipEligible' => [
            'eligible' => $scholarshipData['eligible'] === 'Yes',
            'reason' => $scholarshipData['scholarship_name'],
            'discountPercent' => $scholarshipData['discount_percentage']
        ]
    ];
}
}