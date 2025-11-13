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
        
        // Manually set safe values for all fields
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
        
        // Handle DOB carefully
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
        
        // Process fees data
        $rawFees = $rawData['fees'] ?? [];
        $rawOtherFees = $rawData['other_fees'] ?? [];
        $rawTransactions = $rawData['transactions'] ?? [];
        
        // Decode if they're JSON strings
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
        
        // Process fees data properly
        $this->processFeesDataSafe($safeStudent);
        
        // Calculate fee summary from paymentHistory if no fees structure exists
        $paymentHistory = $rawData['paymentHistory'] ?? [];
        if (is_string($paymentHistory)) {
            $paymentHistory = json_decode($paymentHistory, true) ?? [];
        }
        
        // Calculate totals from payment history
        $totalPaidFromHistory = 0;
        if (is_array($paymentHistory)) {
            foreach ($paymentHistory as $payment) {
                $totalPaidFromHistory += floatval($payment['amount'] ?? 0);
            }
        }
        
        // Get stored fee values
        $totalFeesInclusive = floatval($rawData['total_fees_inclusive_tax'] ?? 0);
        $totalFeesBeforeTax = floatval($rawData['total_fees'] ?? 0);
        $gstAmount = floatval($rawData['gst_amount'] ?? 0);
        
        // Calculate GST if not stored
        if ($gstAmount == 0 && $totalFeesBeforeTax > 0) {
            $gstAmount = $totalFeesBeforeTax * 0.18;
        }
        
        // Calculate total with GST if not stored
        if ($totalFeesInclusive == 0 && $totalFeesBeforeTax > 0) {
            $totalFeesInclusive = $totalFeesBeforeTax + $gstAmount;
        }
        
        // Use paid_fees from database, fallback to calculated value
        $totalPaid = floatval($rawData['paid_fees'] ?? $rawData['paidAmount'] ?? $totalPaidFromHistory);
        $remainingBalance = max(0, $totalFeesInclusive - $totalPaid);
        
        // Calculate fee summary
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
        
        // Check scholarship eligibility from stored data
        $scholarshipEligible = [
            'eligible' => in_array(strtolower($rawData['eligible_for_scholarship'] ?? 'no'), ['yes', 'true', '1']),
            'reason' => $rawData['scholarship_name'] ?? 'N/A',
            'discountPercent' => floatval($rawData['discount_percentage'] ?? 0)
        ];
        
        // **✅ FIXED: Properly build scholarship data from stored fields**
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
    
        Log::info('Student View Data with Scholarship:', [
            'student_id' => $id,
            'scholarship_data' => $scholarshipData,
            'fees_summary' => $feeSummary,
            'payment_history_count' => is_array($paymentHistory) ? count($paymentHistory) : 0
        ]);
        
        return view('student.smstudents.view', compact('safeStudent', 'feeSummary', 'scholarshipEligible', 'scholarshipData'))
            ->with('student', $safeStudent);
    
    } catch (\Exception $e) {
        Log::error('Error showing student: ' . $e->getMessage());
        return back()->with('error', 'Error loading student: ' . $e->getMessage());
    }
}

/**
 * Process fees data safely without date casting issues
 */
private function processFeesDataSafe($student)
{
    // Ensure fees is a collection, not a string
    if (!isset($student->fees)) {
        $student->fees = collect([]);
    } elseif (is_string($student->fees)) {
        // If it's a JSON string, decode it
        try {
            $decodedFees = json_decode($student->fees, true);
            $student->fees = is_array($decodedFees) ? collect($decodedFees) : collect([]);
        } catch (\Exception $e) {
            $student->fees = collect([]);
        }
    } elseif (is_array($student->fees)) {
        $student->fees = collect($student->fees);
    }
    
    // Process regular fees
    if ($student->fees->isNotEmpty()) {
        $student->fees = $student->fees->map(function ($fee) {
            // Ensure $fee is an array
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
            
            // Parse dates safely
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
            
            // Calculate remaining amount
            $actualAmount = floatval($fee['actual_amount'] ?? 0);
            $discountAmount = floatval($fee['discount_amount'] ?? 0);
            $paidAmount = floatval($fee['paid_amount'] ?? 0);
            $fee['remaining_amount'] = $actualAmount - $discountAmount - $paidAmount;
            
            // Determine status
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
        })->filter(); // Remove null entries
    }

    // Ensure other_fees is a collection
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

    // Process other fees
    if ($student->other_fees->isNotEmpty()) {
        $student->other_fees = $student->other_fees->map(function ($fee) {
            // Ensure $fee is an array
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
        })->filter(); // Remove null entries
    }

    // Ensure transactions is a collection
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

    // Process transactions
    if ($student->transactions->isNotEmpty()) {
        $student->transactions = $student->transactions->map(function ($txn) {
            // Ensure $txn is an array
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
        })->filter()->sortByDesc('payment_date'); // Remove null entries and sort
    }
}

/**
 * Check scholarship eligibility safely
 */
private function checkScholarshipEligibilitySafe($rawData, $safeStudent)
{
    $result = [
        'eligible' => false,
        'reason' => 'Not Eligible',
        'discountPercent' => 0
    ];

    // Check if already has scholarship
    if (in_array(strtolower($rawData['eligible_for_scholarship'] ?? ''), ['yes', 'true', '1'])) {
        $result['eligible'] = true;
        $result['reason'] = $rawData['scholarship_name'] ?? 'Scholarship Applied';
        $result['discountPercent'] = floatval($rawData['discount_percentage'] ?? 0);
        return $result;
    }

    return $result;
}



// /**
//  * Process fees data safely without date casting issues
//  */
// private function processFeesDataSafe($student)
// {
//     // Process regular fees
//     if ($student->fees && $student->fees->isNotEmpty()) {
//         $student->fees = $student->fees->map(function ($fee) {
//             // Parse dates safely
//             if (isset($fee['due_date']) && $fee['due_date'] && $fee['due_date'] !== 'N/A') {
//                 try {
//                     $fee['due_date'] = \Carbon\Carbon::parse($fee['due_date']);
//                 } catch (\Exception $e) {
//                     $fee['due_date'] = 'N/A';
//                 }
//             } else {
//                 $fee['due_date'] = 'N/A';
//             }
            
//             if (isset($fee['paid_date']) && $fee['paid_date'] && $fee['paid_date'] !== 'N/A') {
//                 try {
//                     $fee['paid_date'] = \Carbon\Carbon::parse($fee['paid_date']);
//                 } catch (\Exception $e) {
//                     $fee['paid_date'] = 'N/A';
//                 }
//             } else {
//                 $fee['paid_date'] = 'N/A';
//             }
            
//             // Calculate remaining amount
//             $actualAmount = floatval($fee['actual_amount'] ?? 0);
//             $discountAmount = floatval($fee['discount_amount'] ?? 0);
//             $paidAmount = floatval($fee['paid_amount'] ?? 0);
//             $fee['remaining_amount'] = $actualAmount - $discountAmount - $paidAmount;
            
//             // Determine status
//             if (!isset($fee['status'])) {
//                 if ($paidAmount >= ($actualAmount - $discountAmount)) {
//                     $fee['status'] = 'paid';
//                 } elseif ($paidAmount > 0) {
//                     $fee['status'] = 'partial';
//                 } else {
//                     $fee['status'] = 'pending';
//                 }
//             }
            
//             $fee['status_badge'] = $this->getStatusBadge($fee['status']);
            
//             return $fee;
//         });
//     }

//     // Process other fees
//     if ($student->other_fees && $student->other_fees->isNotEmpty()) {
//         $student->other_fees = $student->other_fees->map(function ($fee) {
//             if (isset($fee['due_date']) && $fee['due_date'] && $fee['due_date'] !== 'N/A') {
//                 try {
//                     $fee['due_date'] = \Carbon\Carbon::parse($fee['due_date']);
//                 } catch (\Exception $e) {
//                     $fee['due_date'] = 'N/A';
//                 }
//             } else {
//                 $fee['due_date'] = 'N/A';
//             }
            
//             if (isset($fee['paid_date']) && $fee['paid_date'] && $fee['paid_date'] !== 'N/A') {
//                 try {
//                     $fee['paid_date'] = \Carbon\Carbon::parse($fee['paid_date']);
//                 } catch (\Exception $e) {
//                     $fee['paid_date'] = 'N/A';
//                 }
//             } else {
//                 $fee['paid_date'] = 'N/A';
//             }
            
//             $actualAmount = floatval($fee['actual_amount'] ?? 0);
//             $paidAmount = floatval($fee['paid_amount'] ?? 0);
//             $fee['remaining_amount'] = $actualAmount - $paidAmount;
            
//             if (!isset($fee['status'])) {
//                 if ($paidAmount >= $actualAmount) {
//                     $fee['status'] = 'paid';
//                 } elseif ($paidAmount > 0) {
//                     $fee['status'] = 'partial';
//                 } else {
//                     $fee['status'] = 'pending';
//                 }
//             }
            
//             $fee['status_badge'] = $this->getStatusBadge($fee['status']);
            
//             return $fee;
//         });
//     }

//     // Process transactions
//     if ($student->transactions && $student->transactions->isNotEmpty()) {
//         $student->transactions = $student->transactions->map(function ($txn) {
//             if (isset($txn['payment_date']) && $txn['payment_date'] && $txn['payment_date'] !== 'N/A') {
//                 try {
//                     $txn['payment_date'] = \Carbon\Carbon::parse($txn['payment_date']);
//                 } catch (\Exception $e) {
//                     $txn['payment_date'] = 'N/A';
//                 }
//             }
//             return $txn;
//         })->sortByDesc('payment_date');
//     }
// }

// /**
//  * Check scholarship eligibility safely
//  */
// private function checkScholarshipEligibilitySafe($rawData, $safeStudent)
// {
//     $result = [
//         'eligible' => false,
//         'reason' => 'Not Eligible',
//         'discountPercent' => 0
//     ];

//     // Check if already has scholarship
//     if (in_array(strtolower($rawData['eligible_for_scholarship'] ?? ''), ['yes', 'true', '1'])) {
//         $result['eligible'] = true;
//         $result['reason'] = $rawData['scholarship_name'] ?? 'Scholarship Applied';
//         $result['discountPercent'] = floatval($rawData['discount_percentage'] ?? 0);
//         return $result;
//     }

//     return $result;
// }

/**
 * Debug method to check what data exists in database
 */
public function debug($id)
{
    try {
        $student = SMstudents::findOrFail($id);
        
        // Get all attributes
        $allData = $student->getAttributes();
        
        return response()->json([
            'success' => true,
            'student_id' => $id,
            'all_fields' => $allData,
            'missing_fields' => [
                'father_name' => isset($allData['father_name']),
                'mother_name' => isset($allData['mother_name']),
                'dob' => isset($allData['dob']),
                'father_contact' => isset($allData['father_contact']),
                'category' => isset($allData['category']),
                'gender' => isset($allData['gender']),
            ]
        ], 200);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
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
 * Update student shift with relationship refresh
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
        
        // Convert to strings for comparison
        $currentShiftId = (string)($student->shift_id ?? '');
        $newShiftId = (string)($request->shift_id);
        
        // Check if same shift
        if ($currentShiftId === $newShiftId) {
            return redirect()->back()
                ->with('warning', 'Student is already in this shift.');
        }
        
        // Get old shift name
        $oldShiftName = $student->shift->name ?? 'N/A';
        
        // Find new shift
        $newShift = Shift::findOrFail($request->shift_id);
        
        // Update student
        $student->update([
            'shift_id' => $request->shift_id,
        ]);
        
        // Force a fresh database fetch - don't use cached relationships
        $student = SMstudents::with(['shift', 'batch', 'course'])->findOrFail($id);
        
        Log::info('Shift updated for student:', [
            'student_id' => (string)$student->_id,
            'student_name' => $student->student_name,
            'old_shift' => $oldShiftName,
            'new_shift_id' => $newShiftId,
            'new_shift_name' => $newShift->name ?? 'N/A'
        ]);
        
        return redirect()->route('smstudents.index')
            ->with('success', 'Shift updated successfully from "' . $oldShiftName . '" to "' . ($newShift->name ?? 'N/A') . '"');
            
    } catch (\Exception $e) {
        Log::error('Error updating shift:', [
            'error' => $e->getMessage(),
            'student_id' => $id,
            'shift_id' => $request->shift_id
        ]);
        
        return redirect()->back()
            ->with('error', 'Failed to update shift: ' . $e->getMessage());
    }
}

/**
 * Update student batch with relationship refresh
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
        
        // Convert to strings for comparison
        $currentBatchId = (string)($student->batch_id);
        $newBatchIdInput = (string)($request->batch_id);
        
        // Check if same batch
        if ($currentBatchId === $newBatchIdInput) {
            return redirect()->back()
                ->with('warning', 'Student is already in this batch.');
        }
        
        // Get old batch name
        $oldBatchName = $student->batch->batch_id ?? $student->batch_name ?? 'N/A';
        
        // Find new batch
        $newBatch = Batch::findOrFail($request->batch_id);
        
        // Find course by name
        $course = null;
        if ($newBatch->course) {
            $course = Courses::where('name', $newBatch->course)->first();
        }
        
        // Prepare update data
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
        
        // Update student
        $student->update($updateData);
        
        // CRITICAL: Refresh model and reload relationships
        $student->refresh();
        $student->unsetRelation('batch'); // Clear cached batch
        $student->unsetRelation('course'); // Clear cached course
        $student->load(['batch', 'course']); // Reload fresh data
        
        Log::info('Batch updated successfully:', [
            'student_id' => (string)$student->_id,
            'student_name' => $student->student_name,
            'old_batch' => $oldBatchName,
            'new_batch_id' => (string)$student->batch_id,
            'new_batch_name' => $student->batch_name,
            'new_course' => $newBatch->course,
            'timestamp' => now()->toDateTimeString()
        ]);
        
        return redirect()->route('smstudents.index')
            ->with('success', 'Batch updated from "' . $oldBatchName . '" to "' . ($newBatch->batch_id ?? $newBatch->name) . '"');
            
    } catch (\Exception $e) {
        Log::error('Error updating batch:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'student_id' => $id,
            'batch_id' => $request->batch_id
        ]);
        
        return redirect()->back()
            ->with('error', 'Failed to update batch: ' . $e->getMessage());
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
