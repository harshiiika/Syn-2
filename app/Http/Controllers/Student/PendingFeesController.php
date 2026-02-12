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
/**
 * Display all students with pending fees with search and pagination
 */
public function index(Request $request)
{
    try {
        // Get per_page value from request, default to 10
        $perPage = $request->input('per_page', 10);
        
        // Validate per_page to only allow specific values
        if (!in_array($perPage, [5, 10, 25, 50, 100])) {
            $perPage = 10;
        }

        // Get search query
        $search = $request->input('search', '');

        // Build the query - only show students that haven't been transferred
        $query = PendingFee::where('status', '!=', 'completed');

        // Apply search filter if search term exists
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('father', 'like', '%' . $search . '%')
                  ->orWhere('mobileNumber', 'like', '%' . $search . '%')
                  ->orWhere('courseName', 'like', '%' . $search . '%')
                  ->orWhere('deliveryMode', 'like', '%' . $search . '%')
                  ->orWhere('courseContent', 'like', '%' . $search . '%');
            });
        }

        // Get paginated students
        $pendingFees = $query->orderBy('created_at', 'desc')
                             ->paginate($perPage)
                             ->appends([
                                 'search' => $search,
                                 'per_page' => $perPage
                             ]);

        Log::info('Fetching pending fees students:', [
            'count' => $pendingFees->count(),
            'total' => $pendingFees->total(),
            'per_page' => $perPage,
            'search' => $search
        ]);

        return view('student.pendingfees.pending', [
            'pendingFees' => $pendingFees,
            'totalCount' => $pendingFees->total(),
            'search' => $search
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
            
            // Get ALL history entries
            $history = $student->history ?? [];
            
            // Get payment history for additional details
            $paymentHistory = $student->paymentHistory ?? [];
            
            // Merge and enrich history with payment details
            $enrichedHistory = [];
            
            foreach ($history as $entry) {
                $enrichedEntry = $entry;
                
                // If this is a payment entry, add installment details
                if (isset($entry['action']) && $entry['action'] === 'Fee Paid') {
                    // Find matching payment in paymentHistory
                    foreach ($paymentHistory as $payment) {
                        $paymentDate = $payment['date'] ?? null;
                        $entryTimestamp = $entry['timestamp'] ?? $entry['created_at'] ?? null;
                        
                        // Match by date/time proximity (within same minute)
                        if ($paymentDate && $entryTimestamp) {
                            $paymentTime = strtotime($paymentDate);
                            $entryTime = strtotime($entryTimestamp);
                            
                            if (abs($paymentTime - $entryTime) < 60) {
                                $enrichedEntry['payment_details'] = [
                                    'amount' => $payment['amount'] ?? 0,
                                    'method' => $payment['method'] ?? 'N/A',
                                    'installment_number' => $payment['installment_number'] ?? null,
                                    'transaction_id' => $payment['transaction_id'] ?? null,
                                    'remarks' => $payment['remarks'] ?? null
                                ];
                                break;
                            }
                        }
                    }
                }
                
                $enrichedHistory[] = $enrichedEntry;
            }
            
            // Sort by date (newest first)
            usort($enrichedHistory, function($a, $b) {
                $timeA = strtotime($a['timestamp'] ?? $a['created_at'] ?? '1970-01-01');
                $timeB = strtotime($b['timestamp'] ?? $b['created_at'] ?? '1970-01-01');
                return $timeB - $timeA;
            });
            
            Log::info('  History retrieved successfully', [
                'student_id' => $id,
                'total_entries' => count($enrichedHistory),
                'payment_entries' => count(array_filter($enrichedHistory, function($e) {
                    return isset($e['action']) && $e['action'] === 'Fee Paid';
                }))
            ]);
            
            return response()->json([
                'success' => true,
                'data' => $enrichedHistory,
                'student_name' => $student->name ?? 'N/A',
                'total_paid' => $student->paidAmount ?? $student->paid_fees ?? 0,
                'remaining' => $student->remainingAmount ?? $student->remaining_fees ?? 0
            ]);
            
        } catch (\Exception $e) {
            Log::error(' Get history error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
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

            //    : Calculate fees if missing
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

            //   VALIDATION: Ensure fees are not zero
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

            //   CALCULATE INSTALLMENTS - 40%, 30%, 30%
            $installment1 = round($totalFeesWithGST * 0.40, 2);
            $installment2 = round($totalFeesWithGST * 0.30, 2);
            $installment3 = round($totalFeesWithGST * 0.30, 2);

            //   CALCULATE ADJUSTED INSTALLMENTS based on payments made
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
     *   Calculate adjusted installments based on payment history
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
            
            // Create payment record
            $paymentRecord = [
                'date' => $validated['payment_date'],
                'amount' => $paymentAmount,
                'method' => $validated['payment_type'],
                'payment_mode' => $validated['payment_mode'],
                'installment_number' => $validated['installment_number'] ?? null,
                'transaction_id' => $validated['transaction_id'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
                'other_charges' => floatval($validated['other_charges'] ?? 0),
                'recorded_at' => now()->toDateTimeString(),
                'recorded_by' => auth()->user()->name ?? 'Admin',
            ];

            // Update payment history
            $paymentHistory = $student->paymentHistory ?? [];
            if (!is_array($paymentHistory)) {
                $paymentHistory = [];
            }
            $paymentHistory[] = $paymentRecord;

            // Update history log
            $existingHistory = $student->history ?? [];
            if (!is_array($existingHistory)) {
                $existingHistory = [];
            }
            
            $userName = auth()->user()->name ?? 'Admin';
            $studentName = $student->name;
            
            $paymentHistoryEntry = [
                'action' => 'Fee Paid',
                'description' => "{$userName} paid ₹" . number_format($paymentAmount, 2) . " for {$studentName} via {$validated['payment_type']}.",
                'user' => $userName,
                'timestamp' => now()->toIso8601String(),
                'created_at' => now()->toDateTimeString(),
                'details' => [
                    'amount' => $paymentAmount,
                    'payment_method' => $validated['payment_type'],
                    'payment_mode' => $validated['payment_mode'],
                    'installment_number' => $validated['installment_number'] ?? null,
                    'transaction_id' => $validated['transaction_id'] ?? null,
                ]
            ];
            
            array_unshift($existingHistory, $paymentHistoryEntry);

            // Calculate totals
            $totalFeesWithGST = floatval($student->total_fees_inclusive_tax ?? 0);
            $newPaidAmount = 0;
            foreach ($paymentHistory as $p) {
                $newPaidAmount += floatval($p['amount'] ?? 0);
            }
            
            $newRemainingBalance = max(0, $totalFeesWithGST - $newPaidAmount);
            $isFullyPaid = $newRemainingBalance <= 5;

            //   IF FULLY PAID - Transfer to BOTH collections
            if ($isFullyPaid) {
                Log::info('  FEES FULLY PAID - Transferring to BOTH collections');

                // Add completion history
                $completionHistoryEntry = [
                    'action' => 'Fee Paid & Student Onboarding Complete',
                    'description' => "{$userName} completed the full fee payment for {$studentName}. Student transferred to Active Students.",
                    'user' => $userName,
                    'timestamp' => now()->toIso8601String(),
                    'created_at' => now()->toDateTimeString(),
                    'details' => [
                        'total_amount_paid' => $newPaidAmount,
                        'payment_installments' => count($paymentHistory)
                    ]
                ];
                
                array_unshift($existingHistory, $completionHistoryEntry);

                // Create activities
                $activities = [
                    [
                        'title' => 'Fees Payment Completed',
                        'description' => "paid full fees amount of ₹" . number_format($totalFeesWithGST, 2),
                        'performed_by' => $userName,
                        'created_at' => now()->toIso8601String(),
                    ],
                    [
                        'title' => 'Student Activated',
                        'description' => "transferred student from pending fees to active students",
                        'performed_by' => $userName,
                        'created_at' => now()->toIso8601String(),
                    ]
                ];

                // Prepare complete student data
                $smStudentData = [
                    'roll_no' => $this->generateRollNumber(),
                    'student_name' => $student->name,
                    'name' => $student->name,
                    'email' => $student->email ?? ($student->name . '@temp.com'),
                    'phone' => $student->mobileNumber ?? null,
                    'father_name' => $student->father ?? null,
                    'father' => $student->father ?? null,
                    'mother_name' => $student->mother ?? null,
                    'mother' => $student->mother ?? null,
                    'dob' => $student->dob ?? null,
                    'father_contact' => $student->mobileNumber ?? null,
                    'mobileNumber' => $student->mobileNumber ?? null,
                    'father_whatsapp' => $student->fatherWhatsapp ?? null,
                    'mother_contact' => $student->motherContact ?? null,
                    'category' => $student->category ?? null,
                    'gender' => $student->gender ?? null,
                    'father_occupation' => $student->fatherOccupation ?? null,
                    'mother_occupation' => $student->motherOccupation ?? null,
                    'state' => $student->state ?? null,
                    'city' => $student->city ?? null,
                    'pincode' => $student->pinCode ?? null,
                    'address' => $student->address ?? null,
                    'batch_name' => $student->batchName ?? null,
                    'batch_id' => $student->batch_id ?? null,
                    'course_id' => $student->course_id ?? null,
                    'course_name' => $student->courseName ?? null,
                    'delivery_mode' => $student->deliveryMode ?? 'Offline',
                    'total_fees' => floatval($student->total_fees ?? 0),
                    'gst_amount' => floatval($student->gst_amount ?? 0),
                    'total_fees_inclusive_tax' => $totalFeesWithGST,
                    'paid_fees' => $newPaidAmount,
                    'paidAmount' => $newPaidAmount,
                    'remaining_fees' => 0,
                    'fee_status' => 'paid',
                    'paymentHistory' => $paymentHistory,
                    'last_payment_date' => $validated['payment_date'],
                    'status' => 'active',
                    'transferred_from' => 'pending_fees',
                    'transferred_at' => now(),
                    'activities' => $activities,
                    'history' => $existingHistory,
                    'admission_date' => now(),
                    
                    // Transfer all other fields
                    'course_type' => $student->courseType ?? null,
                    'delivery' => $student->deliveryMode ?? null,
                    'medium' => $student->medium ?? null,
                    'board' => $student->board ?? null,
                    'course_content' => $student->courseContent ?? null,
                    'previous_class' => $student->previousClass ?? null,
                    'academic_medium' => $student->previousMedium ?? null,
                    'school_name' => $student->schoolName ?? null,
                    'academic_board' => $student->previousBoard ?? null,
                    'passing_year' => $student->passingYear ?? null,
                    'percentage' => $student->percentage ?? null,
                    'is_repeater' => $student->isRepeater ?? 'No',
                    'scholarship_test' => $student->scholarshipTest ?? 'No',
                    'board_percentage' => $student->lastBoardPercentage ?? null,
                    'last_board_percentage' => $student->lastBoardPercentage ?? null,
                    'competition_exam' => $student->competitionExam ?? 'No',
                    
                    // Scholarship data
                    'eligible_for_scholarship' => $student->eligible_for_scholarship ?? 'No',
                    'scholarship_name' => $student->scholarship_name ?? null,
                    'discount_percentage' => floatval($student->discount_percentage ?? 0),
                    
                    // Documents
                    'passport_photo' => $student->passport_photo ?? null,
                    'marksheet' => $student->marksheet ?? null,
                    'caste_certificate' => $student->caste_certificate ?? null,
                    'scholarship_proof' => $student->scholarship_proof ?? null,
                    'secondary_marksheet' => $student->secondary_marksheet ?? null,
                    'senior_secondary_marksheet' => $student->senior_secondary_marksheet ?? null,
                ];

                //  SAVE TO BOTH COLLECTIONS
                try {
                    $result = SMstudents::createInBothCollections($smStudentData);
                    
                    Log::info('  Student saved to BOTH collections successfully', [
                        'main_student_id' => $result['main_student']->_id,
                        'course_student_id' => $result['course_student']->_id,
                        'course_collection' => $result['course_collection'],
                        'roll_no' => $result['main_student']->roll_no
                    ]);
                    
                    // Mark pending fee as completed (don't delete for audit)
                    $student->update([
                        'status' => 'completed',
                        'transferred_at' => now(),
                        'sm_student_id' => (string)$result['main_student']->_id
                    ]);

                    return redirect()->route('smstudents.index')
                        ->with('success', "  Payment successful! Student '{$result['main_student']->student_name}' enrolled and saved to BOTH main collection and course collection ({$result['course_collection']}). Total Paid: ₹" . number_format($newPaidAmount, 2));
                        
                } catch (\Exception $e) {
                    Log::error(' Failed to save to both collections', [
                        'error' => $e->getMessage()
                    ]);
                    
                    return redirect()->back()
                        ->with('error', 'Payment processed but enrollment failed: ' . $e->getMessage())
                        ->withInput();
                }
            }

            //   PARTIAL PAYMENT - Update pending fees
            $student->paid_fees = $newPaidAmount;
            $student->paidAmount = $newPaidAmount;
            $student->remaining_fees = $newRemainingBalance;
            $student->fee_status = $newPaidAmount > 0 ? 'partial' : 'pending';
            $student->last_payment_date = $validated['payment_date'];
            $student->setAttribute('paymentHistory', $paymentHistory);
            $student->setAttribute('history', $existingHistory);
            $student->save();

            return redirect()->route('student.pendingfees.pending')
                ->with('success', "Payment of ₹" . number_format($paymentAmount, 2) . " recorded! Remaining: ₹" . number_format($newRemainingBalance, 2));

        } catch (\Exception $e) {
            Log::error(' Payment processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
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
        
        //   COMPLETE DATA MAPPING - ALL FIELDS FROM BOTH MODELS
        $smStudentData = [
            // ========== PRIMARY FIELDS ==========
            'roll_no' => $rollNo,
            'student_name' => $rawData['name'] ?? 'N/A',
            'name' => $rawData['name'] ?? 'N/A', // Keep both
            'email' => $rawData['email'] ?? null,
            'phone' => $rawData['studentContact'] ?? $rawData['mobileNumber'] ?? null,
            
            // ========== PERSONAL DETAILS - BOTH FORMATS ==========
            'father_name' => $rawData['father'] ?? 'N/A',
            'father' => $rawData['father'] ?? 'N/A',
            
            'mother_name' => $rawData['mother'] ?? 'N/A',
            'mother' => $rawData['mother'] ?? 'N/A',
            
            'dob' => $rawData['dob'] ?? null,
            
            'father_contact' => $rawData['mobileNumber'] ?? null,
            'mobileNumber' => $rawData['mobileNumber'] ?? null,
            
            'father_whatsapp' => $rawData['fatherWhatsapp'] ?? null,
            'fatherWhatsapp' => $rawData['fatherWhatsapp'] ?? null,
            
            'mother_contact' => $rawData['motherContact'] ?? null,
            'motherContact' => $rawData['motherContact'] ?? null,
            
            'category' => $rawData['category'] ?? null,
            'gender' => $rawData['gender'] ?? null,
            
            'father_occupation' => $rawData['fatherOccupation'] ?? null,
            'fatherOccupation' => $rawData['fatherOccupation'] ?? null,
            
            'father_grade' => $rawData['fatherGrade'] ?? null,
            'fatherGrade' => $rawData['fatherGrade'] ?? null,
            
            'mother_occupation' => $rawData['motherOccupation'] ?? null,
            'motherOccupation' => $rawData['motherOccupation'] ?? null,
            
            // ========== ADDRESS - BOTH FORMATS ==========
            'state' => $rawData['state'] ?? null,
            'city' => $rawData['city'] ?? null,
            
            'pincode' => $rawData['pinCode'] ?? null,
            'pinCode' => $rawData['pinCode'] ?? null,
            
            'address' => $rawData['address'] ?? null,
            
            // ========== ADDITIONAL INFO - BOTH FORMATS ==========
            'belongs_other_city' => $rawData['belongToOtherCity'] ?? 'No',
            'belongToOtherCity' => $rawData['belongToOtherCity'] ?? 'No',
            
            'economic_weaker_section' => $rawData['economicWeakerSection'] ?? 'No',
            'economicWeakerSection' => $rawData['economicWeakerSection'] ?? 'No',
            
            'army_police_background' => $rawData['armyPoliceBackground'] ?? 'No',
            'armyPoliceBackground' => $rawData['armyPoliceBackground'] ?? 'No',
            
            'specially_abled' => $rawData['speciallyAbled'] ?? 'No',
            'speciallyAbled' => $rawData['speciallyAbled'] ?? 'No',
            
            // ========== COURSE DETAILS - BOTH FORMATS ==========
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
            
            // ========== ACADEMIC DETAILS - BOTH FORMATS ==========
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
            
            // ========== SCHOLARSHIP - ALL 3 FORMATS ==========
            'is_repeater' => $rawData['isRepeater'] ?? 'No',
            'isRepeater' => $rawData['isRepeater'] ?? 'No',
            
            'scholarship_test' => $rawData['scholarshipTest'] ?? 'No',
            'scholarshipTest' => $rawData['scholarshipTest'] ?? 'No',
            
            //  CRITICAL  : Board Percentage - ALL 3 FORMATS
            'board_percentage' => $rawData['lastBoardPercentage'] ?? null,
            'last_board_percentage' => $rawData['lastBoardPercentage'] ?? null,
            'lastBoardPercentage' => $rawData['lastBoardPercentage'] ?? null,
            
            'competition_exam' => $rawData['competitionExam'] ?? 'No',
            'competitionExam' => $rawData['competitionExam'] ?? 'No',
            
            // ========== BATCH - BOTH FORMATS ==========
            'batch_name' => $rawData['batchName'] ?? null,
            'batchName' => $rawData['batchName'] ?? null,
            
            'batch_id' => $rawData['batch_id'] ?? null,
            'course_id' => $rawData['course_id'] ?? null,
            
            // ========== FEES & SCHOLARSHIP ==========
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
            'paid_fees' => $rawData['paid_fees'] ?? $rawData['paidAmount'] ?? 0,
            'paidAmount' => $rawData['paidAmount'] ?? $rawData['paid_fees'] ?? 0,
            'remaining_fees' => $rawData['remaining_fees'] ?? $rawData['remainingAmount'] ?? 0,
            'remainingAmount' => $rawData['remainingAmount'] ?? $rawData['remaining_fees'] ?? 0,
            'fee_status' => $rawData['fee_status'] ?? 'paid',
            
            // ========== CRITICAL: DOCUMENTS - Copy ALL ==========
            'passport_photo' => $rawData['passport_photo'] ?? null,
            'marksheet' => $rawData['marksheet'] ?? null,
            'caste_certificate' => $rawData['caste_certificate'] ?? null,
            'scholarship_proof' => $rawData['scholarship_proof'] ?? null,
            'secondary_marksheet' => $rawData['secondary_marksheet'] ?? null,
            'senior_secondary_marksheet' => $rawData['senior_secondary_marksheet'] ?? null,
            
            // ========== ARRAYS ==========
            'fees' => [],
            'other_fees' => [],
            'transactions' => [],
            'paymentHistory' => $rawData['paymentHistory'] ?? [],
            'history' => $rawData['history'] ?? [], //  TRANSFER COMPLETE HISTORY
            
            // ========== ACTIVITY LOG ==========
            'activities' => [[
                'title' => 'Student Enrolled',
                'description' => 'Student successfully enrolled with Roll No: ' . $rollNo,
                'performed_by' => auth()->user()->name ?? 'System',
                'performed_by_email' => auth()->user()->email ?? 'system@school.com',
                'created_at' => now()->toDateTimeString(),
                'timestamp' => now()->timestamp,
                'ip_address' => request()->ip()
            ]],
            
            // ========== STATUS ==========
            'status' => 'active',
            'admission_date' => $rawData['admission_date'] ?? now(),
            'transferred_from' => 'pending_fees',
            'pending_fees_id' => (string)$pendingFees->_id,
            'transferred_at' => now(),
            'created_by' => $rawData['created_by'] ?? auth()->user()->name ?? 'System',
            'updated_by' => auth()->user()->name ?? 'System'
        ];
        
        //   Create SM Student
        $smStudent = SMstudents::create($smStudentData);
        
        //   Update Pending Fees status (DON'T DELETE - Keep for audit)
        $pendingFees->update([
            'status' => 'completed',
            'transferred_at' => now(),
            'sm_student_id' => (string)$smStudent->_id
        ]);
        
        Log::info('  Transfer to SM Students successful:', [
            'sm_student_id' => (string)$smStudent->_id,
            'roll_no' => $rollNo,
            'name' => $smStudentData['student_name'],
            'father_name' => $smStudentData['father_name'],
            'mother_name' => $smStudentData['mother_name'],
            'board_percentage' => $smStudentData['board_percentage'] ?? 'N/A',
            'documents' => [
                'passport_photo' => !empty($smStudentData['passport_photo']),
                'marksheet' => !empty($smStudentData['marksheet']),
                'caste_certificate' => !empty($smStudentData['caste_certificate'])
            ],
            'history_entries' => count($smStudentData['history'])
        ]);
        
        return redirect()->route('smstudents.index')
            ->with('success', 'Student enrolled successfully! Roll No: ' . $rollNo);
        
    } catch (\Exception $e) {
        Log::error(' Transfer to SM Students failed:', [
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