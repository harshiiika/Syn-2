<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student\SMstudents;
use App\Models\Master\Batch;
use App\Models\Master\Courses;
use App\Models\Student\Shift; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SmStudentsController extends Controller
{
    /**
     * Display a listing of students
     */
    public function index()
    {
        try {
            $students = SMstudents::with(['batch', 'course', 'shift'])
                ->orderBy('created_at', 'desc')
                ->get();
                
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
 * ✅ Display the test series for a specific student
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
    

    /**
     * ✅ NEW METHOD: Get all activities for a student
     */
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
                        \Carbon\Carbon::parse($activity['created_at']) : 
                        \Carbon\Carbon::now()
                ];
            }
        }
        
        // Sort by date (newest first)
        usort($activities, function($a, $b) {
            return $b['created_at']->timestamp - $a['created_at']->timestamp;
        });
        
        return $activities;
    }

    /**
     * ✅ ENHANCED: Create activity log entry
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
            
            // ✅ LOG ACTIVITY
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
     * ✅ ENHANCED: Update student password with activity logging
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
            
            // ✅ LOG ACTIVITY
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
     * ✅ ENHANCED: Update student shift with activity logging
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
            
            // ✅ LOG ACTIVITY
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
     * ✅ ENHANCED: Update student batch with activity logging
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
            
            // ✅ LOG ACTIVITY
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
 * ✅ ENHANCED: Display the specified student with COMPLETE data from all previous stages
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
 * ✅ HELPER: Parse date safely from multiple formats
 */
private function parseDateSafely($dateValue)
{
    if (!$dateValue || $dateValue === 'N/A' || empty($dateValue)) {
        return 'N/A';
    }
    
    try {
        if (is_string($dateValue)) {
            return \Carbon\Carbon::parse($dateValue)->format('d-m-Y');
        } elseif ($dateValue instanceof \Carbon\Carbon) {
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
 * ✅ HELPER: Parse percentage safely
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
 * ✅ HELPER: Get document data from multiple possible field names
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

/**
 * ✅ HELPER: Calculate complete fee summary
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
 * ✅ HELPER: Get scholarship data
 */
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
 * ✅ ENHANCED: Process fees data safely with all previous data
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
    
    // Similar processing for other_fees and transactions...
    // (keeping existing logic from your controller)
}


/**
 * ✅ Helper: Generate unique roll number
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
            
            // ✅ LOG ACTIVITY
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
     * ✅ Add a fee payment with activity logging
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
        
        // ✅ LOG ACTIVITY
        $this->createActivityLog(
            $student,
            'Fee Entry Added',
            'New fee installment added: ₹' . number_format($newFee['actual_amount'], 2) . ' (' . ucfirst($newFee['fee_type']) . ')',
            ['fee_details' => $newFee]
        );

        return redirect()->back()->with('success', 'Fee added successfully');
    }

    /**
     * ✅ Add a transaction with activity logging
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
        
        // ✅ LOG ACTIVITY
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
}