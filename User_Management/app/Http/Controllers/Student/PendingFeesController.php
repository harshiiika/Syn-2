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

            // ✅ CRITICAL FIX: Only consider fully paid if balance is very close to zero (≤ ₹5)
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

                $smStudentData = [
                    'roll_no' => $student->roll_no ?? 'SM' . now()->format('ymd') . rand(100, 999),
                    'student_name' => $student->name,
                    'email' => $student->email ?? $student->studentContact ?? ($student->name . '@temp.com'),
                    'phone' => $student->mobileNumber ?? null,
                    'father_name' => $student->father ?? null,
                    'mother_name' => $student->mother ?? null,
                    'dob' => $student->dob ?? null,
                    'father_contact' => $student->mobileNumber ?? null,
                    'father_whatsapp' => $student->fatherWhatsapp ?? null,
                    'mother_contact' => $student->motherContact ?? null,
                    'gender' => $student->gender ?? null,
                    'father_occupation' => $student->fatherOccupation ?? null,
                    'father_caste' => $student->category ?? null,
                    'mother_occupation' => $student->motherOccupation ?? null,
                    'state' => $student->state ?? null,
                    'city' => $student->city ?? null,
                    'pincode' => $student->pinCode ?? null,
                    'address' => $student->address ?? null,
                    'belongs_other_city' => $student->belongToOtherCity ?? 'No',
                    'previous_class' => $student->previousClass ?? null,
                    'academic_medium' => $student->previousMedium ?? $student->medium ?? null,
                    'school_name' => $student->schoolName ?? null,
                    'academic_board' => $student->previousBoard ?? $student->board ?? null,
                    'passing_year' => $student->passingYear ?? null,
                    'percentage' => $student->percentage ?? null,
                    'batch_id' => $student->batch_id ?? null,
                    'batch_name' => $student->batchName ?? null,
                    'course_id' => $student->course_id ?? null,
                    'course_name' => $student->courseName ?? null,
                    'course_content' => $student->courseContent ?? null,
                    'delivery' => $student->deliveryMode ?? 'Offline',
                    'delivery_mode' => $student->deliveryMode ?? 'Offline',
                    'eligible_for_scholarship' => $student->eligible_for_scholarship ?? 'No',
                    'scholarship_name' => $student->scholarship_name ?? 'N/A',
                    'total_fee_before_discount' => $student->total_fee_before_discount ?? $totalFees,
                    'discount_percentage' => $student->discount_percentage ?? 0,
                    'discounted_fee' => $student->discounted_fee ?? $totalFees,
                    'total_fees' => $totalFees,
                    'gst_amount' => $gstAmount,
                    'total_fees_inclusive_tax' => $totalFeesWithGST,
                    'fees_breakup' => $student->fees_breakup ?? 'Class room course (with test series & study material)',
                    'paid_fees' => $newPaidAmount,
                    'paidAmount' => $newPaidAmount,
                    'remaining_fees' => 0,
                    'remainingAmount' => 0,
                    'fee_status' => 'paid',
                    'paymentHistory' => $paymentHistory,
                    'last_payment_date' => $validated['payment_date'],
                    'status' => 'active',
                    'transferred_from' => 'pending_fees',
                    'transferred_at' => now(),
                    'activities' => $activities, // ✅ Add activities
                    'created_at' => $student->created_at ?? now(),
                    'updated_at' => now(),
                ];

                $smStudent = SMstudents::create($smStudentData);

                Log::info('Student transferred to SMstudents with activities', [
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
}