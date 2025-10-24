<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student\Pending;
use App\Models\Student\Onboard;
use App\Models\Student\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    
    public function showPaymentPage($id)
{
    try {
        Log::info('Attempting to load payment page', ['id' => $id]);
        
        // Find student using where clause instead of findOrFail
        $student = Pending::where('_id', $id)->first();
        
        if (!$student) {
            Log::error("Student not found", ['id' => $id]);
            return redirect()->route('student.pendingfees.pending')
                ->with('error', 'Student not found');
        }
        
        Log::info('Student found', [
            'id' => $id,
            'name' => $student->name,
            'student_data' => $student->toArray()
        ]);
        
        // Calculate previous payments
        $previousPayments = Payment::where('student_id', (string)$id)->get();
        $totalPaid = Payment::where('student_id', (string)$id)
                          ->where('payment_status', 'completed')
                          ->sum('payment_amount') ?? 0;
        
        Log::info('Payment calculations', [
            'previous_payments_count' => $previousPayments->count(),
            'total_paid' => $totalPaid
        ]);
        
        return view('student.payment.pay', compact('student', 'previousPayments', 'totalPaid'));
        
    } catch (\Exception $e) {
        Log::error("Payment page error", [
            'id' => $id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->route('student.pendingfees.pending')
            ->with('error', 'Error loading payment page: ' . $e->getMessage());
    }
}

    /**
     * Process payment - both installment and full payment
     */
    public function processPayment(Request $request, $id)
    {
        DB::beginTransaction();
        
        try {
            Log::info('Processing payment', [
                'student_id' => $id,
                'data' => $request->all()
            ]);

            // Validate the payment request
            $validated = $request->validate([
                'payment_type' => 'required|in:single,installment',
                'payment_date' => 'required|date',
                'payment_method' => 'required|in:cash,online,cheque,card',
                'payment_amount' => 'required|numeric|min:1',
                'total_fees' => 'required|numeric|min:0',
                'gst_amount' => 'nullable|numeric|min:0',
                'other_charges' => 'nullable|numeric|min:0',
                'other_charges_description' => 'nullable|string',
                
                // Transaction details based on payment method
                'transaction_id' => 'nullable|string',
                'reference_number' => 'nullable|string',
                'bank_name' => 'nullable|string',
                'cheque_number' => 'nullable|string',
                'cheque_date' => 'nullable|date',
                'upi_id' => 'nullable|string',
                
                'remarks' => 'nullable|string',
                'session' => 'nullable|string',
            ]);

            // Find the student in Pending collection
            $student = Pending::findOrFail($id);
            
            Log::info('Student found', ['name' => $student->name]);

            // Calculate fees
            $totalFees = $validated['total_fees'];
            $gstAmount = $validated['gst_amount'] ?? ($totalFees * 0.18);
            $otherCharges = $validated['other_charges'] ?? 0;
            $grandTotal = $totalFees + $gstAmount + $otherCharges;
            $paymentAmount = $validated['payment_amount'];
            
            // Get previous payments
            $previousPaid = Payment::getTotalPaid($id);
            $totalPaidNow = $previousPaid + $paymentAmount;
            $remainingAmount = $grandTotal - $totalPaidNow;
            
            // Count installment number
            $installmentNumber = Payment::where('student_id', $id)->count() + 1;
            
            // Generate receipt number
            $receiptNumber = Payment::generateReceiptNumber();
            
            Log::info('Payment calculation', [
                'grand_total' => $grandTotal,
                'payment_amount' => $paymentAmount,
                'previous_paid' => $previousPaid,
                'total_paid_now' => $totalPaidNow,
                'remaining' => $remainingAmount
            ]);

            // Create payment record
            $paymentRecord = Payment::create([
                'student_id' => $student->_id,
                'student_name' => $student->name,
                'father_name' => $student->father,
                'contact_number' => $student->mobileNumber,
                
                'course_name' => $student->courseName,
                'course_type' => $student->courseType,
                'batch_name' => $student->batchName,
                'delivery_mode' => $student->deliveryMode,
                
                'payment_date' => $validated['payment_date'],
                'payment_method' => $validated['payment_method'],
                'payment_type' => $validated['payment_type'],
                'payment_amount' => $paymentAmount,
                'installment_number' => $installmentNumber,
                
                'total_fees' => $totalFees,
                'gst_amount' => $gstAmount,
                'other_charges' => $otherCharges,
                'other_charges_description' => $validated['other_charges_description'] ?? null,
                'grand_total' => $grandTotal,
                
                'transaction_id' => $validated['transaction_id'] ?? null,
                'reference_number' => $validated['reference_number'] ?? null,
                'bank_name' => $validated['bank_name'] ?? null,
                'cheque_number' => $validated['cheque_number'] ?? null,
                'cheque_date' => $validated['cheque_date'] ?? null,
                'upi_id' => $validated['upi_id'] ?? null,
                
                'payment_status' => 'completed',
                'verification_status' => 'verified',
                'verified_by' => Auth::user()->name ?? 'Admin',
                'verified_at' => now(),
                
                'previous_balance' => $remainingAmount + $paymentAmount,
                'amount_paid' => $paymentAmount,
                'remaining_balance' => $remainingAmount,
                
                'remarks' => $validated['remarks'] ?? null,
                'receipt_number' => $receiptNumber,
                'session' => $validated['session'] ?? '2025-2026',
                'academic_year' => date('Y'),
                'recorded_by' => Auth::user()->name ?? 'Admin',
                'branch' => $student->branch ?? 'Main Branch',
            ]);

            // Update payment history in student record
            $paymentHistory = $student->paymentHistory ?? [];
            $paymentHistory[] = [
                'payment_id' => $paymentRecord->_id,
                'amount' => $paymentAmount,
                'date' => $validated['payment_date'],
                'method' => $validated['payment_method'],
                'type' => $validated['payment_type'],
                'receipt_number' => $receiptNumber,
                'recorded_at' => now()
            ];

            // Check if full payment is made
            if ($remainingAmount <= 0) {
                // FULL PAYMENT - Move to Onboard collection
                $onboardData = $student->toArray();
                unset($onboardData['_id']); // Remove old ID
                
                $onboardData['totalFees'] = $grandTotal;
                $onboardData['paidAmount'] = $totalPaidNow;
                $onboardData['remainingAmount'] = 0;
                $onboardData['paymentStatus'] = 'fully_paid';
                $onboardData['paymentHistory'] = $paymentHistory;
                $onboardData['onboardedAt'] = now();
                
                // Create in Onboard collection
                $onboardedStudent = Onboard::create($onboardData);
                
                // Update payment record with onboarded status
                $paymentRecord->update([
                    'remarks' => ($paymentRecord->remarks ?? '') . ' [Student Onboarded]'
                ]);
                
                // Delete from Pending collection
                $student->delete();
                
                DB::commit();
                
                Log::info('Student fully paid and onboarded', [
                    'student_id' => $id,
                    'student_name' => $student->name,
                    'receipt_number' => $receiptNumber
                ]);
                
                return redirect()->route('student.onboard')
                    ->with('success', "Payment completed! Student onboarded successfully. Receipt: {$receiptNumber}");
                    
            } else {
                // PARTIAL PAYMENT - Update in Pending collection
                $student->update([
                    'totalFees' => $grandTotal,
                    'paidAmount' => $totalPaidNow,
                    'remainingAmount' => $remainingAmount,
                    'paymentStatus' => 'partial',
                    'paymentHistory' => $paymentHistory
                ]);
                
                DB::commit();
                
                Log::info('Partial payment recorded', [
                    'student_id' => $id,
                    'amount_paid' => $paymentAmount,
                    'remaining' => $remainingAmount,
                    'receipt_number' => $receiptNumber
                ]);
                
                return redirect()->route('student.pendingfees.pending')
                    ->with('success', "Payment of ₹{$paymentAmount} recorded successfully. Receipt: {$receiptNumber}. Remaining: ₹{$remainingAmount}");
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error:', ['errors' => $e->errors()]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            Log::error('Student not found:', ['id' => $id]);
            return redirect()->route('student.pendingfees.pending')
                ->with('error', 'Student not found');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment processing failed:', [
                'student_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Payment failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * View payment history for a student
     */
    public function viewHistory($id)
    {
        try {
            // Check in both Pending and Onboard collections
            $student = Pending::find($id);
            $isOnboarded = false;
            
            if (!$student) {
                $student = Onboard::findOrFail($id);
                $isOnboarded = true;
            }
            
            // Get all payment records from Payment model
            $payments = Payment::getStudentPayments($id);
            $totalPaid = Payment::getTotalPaid($id);
            
            return view('student.payment.history', compact('student', 'payments', 'totalPaid', 'isOnboarded'));
            
        } catch (\Exception $e) {
            Log::error("Failed to load payment history: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Student not found');
        }
    }

    /**
     * Download payment receipt
     */
    public function downloadReceipt($paymentId)
    {
        try {
            $payment = Payment::findOrFail($paymentId);
            
            // Generate PDF receipt (you can use dompdf or similar)
            // For now, return a view
            return view('student.payment.receipt', compact('payment'));
            
        } catch (\Exception $e) {
            Log::error("Failed to generate receipt: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Receipt not found');
        }
    }
}