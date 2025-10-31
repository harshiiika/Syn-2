<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student\Student;
use App\Models\Student\Pending;
use App\Models\Student\Onboard;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Show payment form for a student
     */
    public function show($id)
    {
        try {
            // Try to find student in all collections
            $student = null;
            $collection = null;
            
            if ($student = Student::find($id)) {
                $collection = 'students';
            } elseif ($student = Pending::find($id)) {
                $collection = 'pending';
            } elseif ($student = Onboard::find($id)) {
                $collection = 'onboard';
            }
            
            if (!$student) {
                throw new \Exception('Student not found in any collection');
            }
            
            // Get fee details from student record
            $totalFees = floatval($student->total_fees ?? 0);
            $gstAmount = floatval($student->gst_amount ?? 0);
            
            // If no fees set, calculate from total_fee_before_discount
            if ($totalFees == 0 && isset($student->total_fee_before_discount)) {
                $totalFees = floatval($student->total_fee_before_discount);
                $gstAmount = $totalFees * 0.18;
            }
            
            // If still no fees, use default
            if ($totalFees == 0) {
                $totalFees = 100000;
                $gstAmount = 18000;
            }
            
            $totalFeesWithGST = $totalFees + $gstAmount;
            
            // Get paid amount from payment history or direct field
            $totalPaid = 0;
            if (isset($student->paymentHistory) && is_array($student->paymentHistory)) {
                foreach ($student->paymentHistory as $payment) {
                    $totalPaid += floatval($payment['amount'] ?? 0);
                }
            } else {
                $totalPaid = floatval($student->paid_fees ?? $student->paidAmount ?? 0);
            }
            
            $remainingBalance = $totalFeesWithGST - $totalPaid;
            
            // Ensure remaining balance is not negative
            if ($remainingBalance < 0) {
                $remainingBalance = 0;
            }
            
            // Calculate first installment (40% of total with GST)
            $firstInstallment = $totalFeesWithGST * 0.40;
            
            Log::info('Payment form opened:', [
                'student_id' => $id,
                'collection' => $collection,
                'student_name' => $student->name,
                'total_fees' => $totalFees,
                'gst_amount' => $gstAmount,
                'total_with_gst' => $totalFeesWithGST,
                'total_paid' => $totalPaid,
                'remaining_balance' => $remainingBalance
            ]);
            
            return view('student.payment.show', compact(
                'student',
                'totalFees',
                'gstAmount',
                'totalFeesWithGST',
                'totalPaid',
                'remainingBalance',
                'firstInstallment'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error loading payment form:', [
                'student_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('student.pendingfees.pending')
                ->with('error', 'Unable to load payment form: ' . $e->getMessage());
        }
    }
    
    /**
     * Process payment submission
     */
    public function process(Request $request, $id)
    {
        try {
            Log::info('=== PAYMENT PROCESSING START ===', [
                'student_id' => $id,
                'request_data' => $request->except('_token')
            ]);
            
            // Validate payment data
            $validated = $request->validate([
                'payment_date' => 'required|date',
                'payment_method' => 'required|in:cash,online,cheque,card',
                'payment_amount' => 'required|numeric|min:1',
                'transaction_id' => 'nullable|string',
                'remarks' => 'nullable|string',
                'payment_type' => 'required|in:single,installment',
                'other_charges' => 'nullable|numeric|min:0'
            ]);
            
            // Find student in all collections
            $student = null;
            $collection = null;
            
            if ($student = Student::find($id)) {
                $collection = 'students';
            } elseif ($student = Pending::find($id)) {
                $collection = 'pending';
            } elseif ($student = Onboard::find($id)) {
                $collection = 'onboard';
            }
            
            if (!$student) {
                throw new \Exception('Student not found');
            }
            
            Log::info('Student found for payment:', [
                'collection' => $collection,
                'student_name' => $student->name
            ]);
            
            // Calculate payment details
            $paymentAmount = floatval($validated['payment_amount']);
            $otherCharges = floatval($validated['other_charges'] ?? 0);
            $totalPayment = $paymentAmount;
            
            // Create payment record
            $paymentRecord = [
                'date' => $validated['payment_date'],
                'amount' => $totalPayment,
                'method' => $validated['payment_method'],
                'transaction_id' => $validated['transaction_id'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
                'type' => $validated['payment_type'],
                'other_charges' => $otherCharges,
                'recorded_at' => now()->toDateTimeString(),
                'recorded_by' => auth()->user()->name ?? 'Admin'
            ];
            
            // Get existing payment history
            $paymentHistory = $student->paymentHistory ?? [];
            if (!is_array($paymentHistory)) {
                $paymentHistory = [];
            }
            $paymentHistory[] = $paymentRecord;
            
            // Calculate new totals
            $totalFees = floatval($student->total_fees ?? $student->total_fee_before_discount ?? 0);
            $gstAmount = floatval($student->gst_amount ?? 0);
            
            if ($gstAmount == 0 && $totalFees > 0) {
                $gstAmount = $totalFees * 0.18;
            }
            
            $totalFeesWithGST = $totalFees + $gstAmount;
            
            // Calculate total paid from payment history
            $newPaidAmount = 0;
            foreach ($paymentHistory as $payment) {
                $newPaidAmount += floatval($payment['amount'] ?? 0);
            }
            
            $newRemainingBalance = $totalFeesWithGST - $newPaidAmount;
            
            // Ensure remaining balance is not negative
            if ($newRemainingBalance < 0) {
                $newRemainingBalance = 0;
            }
            
            // Determine fee status
            $feeStatus = 'pending';
            if ($newRemainingBalance <= 0) {
                $feeStatus = 'paid';
            } elseif ($newPaidAmount > 0) {
                $feeStatus = 'partial';
            }
            
            Log::info('Payment calculations:', [
                'total_fees' => $totalFees,
                'gst_amount' => $gstAmount,
                'total_with_gst' => $totalFeesWithGST,
                'payment_amount' => $totalPayment,
                'new_paid_amount' => $newPaidAmount,
                'new_remaining_balance' => $newRemainingBalance,
                'fee_status' => $feeStatus
            ]);
            
            // Update student record with comprehensive fee data
            $updateData = [
                // Payment tracking
                'paymentHistory' => $paymentHistory,
                'paid_fees' => $newPaidAmount,
                'paidAmount' => $newPaidAmount,
                'remaining_fees' => $newRemainingBalance,
                'remainingAmount' => $newRemainingBalance,
                'fee_status' => $feeStatus,
                'last_payment_date' => $validated['payment_date'],
                
                // Ensure fee structure is saved
                'total_fees' => $totalFees,
                'gst_amount' => $gstAmount,
                'total_fees_inclusive_tax' => $totalFeesWithGST,
                
                // Update timestamps
                'updated_at' => now()
            ];
            
            $student->update($updateData);
            
            Log::info('  Payment processed successfully:', [
                'student_id' => $id,
                'student_name' => $student->name,
                'payment_amount' => $totalPayment,
                'new_paid_total' => $newPaidAmount,
                'new_balance' => $newRemainingBalance,
                'fee_status' => $feeStatus
            ]);
            
            // Prepare success message
            $message = "Payment of ₹" . number_format($totalPayment, 2) . " recorded successfully!";
            if ($newRemainingBalance > 0) {
                $message .= " Remaining balance: ₹" . number_format($newRemainingBalance, 2);
            } else {
                $message .= " All fees paid!";
            }
            
            return redirect()->route('student.pendingfees.pending')
                ->with('success', $message);
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Payment validation failed:', [
                'errors' => $e->errors()
            ]);
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Payment processing failed:', [
                'student_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Payment processing failed: ' . $e->getMessage())
                ->withInput();
        }
    }
}