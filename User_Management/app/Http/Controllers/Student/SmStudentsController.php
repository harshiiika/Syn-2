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
     * Display the specified student with full details
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
            
            // Basic Info
            $safeStudent->_id = $student->_id ?? $id;
            $safeStudent->roll_no = $rawData['roll_no'] ?? 'N/A';
            $safeStudent->student_name = $rawData['student_name'] ?? $rawData['name'] ?? 'N/A';
            $safeStudent->email = $rawData['email'] ?? 'N/A';
            $safeStudent->phone = $rawData['phone'] ?? 'N/A';
            
            // Personal Details
            $safeStudent->father_name = $rawData['father_name'] ?? 'N/A';
            $safeStudent->mother_name = $rawData['mother_name'] ?? 'N/A';
            
            // Handle DOB
            if (isset($rawData['dob']) && $rawData['dob'] && $rawData['dob'] !== 'N/A') {
                try {
                    $safeStudent->dob = \Carbon\Carbon::parse($rawData['dob'])->format('d-m-Y');
                } catch (\Exception $e) {
                    $safeStudent->dob = 'N/A';
                }
            } else {
                $safeStudent->dob = 'N/A';
            }
            
            $safeStudent->father_contact = $rawData['father_contact'] ?? 'N/A';
            $safeStudent->father_whatsapp = $rawData['father_whatsapp'] ?? 'N/A';
            $safeStudent->mother_contact = $rawData['mother_contact'] ?? 'N/A';
            $safeStudent->category = $rawData['category'] ?? 'N/A';
            $safeStudent->gender = $rawData['gender'] ?? 'N/A';
            $safeStudent->father_occupation = $rawData['father_occupation'] ?? 'N/A';
            $safeStudent->mother_occupation = $rawData['mother_occupation'] ?? 'N/A';
            
            // Address
            $safeStudent->state = $rawData['state'] ?? 'N/A';
            $safeStudent->city = $rawData['city'] ?? 'N/A';
            $safeStudent->pincode = $rawData['pincode'] ?? 'N/A';
            $safeStudent->address = $rawData['address'] ?? 'N/A';
            
            // Additional Information
            $safeStudent->belongs_other_city = $rawData['belongs_other_city'] ?? 'No';
            $safeStudent->economic_weaker_section = $rawData['economic_weaker_section'] ?? 'No';
            $safeStudent->army_police_background = $rawData['army_police_background'] ?? 'No';
            $safeStudent->specially_abled = $rawData['specially_abled'] ?? 'No';
            
            // Course Details
            $safeStudent->course_type = $rawData['course_type'] ?? 'N/A';
            $safeStudent->course_name = $rawData['course_name'] ?? ($student->course->name ?? 'N/A');
            $safeStudent->delivery = $rawData['delivery'] ?? $rawData['delivery_mode'] ?? 'N/A';
            $safeStudent->medium = $rawData['medium'] ?? 'N/A';
            $safeStudent->board = $rawData['board'] ?? 'N/A';
            $safeStudent->course_content = $rawData['course_content'] ?? 'N/A';
            
            // Academic Details
            $safeStudent->previous_class = $rawData['previous_class'] ?? 'N/A';
            $safeStudent->academic_medium = $rawData['academic_medium'] ?? 'N/A';
            $safeStudent->school_name = $rawData['school_name'] ?? 'N/A';
            $safeStudent->academic_board = $rawData['academic_board'] ?? 'N/A';
            $safeStudent->passing_year = $rawData['passing_year'] ?? 'N/A';
            $safeStudent->percentage = $rawData['percentage'] ?? 'N/A';
            
            // Scholarship Eligibility
            $safeStudent->scholarship_test = $rawData['scholarship_test'] ?? 'No';
            $safeStudent->board_percentage = $rawData['board_percentage'] ?? 'N/A';
            $safeStudent->competition_exam = $rawData['competition_exam'] ?? 'No';
            
            // Batch Allocation
            $safeStudent->batch_name = $rawData['batch_name'] ?? ($student->batch->name ?? 'N/A');
            $safeStudent->batch = $student->batch;
            $safeStudent->course = $student->course;
            $safeStudent->shift = $student->shift;
            
            // Status
            $safeStudent->status = $rawData['status'] ?? 'active';
            $safeStudent->created_at = $student->created_at;
            $safeStudent->updated_at = $student->updated_at;
            
            // Process fees data
            $rawFees = $rawData['fees'] ?? [];
            $rawOtherFees = $rawData['other_fees'] ?? [];
            $rawTransactions = $rawData['transactions'] ?? [];
            
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
            
            $this->processFeesDataSafe($safeStudent);
            
            // Calculate fees
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
            
            $feeSummary = [
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
            
            $scholarshipEligible = [
                'eligible' => in_array(strtolower($rawData['eligible_for_scholarship'] ?? 'no'), ['yes', 'true', '1']),
                'reason' => $rawData['scholarship_name'] ?? 'N/A',
                'discountPercent' => floatval($rawData['discount_percentage'] ?? 0)
            ];
            
            $scholarshipData = [
                'eligible' => $rawData['eligible_for_scholarship'] ?? 'No',
                'scholarship_name' => $rawData['scholarship_name'] ?? 'N/A',
                'total_before_discount' => floatval($rawData['total_fee_before_discount'] ?? $totalFeesBeforeTax),
                'discount_percentage' => floatval($rawData['discount_percentage'] ?? 0),
                'has_discretionary' => ($rawData['discretionary_discount'] ?? 'No') === 'Yes',
                'discretionary_type' => $rawData['discretionary_discount_type'] ?? null,
                'discretionary_value' => floatval($rawData['discretionary_discount_value'] ?? 0),
                'discretionary_reason' => $rawData['discretionary_discount_reason'] ?? null,
            ];
            
            // ✅ GET REAL ACTIVITY HISTORY FROM DATABASE
            $activities = $this->getStudentActivities($student);
            $safeStudent->activities = $activities;
        
            Log::info('Student View Data:', [
                'student_id' => $id,
                'activities_count' => count($activities),
                'fees_summary' => $feeSummary
            ]);
            
            return view('student.smstudents.view', compact('safeStudent', 'feeSummary', 'scholarshipEligible', 'scholarshipData'))
                ->with('student', $safeStudent);
        
        } catch (\Exception $e) {
            Log::error('Error showing student: ' . $e->getMessage());
            return back()->with('error', 'Error loading student: ' . $e->getMessage());
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
     * Process fees data safely
     */
    private function processFeesDataSafe($student)
    {
        if (!isset($student->fees)) {
            $student->fees = collect([]);
        } elseif (is_string($student->fees)) {
            try {
                $decodedFees = json_decode($student->fees, true);
                $student->fees = is_array($decodedFees) ? collect($decodedFees) : collect([]);
            } catch (\Exception $e) {
                $student->fees = collect([]);
            }
        } elseif (is_array($student->fees)) {
            $student->fees = collect($student->fees);
        }
        
        if ($student->fees->isNotEmpty()) {
            $student->fees = $student->fees->map(function ($fee) {
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
                
                if (isset($fee['due_date']) && $fee['due_date'] && $fee['due_date'] !== 'N/A') {
                    try {
                        $fee['due_date'] = \Carbon\Carbon::parse($fee['due_date']);
                    } catch (\Exception $e) {
                        $fee['due_date'] = 'N/A';
                    }
                } else {
                    $fee['due_date'] = 'N/A';
                }
                
                if (isset($fee['paid_date']) && $fee['paid_date'] && $fee['paid_date'] !== 'N/A') {
                    try {
                        $fee['paid_date'] = \Carbon\Carbon::parse($fee['paid_date']);
                    } catch (\Exception $e) {
                        $fee['paid_date'] = 'N/A';
                    }
                } else {
                    $fee['paid_date'] = 'N/A';
                }
                
                $actualAmount = floatval($fee['actual_amount'] ?? 0);
                $discountAmount = floatval($fee['discount_amount'] ?? 0);
                $paidAmount = floatval($fee['paid_amount'] ?? 0);
                $fee['remaining_amount'] = $actualAmount - $discountAmount - $paidAmount;
                
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

        if (!isset($student->other_fees)) {
            $student->other_fees = collect([]);
        } elseif (is_string($student->other_fees)) {
            try {
                $decodedOtherFees = json_decode($student->other_fees, true);
                $student->other_fees = is_array($decodedOtherFees) ? collect($decodedOtherFees) : collect([]);
            } catch (\Exception $e) {
                $student->other_fees = collect([]);
            }
        } elseif (is_array($student->other_fees)) {
            $student->other_fees = collect($student->other_fees);
        }

        if ($student->other_fees->isNotEmpty()) {
            $student->other_fees = $student->other_fees->map(function ($fee) {
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
                
                if (isset($fee['due_date']) && $fee['due_date'] && $fee['due_date'] !== 'N/A') {
                    try {
                        $fee['due_date'] = \Carbon\Carbon::parse($fee['due_date']);
                    } catch (\Exception $e) {
                        $fee['due_date'] = 'N/A';
                    }
                } else {
                    $fee['due_date'] = 'N/A';
                }
                
                if (isset($fee['paid_date']) && $fee['paid_date'] && $fee['paid_date'] !== 'N/A') {
                    try {
                        $fee['paid_date'] = \Carbon\Carbon::parse($fee['paid_date']);
                    } catch (\Exception $e) {
                        $fee['paid_date'] = 'N/A';
                    }
                } else {
                    $fee['paid_date'] = 'N/A';
                }
                
                $actualAmount = floatval($fee['actual_amount'] ?? 0);
                $paidAmount = floatval($fee['paid_amount'] ?? 0);
                $fee['remaining_amount'] = $actualAmount - $paidAmount;
                
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

        if (!isset($student->transactions)) {
            $student->transactions = collect([]);
        } elseif (is_string($student->transactions)) {
            try {
                $decodedTransactions = json_decode($student->transactions, true);
                $student->transactions = is_array($decodedTransactions) ? collect($decodedTransactions) : collect([]);
            } catch (\Exception $e) {
                $student->transactions = collect([]);
            }
        } elseif (is_array($student->transactions)) {
            $student->transactions = collect($student->transactions);
        }

        if ($student->transactions->isNotEmpty()) {
            $student->transactions = $student->transactions->map(function ($txn) {
                if (is_string($txn)) {
                    try {
                        $txn = json_decode($txn, true);
                    } catch (\Exception $e) {
                        return null;
                    }
                }
                
                if (!is_array($txn)) {
                    return null;
                }
                
                if (isset($txn['payment_date']) && $txn['payment_date'] && $txn['payment_date'] !== 'N/A') {
                    try {
                        $txn['payment_date'] = \Carbon\Carbon::parse($txn['payment_date']);
                    } catch (\Exception $e) {
                        $txn['payment_date'] = 'N/A';
                    }
                }
                return $txn;
            })->filter()->sortByDesc('payment_date');
        }
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
}