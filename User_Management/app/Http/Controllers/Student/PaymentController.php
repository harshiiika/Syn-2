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
    /**
     * Show payment page for a student
     */
    public function showPaymentPage($id)
    {
        try {
            Log::info('Loading payment page for student', ['id' => $id]);
            
            // Try to find student in Pending collection first, then Onboard
            $student = Pending::where('_id', $id)->first();
            $isPending = true;
            
            if (!$student) {
                $student = Onboard::where('_id', $id)->first();
                $isPending = false;
            }
            
            if (!$student) {
                Log::error("Student not found", ['id' => $id]);
                return redirect()->route('student.pendingfees.pending')
                    ->with('error', 'Student not found');
            }
            
            Log::info('Student found for payment', [
                'id' => $id,
                'name' => $student->name,
                'collection' => $isPending ? 'pending' : 'onboard',
                'total_fees' => $student->total_fees_inclusive_tax ?? $student->total_fees ?? 0,
                'paid_amount' => $student->paidAmount ?? 0,
                'remaining_amount' => $student->remainingAmount ?? 0
            ]);
            
            // Calculate payment details
            $totalFees = $student->total_fees ?? 100000; // Base fees before GST
            $gstRate = 0.18;
            $gstAmount = $totalFees * $gstRate;
            $totalFeesWithGST = $totalFees + $gstAmount;
            
            // Calculate installment amount (40% of total with GST)
            $firstInstallment = $totalFeesWithGST * 0.40;
            
            // Get previous payments
            $previousPayments = Payment::where('student_id', (string)$id)->get();
            $totalPaid = Payment::where('student_id', (string)$id)
                              ->where('payment_status', 'completed')
                              ->sum('payment_amount') ?? 0;
            
            $remainingBalance = $totalFeesWithGST - $totalPaid;
            
            Log::info('Payment calculations', [
                'total_fees' => $totalFees,
                'gst_amount' => $gstAmount,
                'total_with_gst' => $totalFeesWithGST,
                'first_installment' => $firstInstallment,
                'total_paid' => $totalPaid,
                'remaining_balance' => $remainingBalance
            ]);
            
            return view('student.payment.pay', compact(
                'student', 
                'previousPayments', 
                'totalPaid',
                'totalFees',
                'gstAmount',
                'totalFeesWithGST',
                'firstInstallment',
                'remainingBalance'
            ));
            
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
     * Process payment
     */
    public function processPayment(Request $request, $id)
    {
        DB::beginTransaction();
        
        try {
            Log::info('=== PROCESSING PAYMENT ===', [
                'student_id' => $id,
                'data' => $request->all()
            ]);

            // Validate payment request
            $validated = $request->validate([
                'payment_type' => 'required|in:single,installment',
                'payment_date' => 'required|date',
                'payment_method' => 'required|in:cash,online,cheque,card',
                'payment_amount' => 'required|numeric|min:1',
                'total_fees' => 'required|numeric|min:0',
                'other_charges' => 'nullable|numeric|min:0',
                'transaction_id' => 'nullable|string',
                'reference_number' => 'nullable|string',
                'bank_name' => 'nullable|string',
                'cheque_number' => 'nullable|string',
                'cheque_date' => 'nullable|date',
                'upi_id' => 'nullable|string',
                'remarks' => 'nullable|string',
            ]);

            // Find student in Pending first, then Onboard
            $student = Pending::where('_id', $id)->first();
            $isPending = true;
            
            if (!$student) {
                $student = Onboard::where('_id', $id)->first();
                $isPending = false;
            }
            
            if (!$student) {
                throw new \Exception('Student not found');
            }
            
            Log::info('Student found for payment processing', [
                'name' => $student->name,
                'collection' => $isPending ? 'pending' : 'onboard'
            ]);

            // Calculate fees
            $totalFees = $validated['total_fees'];
            $gstRate = 0.18;
            $gstAmount = $totalFees * $gstRate;
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
                'total_fees' => $totalFees,
                'gst_amount' => $gstAmount,
                'other_charges' => $otherCharges,
                'grand_total' => $grandTotal,
                'payment_amount' => $paymentAmount,
                'previous_paid' => $previousPaid,
                'total_paid_now' => $totalPaidNow,
                'remaining' => $remainingAmount
            ]);

            // Create payment record
            $paymentRecord = Payment::create([
                'student_id' => (string)$student->_id,
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
                'session' => '2025-2026',
                'academic_year' => date('Y'),
                'recorded_by' => Auth::user()->name ?? 'Admin',
                'branch' => $student->branch ?? 'Main Branch',
            ]);

            Log::info('Payment record created', ['payment_id' => $paymentRecord->_id]);

            // Update payment history in student record
            $paymentHistory = $student->paymentHistory ?? [];
            $paymentHistory[] = [
                'payment_id' => (string)$paymentRecord->_id,
                'amount' => $paymentAmount,
                'date' => $validated['payment_date'],
                'method' => $validated['payment_method'],
                'type' => $validated['payment_type'],
                'receipt_number' => $receiptNumber,
                'recorded_at' => now()->toDateTimeString()
            ];

            // Update student fees information
            $updateData = [
                'totalFees' => $grandTotal,
                'paidAmount' => $totalPaidNow,
                'remainingAmount' => max(0, $remainingAmount),
                'paymentHistory' => $paymentHistory,
                'fee_status' => $remainingAmount <= 0 ? 'paid' : 'partial',
            ];

            Log::info('Updating student with payment data', $updateData);

            // Check if full payment is made
            if ($remainingAmount <= 0) {
                Log::info('FULL PAYMENT - Moving to Onboard');
                
                if ($isPending) {
                    // Move from Pending to Onboard
                    $onboardData = $student->toArray();
                    unset($onboardData['_id']);
                    
                    $onboardData = array_merge($onboardData, $updateData);
                    $onboardData['paymentStatus'] = 'fully_paid';
                    $onboardData['onboardedAt'] = now();
                    
                    $onboardedStudent = Onboard::create($onboardData);
                    
                    Log::info('Student moved to Onboard', [
                        'onboard_id' => $onboardedStudent->_id
                    ]);
                    
                    $student->delete();
                    
                    DB::commit();
                    
                    return redirect()->route('student.onboard.onboard')
                        ->with('success', "Payment completed! Student onboarded. Receipt: {$receiptNumber}");
                } else {
                    // Already in Onboard, just update
                    $student->update($updateData);
                    
                    DB::commit();
                    
                    return redirect()->route('student.onboard.onboard')
                        ->with('success', "Payment completed! Receipt: {$receiptNumber}");
                }
            } else {
                // PARTIAL PAYMENT
                Log::info('PARTIAL PAYMENT - Updating student');
                
                $student->update($updateData);
                
                DB::commit();
                
                $redirectRoute = $isPending ? 'student.pendingfees.pending' : 'student.onboard.onboard';
                
                return redirect()->route($redirectRoute)
                    ->with('success', "Payment of ₹" . number_format($paymentAmount, 2) . " recorded. Receipt: {$receiptNumber}. Remaining: ₹" . number_format($remainingAmount, 2));
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error:', ['errors' => $e->errors()]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
                
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
            $student = Pending::find($id);
            $isOnboarded = false;
            
            if (!$student) {
                $student = Onboard::findOrFail($id);
                $isOnboarded = true;
            }
            
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
            
            return view('student.payment.receipt', compact('payment'));
            
        } catch (\Exception $e) {
            Log::error("Failed to generate receipt: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Receipt not found');
        }
    }
}