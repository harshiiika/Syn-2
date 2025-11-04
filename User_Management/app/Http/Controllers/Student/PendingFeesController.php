<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Student\PendingFee;
use App\Models\Student\SMstudents;

class PendingFeesController extends Controller
{
    /**
     * Display all students with pending fees
     */
    public function index()
    {
        try {
            $pendingFees = PendingFee::where(function ($query) {
                    $query->where('remaining_fees', '>', 0)
                        ->orWhere('status', 'pending_fees');
                })
                ->orderBy('created_at', 'desc')
                ->get();

            Log::info('Fetching pending fees students:', [
                'count' => $pendingFees->count(),
            ]);

            return view('student.pendingfees.pending', [  // ✅ Changed to 'pending'
                'pendingFees' => $pendingFees,
                'totalCount' => $pendingFees->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading pending fees students: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load students');
        }
    }

    /**
     * Display student details
     */
    public function view(string $id)
    {
        try {
            $student = PendingFee::findOrFail($id);

            $feesData = [
                'eligible_for_scholarship' => $student->eligible_for_scholarship ?? 'No',
                'scholarship_name' => $student->scholarship_name ?? 'N/A',
                'total_fee_before_discount' => $student->total_fee_before_discount ?? 0,
                'discretionary_discount' => $student->discretionary_discount ?? 'No',
                'discount_percentage' => $student->discount_percentage ?? 0,
                'discounted_fee' => $student->discounted_fee ?? 0,
                'fees_breakup' => $student->fees_breakup ?? 'N/A',
                'total_fees' => $student->total_fees ?? 0,
                'gst_amount' => $student->gst_amount ?? 0,
                'total_fees_inclusive_tax' => $student->total_fees_inclusive_tax ?? 0,
                'paid_fees' => $student->paid_fees ?? 0,
                'remaining_fees' => $student->remaining_fees ?? 0,
            ];

            return view('student.pendingfees.view', compact('student', 'feesData'));
        } catch (\Exception $e) {
            Log::error("View failed for pending fees student ID {$id}: " . $e->getMessage());
            return redirect()->route('student.pendingfees.pending')->with('error', 'Student not found');  // ✅ Correct
        }
    }

    /**
     * Show payment form
     */
    public function pay($id)
    {
        try {
            $student = PendingFee::findOrFail($id);

            $totalFees = floatval($student->total_fees ?? 0);
            $gstAmount = floatval($student->gst_amount ?? 0);
            
            if ($gstAmount == 0 && $totalFees > 0) {
                $gstAmount = $totalFees * 0.18;
            }

            $totalFeesWithGST = $totalFees + $gstAmount;

            $totalPaid = 0;
            if (isset($student->paymentHistory) && is_array($student->paymentHistory)) {
                foreach ($student->paymentHistory as $payment) {
                    $totalPaid += floatval($payment['amount'] ?? 0);
                }
            } else {
                $totalPaid = floatval($student->paid_fees ?? 0);
            }

            $remainingBalance = max(0, $totalFeesWithGST - $totalPaid);
            $firstInstallment = $totalFeesWithGST * 0.40;

            return view('student.pendingfees.pay', compact(
                'student',
                'totalFees',
                'gstAmount',
                'totalFeesWithGST',
                'totalPaid',
                'remainingBalance',
                'firstInstallment'
            ));
        } catch (\Exception $e) {
            Log::error('Payment form error:', ['id' => $id, 'error' => $e->getMessage()]);
            return redirect()->route('student.pendingfees.pending')->with('error', 'Unable to load payment form');  // ✅ Correct
        }
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
                'transaction_id' => 'nullable|string',
                'remarks' => 'nullable|string',
                'do_you_want_to_pay_fees' => 'required|in:single_payment,in_installment',
                'other_charges' => 'nullable|numeric|min:0',
            ]);

            $student = PendingFee::findOrFail($id);

            $paymentAmount = floatval($validated['payment_amount']);
            $otherCharges = floatval($validated['other_charges'] ?? 0);

            $paymentRecord = [
                'date' => $validated['payment_date'],
                'amount' => $paymentAmount,
                'method' => $validated['payment_type'],
                'transaction_id' => $validated['transaction_id'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
                'type' => $validated['do_you_want_to_pay_fees'],
                'other_charges' => $otherCharges,
                'recorded_at' => now()->toDateTimeString(),
                'recorded_by' => auth()->user()->name ?? 'Admin',
            ];

            $paymentHistory = $student->paymentHistory ?? [];
            if (!is_array($paymentHistory)) {
                $paymentHistory = [];
            }
            $paymentHistory[] = $paymentRecord;

            $totalFees = floatval($student->total_fees ?? 0);
            $gstAmount = floatval($student->gst_amount ?? 0);
            if ($gstAmount == 0 && $totalFees > 0) {
                $gstAmount = $totalFees * 0.18;
            }
            $totalFeesWithGST = $totalFees + $gstAmount;

            $newPaidAmount = 0;
            foreach ($paymentHistory as $p) {
                $newPaidAmount += floatval($p['amount'] ?? 0);
            }
            $newRemainingBalance = max(0, $totalFeesWithGST - $newPaidAmount);

            // Fully paid -> transfer to SMstudents
            if ($newRemainingBalance <= 0) {
                Log::info('✅ FEES FULLY PAID - Transferring to SMstudents', ['student_id' => $id]);

                $smStudentData = [
                    'roll_no' => $student->roll_no ?? 'SM' . now()->format('ymd') . rand(100, 999),
                    'student_name' => $student->name,
                    'email' => $student->email ?? $student->studentContact ?? null,
                    'phone' => $student->mobileNumber ?? null,
                    'father_name' => $student->father ?? null,
                    'mother_name' => $student->mother ?? null,
                    'dob' => $student->dob ?? null,
                    'father_contact' => $student->fatherWhatsapp ?? null,
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
                    'scholarship_name' => $student->scholarship_name ?? null,
                    'total_fee_before_discount' => $student->total_fee_before_discount ?? $totalFees,
                    'total_fees' => $totalFees,
                    'gst_amount' => $gstAmount,
                    'total_fees_inclusive_tax' => $totalFeesWithGST,
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
                    'created_at' => $student->created_at ?? now(),
                    'updated_at' => now(),
                ];

                $smStudent = SMstudents::create($smStudentData);

                Log::info('✅ Student transferred to SMstudents', [
                    'sm_student_id' => $smStudent->_id,
                    'name' => $smStudent->student_name,
                ]);

                $student->delete();

                return redirect()->route('smstudents.index')
                    ->with('success', "Payment successful! Student '{$smStudent->student_name}' moved to Active Students.");
            }

            // Partial payment
            $feeStatus = $newPaidAmount > 0 ? 'partial' : 'pending';

            $student->update([
                'paymentHistory' => $paymentHistory,
                'paid_fees' => $newPaidAmount,
                'paidAmount' => $newPaidAmount,
                'remaining_fees' => $newRemainingBalance,
                'remainingAmount' => $newRemainingBalance,
                'fee_status' => $feeStatus,
                'last_payment_date' => $validated['payment_date'],
                'total_fees' => $totalFees,
                'gst_amount' => $gstAmount,
                'total_fees_inclusive_tax' => $totalFeesWithGST,
                'updated_at' => now(),
            ]);

            $message = "Payment of ₹" . number_format($paymentAmount, 2) . " recorded successfully! Remaining: ₹" . number_format($newRemainingBalance, 2);

            return redirect()->route('student.pendingfees.pending')->with('success', $message);  // ✅ Changed to 'pending'

        } catch (\Exception $e) {
            Log::error('❌ Payment processing failed', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Payment failed: ' . $e->getMessage())->withInput();
        }
    }
}