<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student\SMstudents;
use App\Models\Master\Batch;
use App\Models\Master\Courses;
use App\Models\Master\Scholarship;
use App\Models\Student\Shift; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SmStudentsController extends Controller
{
    /**
     * Display a listing of students
     */
    public function index()
    {
        try {
            $students = SMstudents::with(['batch', 'course'])->get();
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
            $student = SMstudents::with(['batch', 'course', 'shift'])->findOrFail($id);
 
            // ✅ Process fees data and ensure proper formatting
            $this->processFeesData($student);
 
            // ✅ Calculate comprehensive fee summary
            $feeSummary = $this->calculateFeeSummary($student);
 
            // ✅ Check scholarship eligibility
            $scholarshipEligible = $this->checkScholarshipEligibility($student);
 
            // ✅ Add debug logging
            Log::info('Student View Data', [
                'student_id' => $id,
                'fees_count' => $student->fees->count(),
                'other_fees_count' => $student->other_fees->count(),
                'transactions_count' => $student->transactions->count(),
                'fee_summary' => $feeSummary,
                'scholarship' => $scholarshipEligible
            ]);
 
            if (request()->wantsJson()) {
                return response()->json([
                    'student' => $student,
                    'feeSummary' => $feeSummary,
                    'scholarshipEligible' => $scholarshipEligible
                ]);
            }
 
            return view('student.smstudents.view', compact('student', 'feeSummary', 'scholarshipEligible'));
        } catch (\Exception $e) {
            Log::error('Error showing student: ' . $e->getMessage());
            return back()->with('error', 'Student not found: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing student
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
            }

            $student->update($updateData);

            return redirect()->route('smstudents.index')->with('success', 'Student updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating student: ' . $e->getMessage());
            return back()->with('error', 'Failed to update student: ' . $e->getMessage())->withInput();
        }
    }

    /**
 * Update student password with old password verification
 */
public function updatePassword(Request $request, $id)
{
    // Validation rules
    $validator = Validator::make($request->all(), [
        'old_password' => 'required|string',
        'password' => 'required|string|min:6|confirmed',
        'password_confirmation' => 'required|string|min:6'
    ], [
        'old_password.required' => 'Current password is required',
        'password.required' => 'New password is required',
        'password.min' => 'New password must be at least 6 characters',
        'password.confirmed' => 'New password and confirmation do not match',
        'password_confirmation.required' => 'Please confirm your new password'
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->with('error', 'Password validation failed');
    }

    try {
        // Find the student
        $student = SMstudents::findOrFail($id);
        
        // Verify old password
        if (!Hash::check($request->old_password, $student->password)) {
            return redirect()->back()
                ->with('error', 'Current password is incorrect. Please try again.')
                ->withInput();
        }
        
        // Check if new password is same as old password
        if (Hash::check($request->password, $student->password)) {
            return redirect()->back()
                ->with('error', 'New password cannot be the same as current password')
                ->withInput();
        }
        
        // Update the password
        $student->update([
            'password' => Hash::make($request->password)
        ]);
        
        // Log the password change
        Log::info('Password updated for student:', [
            'student_id' => (string)$student->_id,
            'student_name' => $student->student_name,
            'roll_no' => $student->roll_no,
            'updated_by' => auth()->user()->email ?? 'Admin',
            'timestamp' => now()->toDateTimeString()
        ]);
        
        // Optional: Create activity log
        $this->createActivityLog($student, 'Password Updated', 
            'Password was changed by ' . (auth()->user()->email ?? 'Admin')
        );

        return redirect()->route('smstudents.index')
            ->with('success', 'Password updated successfully for ' . ($student->student_name ?? 'student'));
            
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        Log::error('Student not found for password update:', [
            'student_id' => $id,
            'error' => $e->getMessage()
        ]);
        
        return redirect()->back()
            ->with('error', 'Student not found');
            
    } catch (\Exception $e) {
        Log::error('Error updating password:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'student_id' => $id
        ]);
        
        return redirect()->back()
            ->with('error', 'Failed to update password. Please try again.');
    }
}
    // /**
    //  * Update student batch
    //  */
    // public function updateBatch(Request $request, $id)
    // {
    //     try {
    //         $request->validate(['batch_id' => 'required']);

    //         $student = SMstudents::findOrFail($id);
    //         $batch = Batch::find($request->batch_id);
            
    //         if (!$batch) {
    //             return response()->json([
    //                 'success' => false, 
    //                 'message' => 'Batch not found'
    //             ], 404);
    //         }

    //         $student->batch_id = $request->batch_id;
    //         $student->batch_name = $batch->name;
    //         $student->save();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Batch updated successfully'
    //         ]);
    //     } catch (\Exception $e) {
    //         Log::error('Batch update failed: ' . $e->getMessage());
    //         return response()->json([
    //             'success' => false, 
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }


/**
 * Helper method to create activity log
 */
private function createActivityLog($student, $title, $description)
{
    try {
        $activities = $student->activities ?? [];
        
        $newActivity = [
            'title' => $title,
            'description' => $description,
            'performed_by' => auth()->user()->name ?? auth()->user()->email ?? 'Admin',
            'created_at' => now(),
            'timestamp' => now()->toDateTimeString()
        ];
        
        array_unshift($activities, $newActivity);
        
        // Keep only last 50 activities
        $activities = array_slice($activities, 0, 50);
        
        $student->update(['activities' => $activities]);
        
    } catch (\Exception $e) {
        Log::warning('Failed to create activity log:', [
            'error' => $e->getMessage(),
            'student_id' => $student->_id
        ]);
    }
}

    /**
     * Update student shift
     */
    public function updateShift(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'shift_id' => 'required|exists:shifts,_id'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->with('error', 'Shift validation failed');
        }
        
        try {
            $student = SMstudents::findOrFail($id);
            $shift = Shift::find($request->shift_id);
            
            $student->update([
                'shift_id' => $request->shift_id,
                'shift' => $shift ? $shift->name : null,
            ]);
            
            Log::info('Shift updated for student:', [
                'student_id' => (string)$student->_id,
                'student_name' => $student->student_name,
                'new_shift_id' => (string)$request->shift_id,
                'new_shift_name' => $shift ? $shift->name : null
            ]);
            
            return redirect()->back()->with('success', 'Shift updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating shift: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update shift: ' . $e->getMessage());
        }
    }

    /**
     * Deactivate student
     */
    public function deactivate($id)
    {
        try {
            $student = SMstudents::findOrFail($id);
            $student->update(['status' => 'inactive']);

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
     * Get student history/activity log
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
 * Update student batch with comprehensive data synchronization
 */
public function updateBatch(Request $request, $id)
{
    // Validate input
    $validator = Validator::make($request->all(), [
        'batch_id' => 'required|exists:batches,_id'
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->with('error', 'Please select a valid batch');
    }

    try {
        // Find student
        $student = SMstudents::findOrFail($id);
        
        // Store old values for logging
        $oldBatchId = $student->batch_id;
        $oldBatchName = $student->batch_name ?? 'N/A';
        
        // Find new batch
        $newBatch = Batch::findOrFail($request->batch_id);
        
        // Find course by name from batch
        $course = null;
        if ($newBatch->course) {
            $course = Courses::where('name', $newBatch->course)->first();
        }
        
        // Prepare comprehensive update data
        $updateData = [
            'batch_id' => $request->batch_id,
            'batch_name' => $newBatch->batch_id, // Use batch_id as batch_name
            'course_name' => $newBatch->course,
            'delivery_mode' => $newBatch->mode,
        ];
        
        // Add course_id if course exists
        if ($course) {
            $updateData['course_id'] = $course->_id ?? $course->id;
        }
        
        // Update shift if batch has a specific shift
        if (!empty($newBatch->shift)) {
            $updateData['shift'] = $newBatch->shift;
        }
        
        // Update the student record
        $student->update($updateData);
        
        // Log the successful update
        Log::info('Batch updated successfully for student:', [
            'student_id' => (string)$student->_id,
            'student_name' => $student->student_name,
            'old_batch' => $oldBatchName,
            'new_batch' => $newBatch->batch_id,
            'new_course' => $newBatch->course,
            'timestamp' => now()->toDateTimeString()
        ]);
        
        return redirect()->route('smstudents.index')
            ->with('success', 'Batch updated successfully! Student assigned to ' . $newBatch->batch_id);
            
    } catch (\Exception $e) {
        Log::error('Error updating batch:', [
            'error' => $e->getMessage(),
            'student_id' => $id,
            'batch_id' => $request->batch_id
        ]);
        
        return redirect()->back()
            ->with('error', 'Failed to update batch: ' . $e->getMessage());
    }
}
    
    /* =======================================================
       ✅ ENHANCED PRIVATE HELPER METHODS
    ======================================================= */

    /**
     * ✅ ENHANCED: Process and format fees data with proper date handling
     */
    private function processFeesData($student)
    {
        // Process regular fees
        if ($student->fees && is_array($student->fees)) {
            $student->fees = collect($student->fees)->map(function ($fee) {
                // Parse dates
                if (isset($fee['due_date'])) {
                    $fee['due_date'] = Carbon::parse($fee['due_date']);
                }
                if (isset($fee['paid_date']) && $fee['paid_date']) {
                    $fee['paid_date'] = Carbon::parse($fee['paid_date']);
                }
                
                // Calculate remaining amount
                $actualAmount = floatval($fee['actual_amount'] ?? 0);
                $discountAmount = floatval($fee['discount_amount'] ?? 0);
                $paidAmount = floatval($fee['paid_amount'] ?? 0);
                $fee['remaining_amount'] = $actualAmount - $discountAmount - $paidAmount;
                
                // Determine status if not set
                if (!isset($fee['status'])) {
                    if ($paidAmount >= ($actualAmount - $discountAmount)) {
                        $fee['status'] = 'paid';
                    } elseif ($paidAmount > 0) {
                        $fee['status'] = 'partial';
                    } elseif (isset($fee['due_date']) && Carbon::parse($fee['due_date'])->isPast()) {
                        $fee['status'] = 'overdue';
                    } else {
                        $fee['status'] = 'pending';
                    }
                }
                
                // Add status badge
                $fee['status_badge'] = $this->getStatusBadge($fee['status']);
                
                return $fee;
            });
        } else {
            $student->fees = collect([]);
        }

        // Process other fees
        if ($student->other_fees && is_array($student->other_fees)) {
            $student->other_fees = collect($student->other_fees)->map(function ($fee) {
                // Parse dates
                if (isset($fee['due_date'])) {
                    $fee['due_date'] = Carbon::parse($fee['due_date']);
                }
                if (isset($fee['paid_date']) && $fee['paid_date']) {
                    $fee['paid_date'] = Carbon::parse($fee['paid_date']);
                }
                
                // Calculate remaining amount
                $actualAmount = floatval($fee['actual_amount'] ?? 0);
                $paidAmount = floatval($fee['paid_amount'] ?? 0);
                $fee['remaining_amount'] = $actualAmount - $paidAmount;
                
                // Determine status if not set
                if (!isset($fee['status'])) {
                    if ($paidAmount >= $actualAmount) {
                        $fee['status'] = 'paid';
                    } elseif ($paidAmount > 0) {
                        $fee['status'] = 'partial';
                    } elseif (isset($fee['due_date']) && Carbon::parse($fee['due_date'])->isPast()) {
                        $fee['status'] = 'overdue';
                    } else {
                        $fee['status'] = 'pending';
                    }
                }
                
                // Add status badge
                $fee['status_badge'] = $this->getStatusBadge($fee['status']);
                
                return $fee;
            });
        } else {
            $student->other_fees = collect([]);
        }

        // Process transactions
        if ($student->transactions && is_array($student->transactions)) {
            $student->transactions = collect($student->transactions)->map(function ($txn) {
                if (isset($txn['payment_date'])) {
                    $txn['payment_date'] = Carbon::parse($txn['payment_date']);
                }
                return $txn;
            })->sortByDesc('payment_date');
        } else {
            $student->transactions = collect([]);
        }
    }

    /**
     * ✅ ENHANCED: Calculate comprehensive fee summary
     */
    private function calculateFeeSummary($student)
    {
        // Regular Fees Summary
        $totalFees = $student->fees->sum(fn($f) => floatval($f['actual_amount'] ?? 0));
        $discountFees = $student->fees->sum(fn($f) => floatval($f['discount_amount'] ?? 0));
        $paidFees = $student->fees->sum(fn($f) => floatval($f['paid_amount'] ?? 0));
        $pendingFees = $totalFees - $discountFees - $paidFees;

        // Other Fees Summary
        $totalOtherFees = $student->other_fees->sum(fn($f) => floatval($f['actual_amount'] ?? 0));
        $paidOtherFees = $student->other_fees->sum(fn($f) => floatval($f['paid_amount'] ?? 0));
        $pendingOtherFees = $totalOtherFees - $paidOtherFees;

        // Grand Total
        $grandTotal = $totalFees + $totalOtherFees;
        $grandPaid = $paidFees + $paidOtherFees;
        $grandPending = $pendingFees + $pendingOtherFees;

        return [
            'fees' => [
                'total' => $totalFees,
                'discount' => $discountFees,
                'paid' => $paidFees,
                'pending' => $pendingFees
            ],
            'other_fees' => [
                'total' => $totalOtherFees,
                'paid' => $paidOtherFees,
                'pending' => $pendingOtherFees
            ],
            'grand' => [
                'total' => $grandTotal,
                'paid' => $grandPaid,
                'pending' => $grandPending
            ]
        ];
    }

    /**
     * ✅ ENHANCED: Check scholarship eligibility with Scholarship model integration
     */
    private function checkScholarshipEligibility($student)
    {
        $result = [
            'eligible' => false,
            'reason' => 'Not Eligible',
            'discountPercent' => 0
        ];

        // 1. Check if already has scholarship assigned
        if (in_array(strtolower($student->eligible_for_scholarship ?? ''), ['yes', 'true', '1'])) {
            $result['eligible'] = true;
            $result['reason'] = $student->scholarship_name ?? 'Scholarship Applied';
            $result['discountPercent'] = floatval($student->discount_percentage ?? 0);
            return $result;
        }

        // 2. Get student's course and category
        $courseName = $student->course_name ?? $student->course->name ?? null;
        $category = $student->category ?? 'General';

        // 3. Check Scholarship Test (Competition Exam)
        if (in_array(strtolower($student->scholarship_test ?? ''), ['yes'])) {
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

        // 4. Check Board Exam Percentage
        if (!empty($student->board_percentage)) {
            $boardPercent = floatval($student->board_percentage);
            
            $scholarship = Scholarship::getByPercentage($boardPercent, $courseName, $category);
            
            if ($scholarship) {
                return [
                    'eligible' => true,
                    'reason' => $scholarship->scholarship_name ?? 'Board Exam Merit',
                    'discountPercent' => floatval($scholarship->discount_percentage)
                ];
            }
        }

        // 5. Check Special Categories
        $specialCategories = [
            'economic_weaker_section' => Scholarship::APPLICABLE_EWS,
            'army_police_background' => Scholarship::APPLICABLE_DEFENCE,
            'specially_abled' => Scholarship::APPLICABLE_PWD
        ];

        foreach ($specialCategories as $field => $applicableFor) {
            if (in_array(strtolower($student->$field ?? ''), ['yes'])) {
                $scholarships = Scholarship::getApplicableScholarships($category, $applicableFor, $courseName);
                
                if ($scholarships->isNotEmpty()) {
                    $scholarship = $scholarships->first();
                    return [
                        'eligible' => true,
                        'reason' => $scholarship->scholarship_name,
                        'discountPercent' => floatval($scholarship->discount_percentage)
                    ];
                }
            }
        }

        return $result;
    }

    /**
     * ✅ Get Bootstrap badge class based on status
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
     * Add a new fee entry
     */
    public function addFee(Request $request, $id)
    {
        $student = SMstudents::findOrFail($id);
        $fees = $student->fees ?? [];
        
        $fees[] = [
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
        
        $student->fees = $fees;
        $student->save();

        return redirect()->back()->with('success', 'Fee added successfully');
    }

    /**
     * Add a new transaction
     */
    public function addTransaction(Request $request, $id)
    {
        $student = SMstudents::findOrFail($id);
        $transactions = $student->transactions ?? [];
        
        $transactions[] = [
            'transaction_id' => $request->transaction_id ?? 'TXN' . time(),
            'fee_type' => $request->fee_type,
            'amount' => floatval($request->amount),
            'payment_method' => $request->payment_method,
            'payment_date' => now()->toDateTimeString(),
            'received_by' => auth()->user()->name ?? 'Admin',
            'remarks' => $request->remarks ?? null
        ];
        
        $student->transactions = $transactions;
        $student->save();

        return redirect()->back()->with('success', 'Transaction recorded successfully');
    }
}
