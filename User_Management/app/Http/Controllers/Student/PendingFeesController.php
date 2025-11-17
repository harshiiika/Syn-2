<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student\PendingFee;
use App\Models\Student\SMstudents;
use Illuminate\Support\Facades\Log;

class PendingFeesController extends Controller
{
    /**
     * Display all students with pending fees
     */
    public function index()
    {
        try {
            $pendingFees = PendingFee::orderBy('created_at', 'desc')->get();

            Log::info('Fetching pending fees students:', [
                'count' => $pendingFees->count(),
            ]);

            return view('student.pendingfees.pending', [
                'pendingFees' => $pendingFees,
                'totalCount' => $pendingFees->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading pending fees students: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to load students');
        }
    }

    /**
     * Display student details (read-only view)
     */
    public function view(string $id)
    {
        try {
            $student = PendingFee::findOrFail($id);
            
            Log::info('=== VIEWING PENDING FEES STUDENT DETAILS ===', [
                'student_id' => $id,
                'student_name' => $student->name ?? 'N/A',
            ]);
            
            $feesData = [
                'eligible_for_scholarship' => $student->eligible_for_scholarship ?? 'No',
                'scholarship_name' => $student->scholarship_name ?? 'N/A',
                'total_fee_before_discount' => $student->total_fee_before_discount ?? 0,
                'discretionary_discount' => $student->discretionary_discount ?? 'No',
                'discount_percentage' => $student->discount_percentage ?? 0,
                'discounted_fee' => $student->discounted_fee ?? 0,
                'fees_breakup' => $student->fees_breakup ?? 'Class room course (with test series & study material)',
                'total_fees' => $student->total_fees ?? 0,
                'gst_amount' => $student->gst_amount ?? 0,
                'total_fees_inclusive_tax' => $student->total_fees_inclusive_tax ?? 0,
                'single_installment_amount' => $student->single_installment_amount ?? 0,
                'installment_1' => $student->installment_1 ?? 0,
                'installment_2' => $student->installment_2 ?? 0,
                'installment_3' => $student->installment_3 ?? 0,
            ];
            
            return view('student.pendingfees.view', compact('student', 'feesData'));
            
        } catch (\Exception $e) {
            Log::error("View failed for student ID {$id}: " . $e->getMessage());
            return redirect()->route('student.pendingfees.pending')
                ->with('error', 'Student not found');
        }
    }

    /**
     * Get student history
     */
    public function getHistory($id)
    {
        try {
            $student = PendingFee::find($id);
            
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found'
                ], 404);
            }
            
            $history = $student->history ?? [];
            $history = array_reverse($history);
            
            Log::info('History retrieved for student', [
                'student_id' => $id,
                'history_count' => count($history)
            ]);
            
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

    /**
     * Show edit form
     */
    public function edit($id) 
    {
        try {
            $student = PendingFee::findOrFail($id);
            
            Log::info('Editing pending student:', [
                'student_id' => $id,
                'student_name' => $student->name ?? 'N/A'
            ]);
            
            return view('student.pendingfees.edit', compact('student'));
            
        } catch (\Exception $e) {
            Log::error("Edit failed for student ID {$id}: " . $e->getMessage());
            return redirect()->route('student.pendingfees.pending')
                ->with('error', 'Student not found');
        }
    }

    /**
     * Update student information
     */
    public function update(Request $request, string $id)
    {
        try {
            Log::info('Update request received', ['id' => $id]);

            $student = PendingFee::findOrFail($id);
            $oldData = $student->toArray();
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'father' => 'nullable|string|max:255',
                'mother' => 'nullable|string|max:255',
                'dob' => 'nullable|date',
                'mobileNumber' => 'nullable|string|max:15',
                'fatherWhatsapp' => 'nullable|string|max:15',
                'motherContact' => 'nullable|string|max:15',
                'studentContact' => 'nullable|string|max:15',
                'category' => 'nullable|in:OBC,SC,GENERAL,ST',
                'gender' => 'nullable|in:Male,Female,Others',
                'courseName' => 'nullable|string',
                'deliveryMode' => 'nullable|string',
                'courseContent' => 'nullable|string',
                'batchName' => 'nullable|string',
            ]);

            // Track changes
            $changes = [];
            foreach ($validated as $key => $value) {
                $oldValue = $oldData[$key] ?? null;
                if ($oldValue != $value && $value !== null) {
                    $changes[$key] = [
                        'from' => $oldValue,
                        'to' => $value
                    ];
                }
            }

            // Add history entry
            $history = $student->history ?? [];
            $history[] = [
                'action' => 'Student Details Updated',
                'user' => auth()->check() ? auth()->user()->name : 'Admin',
                'description' => 'Student details updated for ' . $student->name,
                'timestamp' => now()->toIso8601String(),
                'changes' => $changes
            ];
            
            $validated['history'] = $history;
            $student->update($validated);

            return redirect()
                ->route('student.pendingfees.pending')
                ->with('success', 'Student details updated successfully!');

        } catch (\Exception $e) {
            Log::error('Update failed:', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'Failed to update student: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show payment form with ALL fee details including discounts
     */
    public function pay($id)
    {
        try {
            $student = PendingFee::findOrFail($id);

            Log::info('PAYMENT FORM - Loading student data:', [
                'student_id' => $id,
                'name' => $student->name,
            ]);

            // ✅ FIX: Calculate fees if missing
            $totalFees = floatval($student->total_fees ?? 0);
            $gstAmount = floatval($student->gst_amount ?? 0);
            $totalFeesWithGST = floatval($student->total_fees_inclusive_tax ?? 0);

            // If fees are missing, calculate them from course
            if ($totalFeesWithGST == 0 || $totalFees == 0) {
                Log::warning('Fees missing for student, calculating now', [
                    'student_id' => $id,
                    'course_name' => $student->courseName ?? $student->course_name ?? 'unknown'
                ]);
                
                // Course fees mapping
                $courseFees = [
                    'Anthesis 11th NEET' => 88000,
                    'Momentum 12th NEET' => 88000,
                    'Dynamic Target NEET' => 88000,
                    'Impulse 11th IIT' => 88000,
                    'Intensity 12th IIT' => 88000,
                    'Thurst Target IIT' => 88000,
                    'Seedling 10th' => 60000,
                    'Plumule 9th' => 55000,
                    'Radicle 8th' => 50000
                ];

                $courseName = $student->courseName ?? $student->course_name ?? '';
                $baseFee = $courseFees[$courseName] ?? 88000;

                // Apply scholarships if any
                $discount = floatval($student->discount_percentage ?? 0);
                $totalFees = $baseFee;
                
                if ($discount > 0) {
                    $totalFees = $baseFee - ($baseFee * $discount / 100);
                }

                // Apply discretionary discount if any
                if (($student->discretionary_discount ?? 'No') === 'Yes') {
                    $discType = $student->discretionary_discount_type ?? 'percentage';
                    $discValue = floatval($student->discretionary_discount_value ?? 0);
                    
                    if ($discType === 'percentage') {
                        $totalFees = $totalFees - ($totalFees * $discValue / 100);
                    } else {
                        $totalFees = $totalFees - $discValue;
                    }
                }

                $gstAmount = $totalFees * 0.18;
                $totalFeesWithGST = $totalFees + $gstAmount;

                // Update student record with calculated fees
                $student->update([
                    'total_fees' => $totalFees,
                    'gst_amount' => $gstAmount,
                    'total_fees_inclusive_tax' => $totalFeesWithGST,
                    'installment_1' => round($totalFeesWithGST * 0.40, 2),
                    'installment_2' => round($totalFeesWithGST * 0.30, 2),
                    'installment_3' => round($totalFeesWithGST * 0.30, 2),
                    'single_installment_amount' => $totalFeesWithGST,
                ]);

                Log::info('Fees calculated and saved:', [
                    'total_fees' => $totalFees,
                    'gst_amount' => $gstAmount,
                    'total_fees_with_gst' => $totalFeesWithGST,
                ]);
            } else {
                // Recalculate GST if missing
                if ($gstAmount == 0 && $totalFees > 0) {
                    $gstAmount = $totalFees * 0.18;
                }

                // Recalculate total with GST if missing
                if ($totalFeesWithGST == 0) {
                    $totalFeesWithGST = $totalFees + $gstAmount;
                }
            }

            // ✅ VALIDATION: Ensure fees are not zero
            if ($totalFeesWithGST == 0) {
                Log::error('Unable to determine fees for student', [
                    'student_id' => $id,
                    'course_name' => $student->courseName ?? $student->course_name ?? 'unknown'
                ]);
                
                return redirect()->route('student.pendingfees.pending')
                    ->with('error', 'Unable to determine fees for this student. Please update the course information or contact admin.');
            }

            // Calculate total paid from payment history
            $totalPaid = 0;
            if (isset($student->paymentHistory) && is_array($student->paymentHistory)) {
                foreach ($student->paymentHistory as $payment) {
                    $totalPaid += floatval($payment['amount'] ?? 0);
                }
            }

            if ($totalPaid == 0) {
                $totalPaid = floatval($student->paid_fees ?? 0);
            }

            $remainingBalance = max(0, $totalFeesWithGST - $totalPaid);

            // ✅ CALCULATE INSTALLMENTS - 40%, 30%, 30%
            $installment1 = round($totalFeesWithGST * 0.40, 2);
            $installment2 = round($totalFeesWithGST * 0.30, 2);
            $installment3 = round($totalFeesWithGST * 0.30, 2);

            // ✅ CALCULATE ADJUSTED INSTALLMENTS based on payments made
            $adjustedInstallments = $this->calculateAdjustedInstallments(
                $totalFeesWithGST,
                $totalPaid,
                $student->paymentHistory ?? []
            );

            // Prepare scholarship/discount data
            $scholarshipData = [
                'eligible' => $student->eligible_for_scholarship ?? 'No',
                'scholarship_name' => $student->scholarship_name ?? 'N/A',
                'total_before_discount' => floatval($student->total_fee_before_discount ?? $totalFees),
                'discount_percentage' => floatval($student->discount_percentage ?? 0),
                'has_discretionary' => ($student->discretionary_discount ?? 'No') === 'Yes',
                'discretionary_type' => $student->discretionary_discount_type ?? null,
                'discretionary_value' => floatval($student->discretionary_discount_value ?? 0),
                'discretionary_reason' => $student->discretionary_discount_reason ?? null,
            ];

            Log::info('Payment Form Loaded:', [
                'student_id' => $id,
                'total_fees_with_gst' => $totalFeesWithGST,
                'total_paid' => $totalPaid,
                'remaining_balance' => $remainingBalance,
                'installment_1' => $installment1,
                'installment_2' => $installment2,
                'installment_3' => $installment3,
                'adjusted_installments' => $adjustedInstallments,
            ]);

            return view('student.pendingfees.pay', compact(
                'student',
                'totalFees',
                'gstAmount',
                'totalFeesWithGST',
                'totalPaid',
                'remainingBalance',
                'installment1',
                'installment2',
                'installment3',
                'adjustedInstallments',
                'scholarshipData'
            ));
        } catch (\Exception $e) {
            Log::error('Payment form error:', [
                'id' => $id, 
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('student.pendingfees.pending')
                ->with('error', 'Unable to load payment form: ' . $e->getMessage());
        }
    }

    /**
     * ✅ Calculate adjusted installments based on payment history
     */
    private function calculateAdjustedInstallments($totalFees, $totalPaid, $paymentHistory)
    {
        // Original installments: 40%, 30%, 30%
        $original1 = round($totalFees * 0.40, 2);
        $original2 = round($totalFees * 0.30, 2);
        $original3 = round($totalFees * 0.30, 2);

        // Track what's been paid per installment
        $inst1Paid = 0;
        $inst2Paid = 0;
        $inst3Paid = 0;

        foreach ($paymentHistory as $payment) {
            $amount = floatval($payment['amount'] ?? 0);
            $instNum = $payment['installment_number'] ?? null;

            if ($instNum == 1) {
                $inst1Paid += $amount;
            } elseif ($instNum == 2) {
                $inst2Paid += $amount;
            } elseif ($instNum == 3) {
                $inst3Paid += $amount;
            }
        }

        // Calculate remaining for each installment
        $inst1Remaining = max(0, $original1 - $inst1Paid);
        $inst2Remaining = max(0, $original2 - $inst2Paid);
        $inst3Remaining = max(0, $original3 - $inst3Paid);

        // If installment 1 or 2 has excess payment, redistribute to next installments
        if ($inst1Remaining == 0 && $inst1Paid > $original1) {
            $excess = $inst1Paid - $original1;
            $inst2Remaining = max(0, $inst2Remaining - $excess);
        }

        if ($inst2Remaining == 0 && $inst2Paid > $original2) {
            $excess = $inst2Paid - $original2;
            $inst3Remaining = max(0, $inst3Remaining - $excess);
        }

        // Final installment always shows exact remaining balance
        $totalRemaining = $totalFees - $totalPaid;
        $inst3Remaining = max(0, $totalRemaining);

        return [
            'installment_1' => [
                'original' => $original1,
                'paid' => $inst1Paid,
                'remaining' => $inst1Remaining,
                'status' => $inst1Remaining == 0 ? 'paid' : ($inst1Paid > 0 ? 'partial' : 'pending')
            ],
            'installment_2' => [
                'original' => $original2,
                'paid' => $inst2Paid,
                'remaining' => $inst2Remaining,
                'status' => $inst2Remaining == 0 ? 'paid' : ($inst2Paid > 0 ? 'partial' : 'pending')
            ],
            'installment_3' => [
                'original' => $original3,
                'paid' => $inst3Paid,
                'remaining' => $inst3Remaining,
                'status' => $inst3Remaining == 0 ? 'paid' : ($inst3Paid > 0 ? 'partial' : 'pending')
            ],
        ];
    }

    /**
     * Process payment and transfer to SMstudents if fully paid
     */
    public function processPayment(Request $request, $id)
    {
        try {
            Log::info('=== PAYMENT PROCESSING STARTED ===', ['id' => $id]);

            $validated = $request->validate([
                'payment_date' => 'required|date',
                'payment_type' => 'required|in:cash,online,cheque,card',
                'payment_amount' => 'required|numeric|min:1',
                'payment_mode' => 'required|in:single,installment,custom',
                'installment_number' => 'nullable|in:1,2,3',
                'transaction_id' => 'nullable|string',
                'remarks' => 'nullable|string',
                'other_charges' => 'nullable|numeric|min:0',
            ]);

            $student = PendingFee::findOrFail($id);

            $paymentAmount = floatval($validated['payment_amount']);
            $otherCharges = floatval($validated['other_charges'] ?? 0);
            $paymentMode = $validated['payment_mode'];
            $installmentNumber = $validated['installment_number'] ?? null;

            // Create payment record
            $paymentRecord = [
                'date' => $validated['payment_date'],
                'amount' => $paymentAmount,
                'method' => $validated['payment_type'],
                'payment_mode' => $paymentMode,
                'installment_number' => $installmentNumber,
                'transaction_id' => $validated['transaction_id'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
                'other_charges' => $otherCharges,
                'recorded_at' => now()->toDateTimeString(),
                'recorded_by' => auth()->user()->name ?? 'Admin',
            ];

            // Get existing payment history
            $paymentHistory = $student->paymentHistory ?? [];
            if (!is_array($paymentHistory)) {
                $paymentHistory = [];
            }
            
            $paymentHistory[] = $paymentRecord;
            
            Log::info('Payment Record Created:', [
                'payment_mode' => $paymentMode,
                'installment' => $installmentNumber,
                'amount' => $paymentAmount,
            ]);

            $totalFeesWithGST = floatval($student->total_fees_inclusive_tax ?? 0);
            
            if ($totalFeesWithGST == 0) {
                $totalFees = floatval($student->total_fees ?? 0);
                $gstAmount = floatval($student->gst_amount ?? 0);
                
                if ($gstAmount == 0 && $totalFees > 0) {
                    $gstAmount = $totalFees * 0.18;
                }
                
                $totalFeesWithGST = $totalFees + $gstAmount;
            }

            // Calculate total paid
            $newPaidAmount = 0;
            foreach ($paymentHistory as $p) {
                $newPaidAmount += floatval($p['amount'] ?? 0);
            }
            
            $newRemainingBalance = $totalFeesWithGST - $newPaidAmount;

            // This prevents premature transfer due to rounding errors
            $isFullyPaid = false;
            if ($newRemainingBalance <= 5 && $newRemainingBalance >= -5) {
                $newRemainingBalance = 0;
                $isFullyPaid = true;
            } else {
                $newRemainingBalance = max(0, $newRemainingBalance);
                $isFullyPaid = false;
            }

            Log::info('Payment Calculation:', [
                'total_fees_with_gst' => $totalFeesWithGST,
                'new_paid_amount' => $newPaidAmount,
                'new_remaining_balance' => $newRemainingBalance,
                'is_fully_paid' => $isFullyPaid,
            ]);

           // ✅ CHECK IF FULLY PAID - Only transfer when truly paid
if ($isFullyPaid && $newRemainingBalance == 0) {
    Log::info('FEES FULLY PAID - Transferring to SMstudents');

    $totalFees = floatval($student->total_fees ?? ($totalFeesWithGST / 1.18));
    $gstAmount = floatval($student->gst_amount ?? ($totalFeesWithGST - $totalFees));

    // ✅ Create activity log for SMstudents
    $activities = [];
    
    // Add payment completion activity
    $activities[] = [
        'title' => 'Fees Payment Completed',
        'description' => 'paid full fees amount of ₹' . number_format($totalFeesWithGST, 2),
        'performed_by' => auth()->user()->name ?? 'Admin',
        'created_at' => now()->toIso8601String(),
    ];

    // Add transfer activity
    $activities[] = [
        'title' => 'Student Activated',
        'description' => 'transferred student from pending fees to active students',
        'performed_by' => auth()->user()->name ?? 'Admin',
        'created_at' => now()->toIso8601String(),
    ];

    // ✅ COMPLETE FIELD MAPPING - All fields properly mapped
    $smStudentData = [
        // Roll Number & Basic Identity
        'roll_no' => $student->roll_no ?? 'SM' . now()->format('ymd') . rand(100, 999),
        'student_name' => $student->name,
        'email' => $student->email ?? $student->studentContact ?? ($student->name . '@temp.com'),
        'phone' => $student->studentContact ?? $student->mobileNumber,
        
        // ✅ FIXED: Parent Details
        'father_name' => $student->father,
        'mother_name' => $student->mother,
        'dob' => $student->dob,
        
        // ✅ FIXED: Contact Numbers
        'father_contact' => $student->mobileNumber,
        'father_whatsapp' => $student->fatherWhatsapp,
        'mother_contact' => $student->motherContact,
        'student_contact' => $student->studentContact,
        
        // ✅ FIXED: Personal Details
        'category' => $student->category,  // This was missing!
        'gender' => $student->gender,
        'father_occupation' => $student->fatherOccupation,
        'father_grade' => $student->fatherGrade ?? null,
        'mother_occupation' => $student->motherOccupation,
        
        // ✅ FIXED: Address Details
        'state' => $student->state,
        'city' => $student->city,
        'pincode' => $student->pinCode,  // Note: pinCode -> pincode
        'address' => $student->address,
        'belongs_other_city' => $student->belongToOtherCity ?? 'No',
        'economic_weaker_section' => $student->economicWeakerSection ?? 'No',
        'army_police_background' => $student->armyPoliceBackground ?? 'No',
        'specially_abled' => $student->speciallyAbled ?? 'No',
        
        // ✅ FIXED: Academic Details
        'previous_class' => $student->previousClass,
        'academic_medium' => $student->previousMedium ?? $student->medium,
        'school_name' => $student->schoolName,
        'academic_board' => $student->previousBoard ?? $student->board,
        'passing_year' => $student->passingYear,
        'percentage' => $student->percentage,
        
        // ✅ FIXED: Scholarship Test Info
        'is_repeater' => $student->isRepeater ?? 'No',
        'scholarship_test' => $student->scholarshipTest ?? 'No',
        'last_board_percentage' => $student->lastBoardPercentage,
        'competition_exam' => $student->competitionExam ?? 'No',
        
        // ✅ FIXED: Course & Batch
        'batch_id' => $student->batch_id,
        'batch_name' => $student->batchName,
        'course_id' => $student->course_id,
        'course_name' => $student->courseName,
        'course_type' => $student->courseType ?? $student->course_type,
        'delivery_mode' => $student->deliveryMode ?? 'Offline',
        'course_content' => $student->courseContent ?? 'Class room course (with test series & study material)',
        'medium' => $student->medium,
        'board' => $student->board,
        
        // ✅ FIXED: Scholarship & Discount Details
        'eligible_for_scholarship' => $student->eligible_for_scholarship ?? 'No',
        'scholarship_name' => $student->scholarship_name ?? 'N/A',
        'total_fee_before_discount' => $student->total_fee_before_discount ?? $totalFees,
        'discretionary_discount' => $student->discretionary_discount ?? 'No',
        'discretionary_discount_type' => $student->discretionary_discount_type,
        'discretionary_discount_value' => $student->discretionary_discount_value ?? 0,
        'discretionary_discount_reason' => $student->discretionary_discount_reason,
        'discount_percentage' => $student->discount_percentage ?? 0,
        'discounted_fee' => $student->discounted_fee ?? $totalFees,
        
        // ✅ FIXED: Fees Details
        'fees_breakup' => $student->fees_breakup ?? 'Class room course (with test series & study material)',
        'total_fees' => $totalFees,
        'gst_amount' => $gstAmount,
        'total_fees_inclusive_tax' => $totalFeesWithGST,
        'single_installment_amount' => $student->single_installment_amount ?? $totalFeesWithGST,
        'installment_1' => $student->installment_1 ?? round($totalFeesWithGST * 0.40, 2),
        'installment_2' => $student->installment_2 ?? round($totalFeesWithGST * 0.30, 2),
        'installment_3' => $student->installment_3 ?? round($totalFeesWithGST * 0.30, 2),
        'paid_fees' => $newPaidAmount,
        'paidAmount' => $newPaidAmount,
        'remaining_fees' => 0,
        'remainingAmount' => 0,
        'fee_status' => 'paid',
        'paymentHistory' => $paymentHistory,
        'last_payment_date' => $validated['payment_date'],
        
        // ✅ FIXED: Documents - preserve from pending_fees
        'passport_photo' => $student->passport_photo,
        'marksheet' => $student->marksheet,
        'caste_certificate' => $student->caste_certificate ?? null,
        'scholarship_proof' => $student->scholarship_proof ?? null,
        'secondary_marksheet' => $student->secondary_marksheet ?? null,
        'senior_secondary_marksheet' => $student->senior_secondary_marksheet ?? null,
        
        // ✅ FIXED: Status & Metadata
        'status' => 'active',
        'admission_date' => now(),
        'transferred_from' => 'pending_fees',
        'transferred_at' => now(),
        'activities' => $activities,
        'session' => session('current_session', '2025-2026'),
        'branch' => $student->branch ?? 'Main Branch',
        'created_at' => $student->created_at ?? now(),
        'updated_at' => now(),
    ];

    Log::info('Creating SMstudents record with complete data', [
        'student_name' => $smStudentData['student_name'],
        'category' => $smStudentData['category'],
        'dob' => $smStudentData['dob'],
        'mother_name' => $smStudentData['mother_name'],
    ]);

    $smStudent = SMstudents::create($smStudentData);

    Log::info('✅ Student transferred successfully with ALL fields', [
        'sm_student_id' => $smStudent->_id,
        'activities_count' => count($activities),
    ]);

    $student->delete();

    return redirect()->route('smstudents.index')
        ->with('success', "Payment successful! Student '{$smStudent->student_name}' moved to Active Students. Total Paid: ₹" . number_format($newPaidAmount, 2));
}

            // ✅ PARTIAL PAYMENT - Update student record
            $feeStatus = $newPaidAmount > 0 ? 'partial' : 'pending';

            $student->paid_fees = $newPaidAmount;
            $student->paidAmount = $newPaidAmount;
            $student->remaining_fees = $newRemainingBalance;
            $student->remainingAmount = $newRemainingBalance;
            $student->fee_status = $feeStatus;
            $student->last_payment_date = $validated['payment_date'];
            $student->setAttribute('paymentHistory', $paymentHistory);
            $student->save();
            
            $student->refresh();

            Log::info('Partial payment recorded', [
                'paid_this_time' => $paymentAmount,
                'total_paid' => $newPaidAmount,
                'remaining' => $newRemainingBalance,
            ]);

            $message = "Payment of ₹" . number_format($paymentAmount, 2) . " recorded";
            if ($installmentNumber) {
                $message .= " (Installment #{$installmentNumber})";
            }
            $message .= "! Total Paid: ₹" . number_format($newPaidAmount, 2) . " | Remaining: ₹" . number_format($newRemainingBalance, 2);

            return redirect()->route('student.pendingfees.pending')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Payment processing failed', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Payment failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete a pending fees student
     */
    public function destroy($id)
    {
        try {
            $student = PendingFee::findOrFail($id);
            $studentName = $student->name;
            
            $student->delete();
            
            Log::info('Student deleted from pending fees', [
                'student_id' => $id,
                'name' => $studentName,
            ]);
            
            return redirect()->route('student.pendingfees.pending')
                ->with('success', "Student '{$studentName}' deleted successfully");
                
        } catch (\Exception $e) {
            Log::error('Delete failed:', ['id' => $id, 'error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Failed to delete student');
        }
    }
public function transferToSmStudents($id)
{
    try {
        $pendingFees = PendingFee::findOrFail($id);
        $rawData = $pendingFees->getAttributes();
        
        Log::info('Transferring Pending Fees to SM Students:', [
            'pending_fees_id' => $id,
            'name' => $rawData['name'] ?? 'N/A',
            'father' => $rawData['father'] ?? 'N/A',
            'has_documents' => isset($rawData['passport_photo'])
        ]);
        
        // Generate roll number
        $rollNo = $this->generateRollNumber();
        
        // ✅ CREATE SM STUDENTS DATA WITH BOTH FIELD NAME FORMATS
        $smStudentData = [
            // ✅ PRIMARY FIELDS (snake_case for view)
            'roll_no' => $rollNo,
            'student_name' => $rawData['name'] ?? 'N/A',
            'email' => $rawData['email'] ?? null,
            'phone' => $rawData['studentContact'] ?? $rawData['mobileNumber'] ?? null,
            
            // ✅ PERSONAL DETAILS (both formats)
            'father_name' => $rawData['father'] ?? 'N/A',
            'father' => $rawData['father'] ?? 'N/A', // Keep camelCase too
            
            'mother_name' => $rawData['mother'] ?? 'N/A',
            'mother' => $rawData['mother'] ?? 'N/A', // Keep camelCase too
            
            'dob' => $rawData['dob'] ?? null,
            
            'father_contact' => $rawData['mobileNumber'] ?? null,
            'mobileNumber' => $rawData['mobileNumber'] ?? null, // Keep camelCase too
            
            'father_whatsapp' => $rawData['fatherWhatsapp'] ?? null,
            'fatherWhatsapp' => $rawData['fatherWhatsapp'] ?? null, // Keep camelCase too
            
            'mother_contact' => $rawData['motherContact'] ?? null,
            'motherContact' => $rawData['motherContact'] ?? null, // Keep camelCase too
            
            'category' => $rawData['category'] ?? null,
            'gender' => $rawData['gender'] ?? null,
            
            'father_occupation' => $rawData['fatherOccupation'] ?? null,
            'fatherOccupation' => $rawData['fatherOccupation'] ?? null, // Keep camelCase too
            
            'mother_occupation' => $rawData['motherOccupation'] ?? null,
            'motherOccupation' => $rawData['motherOccupation'] ?? null, // Keep camelCase too
            
            // ✅ ADDRESS (both formats)
            'state' => $rawData['state'] ?? null,
            'city' => $rawData['city'] ?? null,
            
            'pincode' => $rawData['pinCode'] ?? null,
            'pinCode' => $rawData['pinCode'] ?? null, // Keep camelCase too
            
            'address' => $rawData['address'] ?? null,
            
            // ✅ ADDITIONAL INFO (both formats)
            'belongs_other_city' => $rawData['belongToOtherCity'] ?? 'No',
            'belongToOtherCity' => $rawData['belongToOtherCity'] ?? 'No',
            
            'economic_weaker_section' => $rawData['economicWeakerSection'] ?? 'No',
            'economicWeakerSection' => $rawData['economicWeakerSection'] ?? 'No',
            
            'army_police_background' => $rawData['armyPoliceBackground'] ?? 'No',
            'armyPoliceBackground' => $rawData['armyPoliceBackground'] ?? 'No',
            
            'specially_abled' => $rawData['speciallyAbled'] ?? 'No',
            'speciallyAbled' => $rawData['speciallyAbled'] ?? 'No',
            
            // ✅ COURSE DETAILS (both formats)
            'course_type' => $rawData['courseType'] ?? $rawData['course_type'] ?? null,
            'courseType' => $rawData['courseType'] ?? $rawData['course_type'] ?? null,
            
            'course_name' => $rawData['courseName'] ?? null,
            'courseName' => $rawData['courseName'] ?? null,
            
            'delivery_mode' => $rawData['deliveryMode'] ?? null,
            'deliveryMode' => $rawData['deliveryMode'] ?? null,
            
            'medium' => $rawData['medium'] ?? null,
            'board' => $rawData['board'] ?? null,
            
            'course_content' => $rawData['courseContent'] ?? null,
            'courseContent' => $rawData['courseContent'] ?? null,
            
            // ✅ ACADEMIC DETAILS (both formats)
            'previous_class' => $rawData['previousClass'] ?? null,
            'previousClass' => $rawData['previousClass'] ?? null,
            
            'academic_medium' => $rawData['previousMedium'] ?? null,
            'previousMedium' => $rawData['previousMedium'] ?? null,
            
            'school_name' => $rawData['schoolName'] ?? null,
            'schoolName' => $rawData['schoolName'] ?? null,
            
            'academic_board' => $rawData['previousBoard'] ?? null,
            'previousBoard' => $rawData['previousBoard'] ?? null,
            
            'passing_year' => $rawData['passingYear'] ?? null,
            'passingYear' => $rawData['passingYear'] ?? null,
            
            'percentage' => $rawData['percentage'] ?? null,
            
            // ✅ SCHOLARSHIP (both formats - CRITICAL FIX)
            'is_repeater' => $rawData['isRepeater'] ?? 'No',
            'isRepeater' => $rawData['isRepeater'] ?? 'No',
            
            'scholarship_test' => $rawData['scholarshipTest'] ?? 'No',
            'scholarshipTest' => $rawData['scholarshipTest'] ?? 'No',
            
            // ⭐ BOARD PERCENTAGE - ALL 3 FORMATS TO FIX YOUR ERROR
            'board_percentage' => $rawData['lastBoardPercentage'] ?? null,
            'last_board_percentage' => $rawData['lastBoardPercentage'] ?? null,
            'lastBoardPercentage' => $rawData['lastBoardPercentage'] ?? null,
            
            'competition_exam' => $rawData['competitionExam'] ?? 'No',
            'competitionExam' => $rawData['competitionExam'] ?? 'No',
            
            // ✅ BATCH (both formats)
            'batch_name' => $rawData['batchName'] ?? null,
            'batchName' => $rawData['batchName'] ?? null,
            
            'batch_id' => $rawData['batch_id'] ?? null,
            'course_id' => $rawData['course_id'] ?? null,
            
            // ✅ FEES & SCHOLARSHIP
            'eligible_for_scholarship' => $rawData['eligible_for_scholarship'] ?? 'No',
            'scholarship_name' => $rawData['scholarship_name'] ?? null,
            'total_fee_before_discount' => $rawData['total_fee_before_discount'] ?? 0,
            'discretionary_discount' => $rawData['discretionary_discount'] ?? 'No',
            'discretionary_discount_type' => $rawData['discretionary_discount_type'] ?? null,
            'discretionary_discount_value' => $rawData['discretionary_discount_value'] ?? 0,
            'discretionary_discount_reason' => $rawData['discretionary_discount_reason'] ?? null,
            'discount_percentage' => $rawData['discount_percentage'] ?? 0,
            'discounted_fee' => $rawData['discounted_fee'] ?? 0,
            'fees_breakup' => $rawData['fees_breakup'] ?? null,
            'total_fees' => $rawData['total_fees'] ?? 0,
            'gst_amount' => $rawData['gst_amount'] ?? 0,
            'total_fees_inclusive_tax' => $rawData['total_fees_inclusive_tax'] ?? 0,
            'single_installment_amount' => $rawData['single_installment_amount'] ?? 0,
            'installment_1' => $rawData['installment_1'] ?? 0,
            'installment_2' => $rawData['installment_2'] ?? 0,
            'installment_3' => $rawData['installment_3'] ?? 0,
            'paid_fees' => 0,
            'remaining_fees' => $rawData['total_fees_inclusive_tax'] ?? 0,
            
            // ⭐ CRITICAL: DOCUMENTS - Copy ALL
            'passport_photo' => $rawData['passport_photo'] ?? null,
            'marksheet' => $rawData['marksheet'] ?? null,
            'caste_certificate' => $rawData['caste_certificate'] ?? null,
            'scholarship_proof' => $rawData['scholarship_proof'] ?? null,
            'secondary_marksheet' => $rawData['secondary_marksheet'] ?? null,
            'senior_secondary_marksheet' => $rawData['senior_secondary_marksheet'] ?? null,
            
            // ✅ ARRAYS
            'fees' => [],
            'other_fees' => [],
            'transactions' => [],
            'paymentHistory' => [],
            'history' => $rawData['history'] ?? [],
            
            // ✅ ACTIVITY LOG
            'activities' => [[
                'title' => 'Student Enrolled',
                'description' => 'Student successfully enrolled with Roll No: ' . $rollNo,
                'performed_by' => auth()->user()->name ?? 'System',
                'performed_by_email' => auth()->user()->email ?? 'system@school.com',
                'created_at' => now()->toDateTimeString(),
                'timestamp' => now()->timestamp,
                'ip_address' => request()->ip()
            ]],
            
            // ✅ STATUS
            'status' => 'active',
            'admission_date' => $rawData['admission_date'] ?? now(),
            'transferred_from' => 'pending_fees',
            'pending_fees_id' => (string)$pendingFees->_id,
            'transferred_at' => now(),
            'created_by' => $rawData['created_by'] ?? auth()->user()->name ?? 'System',
            'updated_by' => auth()->user()->name ?? 'System'
        ];
        
        // Create SM Student
        $smStudent = SMstudents::create($smStudentData);
        
        // Update Pending Fees status
        $pendingFees->update([
            'status' => 'completed',
            'sm_student_id' => (string)$smStudent->_id
        ]);
        
        Log::info('Transfer to SM Students successful:', [
            'sm_student_id' => (string)$smStudent->_id,
            'roll_no' => $rollNo,
            'name' => $smStudentData['student_name'],
            'father_name' => $smStudentData['father_name'],
            'mother_name' => $smStudentData['mother_name'],
            'documents' => [
                'passport_photo' => !empty($smStudentData['passport_photo']),
                'marksheet' => !empty($smStudentData['marksheet']),
                'caste_certificate' => !empty($smStudentData['caste_certificate'])
            ]
        ]);
        
        return redirect()->route('smstudents.index')
            ->with('success', 'Student enrolled successfully! Roll No: ' . $rollNo);
        
    } catch (\Exception $e) {
        Log::error('Transfer to SM Students failed:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()->with('error', 'Enrollment failed: ' . $e->getMessage());
    }
}


/**
 * Generate unique roll number
 */
private function generateRollNumber()
{
    $year = date('y');
    $lastStudent = SMstudents::orderBy('created_at', 'desc')->first();
    
    if ($lastStudent && isset($lastStudent->roll_no)) {
        // Extract number from last roll no (e.g., STU240001 -> 0001)
        preg_match('/\d+$/', $lastStudent->roll_no, $matches);
        $lastNumber = isset($matches[0]) ? intval($matches[0]) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    } else {
        $newNumber = '0001';
    }
    
    return 'STU' . $year . $newNumber;
}

}