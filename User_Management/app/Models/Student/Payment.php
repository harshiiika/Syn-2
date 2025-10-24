<?php

namespace App\Models\Student;

use MongoDB\Laravel\Eloquent\Model;

class Payment extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'payments';
    
    public $timestamps = true;

    protected $fillable = [
        // Student Reference
        'student_id',
        'student_name',
        'father_name',
        'contact_number',
        
        // Course Information
        'course_name',
        'course_type',
        'batch_name',
        'delivery_mode',
        
        // Payment Details
        'payment_date',
        'payment_method', // cash, online, cheque, card
        'payment_type', // single, installment
        'payment_amount',
        'installment_number',
        
        // Fee Breakdown
        'total_fees',
        'gst_amount',
        'other_charges',
        'other_charges_description',
        'grand_total',
        
        // Transaction Details
        'transaction_id',
        'reference_number',
        'bank_name',
        'cheque_number',
        'cheque_date',
        'card_last_four',
        'upi_id',
        
        // Status Tracking
        'payment_status', // pending, completed, failed, refunded
        'verification_status', // verified, unverified
        'verified_by',
        'verified_at',
        
        // Balance Information
        'previous_balance',
        'amount_paid',
        'remaining_balance',
        
        // Additional Information
        'remarks',
        'receipt_number',
        'session',
        'academic_year',
        'recorded_by',
        'branch',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'cheque_date' => 'date',
        'payment_amount' => 'float',
        'total_fees' => 'float',
        'gst_amount' => 'float',
        'other_charges' => 'float',
        'grand_total' => 'float',
        'previous_balance' => 'float',
        'amount_paid' => 'float',
        'remaining_balance' => 'float',
        'verified_at' => 'datetime',
    ];

    /**
     * Get payments for a specific student
     */
    public static function getStudentPayments($studentId)
    {
        return self::where('student_id', $studentId)
                   ->orderBy('payment_date', 'desc')
                   ->get();
    }

    /**
     * Get total paid amount for a student
     */
    public static function getTotalPaid($studentId)
    {
        return self::where('student_id', $studentId)
                   ->where('payment_status', 'completed')
                   ->sum('payment_amount');
    }

    /**
     * Get payments by date range
     */
    public static function getPaymentsByDateRange($startDate, $endDate)
    {
        return self::whereBetween('payment_date', [$startDate, $endDate])
                   ->orderBy('payment_date', 'desc')
                   ->get();
    }

    /**
     * Get payments by method
     */
    public static function getPaymentsByMethod($method)
    {
        return self::where('payment_method', $method)
                   ->orderBy('payment_date', 'desc')
                   ->get();
    }

    /**
     * Get today's payments
     */
    public static function getTodayPayments()
    {
        return self::whereDate('payment_date', today())
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    /**
     * Generate receipt number
     */
    public static function generateReceiptNumber()
    {
        $lastPayment = self::orderBy('created_at', 'desc')->first();
        
        if (!$lastPayment || !$lastPayment->receipt_number) {
            return 'RCP-' . date('Y') . '-0001';
        }
        
        // Extract number from last receipt
        preg_match('/\d+$/', $lastPayment->receipt_number, $matches);
        $lastNumber = isset($matches[0]) ? intval($matches[0]) : 0;
        $newNumber = $lastNumber + 1;
        
        return 'RCP-' . date('Y') . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get payment summary statistics
     */
    public static function getPaymentSummary($startDate = null, $endDate = null)
    {
        $query = self::where('payment_status', 'completed');
        
        if ($startDate && $endDate) {
            $query->whereBetween('payment_date', [$startDate, $endDate]);
        }
        
        $payments = $query->get();
        
        return [
            'total_amount' => $payments->sum('payment_amount'),
            'total_count' => $payments->count(),
            'cash_amount' => $payments->where('payment_method', 'cash')->sum('payment_amount'),
            'online_amount' => $payments->where('payment_method', 'online')->sum('payment_amount'),
            'cheque_amount' => $payments->where('payment_method', 'cheque')->sum('payment_amount'),
            'card_amount' => $payments->where('payment_method', 'card')->sum('payment_amount'),
        ];
    }

    /**
     * Verify payment
     */
    public function verifyPayment($verifiedBy)
    {
        $this->verification_status = 'verified';
        $this->verified_by = $verifiedBy;
        $this->verified_at = now();
        return $this->save();
    }

    /**
     * Mark payment as completed
     */
    public function markCompleted()
    {
        $this->payment_status = 'completed';
        return $this->save();
    }

    /**
     * Mark payment as failed
     */
    public function markFailed($reason = null)
    {
        $this->payment_status = 'failed';
        if ($reason) {
            $this->remarks = $reason;
        }
        return $this->save();
    }

    /**
     * Relationship: Get student from Pending model
     */
    public function pendingStudent()
    {
        return $this->belongsTo(Pending::class, 'student_id', '_id');
    }

    /**
     * Relationship: Get student from Onboard model
     */
    public function onboardedStudent()
    {
        return $this->belongsTo(Onboard::class, 'student_id', '_id');
    }

    /**
     * Scope for completed payments
     */
    public function scopeCompleted($query)
    {
        return $query->where('payment_status', 'completed');
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * Scope for verified payments
     */
    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }
}