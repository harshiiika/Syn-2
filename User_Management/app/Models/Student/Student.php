<?php

namespace App\Models\Student;

use MongoDB\Laravel\Eloquent\Model;

class Student extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'students';
    
    // Status constants
    const STATUS_INQUIRY = 'inquiry';
    const STATUS_PENDING_FEES = 'pending_fees';
    const STATUS_ACTIVE = 'active';
    const STATUS_ONBOARDED = 'onboarded';

<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 119b3e2f306b00c0441094f32a236d5c2973aaed
    public $timestamps = true;

    // Allow mass assignment for ALL fields
    protected $guarded = [];

<<<<<<< HEAD
>>>>>>> f511f3813e8a0d0efe4af9d77513ecef0a386326
=======
>>>>>>> 119b3e2f306b00c0441094f32a236d5c2973aaed
    protected $fillable = [
        // Basic Details
        'name',
        'father',
        'mother',
        'dob',
        'mobileNumber',
<<<<<<< HEAD
<<<<<<< HEAD
        'alternateNumber',
=======
>>>>>>> f511f3813e8a0d0efe4af9d77513ecef0a386326
=======
>>>>>>> 119b3e2f306b00c0441094f32a236d5c2973aaed
        'fatherWhatsapp',
        'motherContact',
        'studentContact',
        'category',
        'gender',
        'fatherOccupation',
        'fatherGrade',
        'motherOccupation',
        
        // Address Details
        'state',
        'city',
        'pinCode',
        'address',
        'belongToOtherCity',
        'economicWeakerSection',
        'armyPoliceBackground',
        'speciallyAbled',
        
        // Course Details
        'course_type',
        'course',
<<<<<<< HEAD
<<<<<<< HEAD
        'courseName',
        'courseType',
=======
        'courseType',
        'courseName',
>>>>>>> f511f3813e8a0d0efe4af9d77513ecef0a386326
=======
        'courseType',
        'courseName',
>>>>>>> 119b3e2f306b00c0441094f32a236d5c2973aaed
        'deliveryMode',
        'medium',
        'board',
        'courseContent',
<<<<<<< HEAD
<<<<<<< HEAD
        'email',
        'branch',
=======
>>>>>>> f511f3813e8a0d0efe4af9d77513ecef0a386326
=======
>>>>>>> 119b3e2f306b00c0441094f32a236d5c2973aaed
        
        // Academic Details
        'previousClass',
        'previousMedium',
        'schoolName',
        'previousBoard',
        'passingYear',
        'percentage',
        
        // Scholarship Eligibility
        'isRepeater',
        'scholarshipTest',
        'lastBoardPercentage',
        'competitionExam',
        
<<<<<<< HEAD
<<<<<<< HEAD
        // Batch & Fees
        'batchName',
        'batchStartDate', // ADD THIS
        'status',
        'total_fees',
        'paid_fees',
        'paid_amount', // ADD THIS (alias for paid_fees)
        'remaining_fees',
        'fee_status',
        'admission_date',
        'session',
        'paymentHistory',
        'payment_history', // ADD THIS (alias for paymentHistory)
        
        // Transfer Metadata - ADD THESE
        'onboardedAt',
        'transferredToPendingFeesAt',
=======
=======
>>>>>>> 119b3e2f306b00c0441094f32a236d5c2973aaed
        // Batch Details
        'batchName',
        'batchStartDate',
        
        // Metadata
        'email',
        'alternateNumber',
        'branch',
        'session',
        'status',
        'admission_date',
        
        // ✅ SCHOLARSHIP & FEES DETAILS
        'eligible_for_scholarship',
        'scholarship_name',
        'total_fee_before_discount',
        'discretionary_discount',
        'discretionary_discount_type',
        'discretionary_discount_value',
        'discretionary_discount_reason',
        'discount_percentage',
        'discounted_fee',
        'fees_breakup',
        'total_fees',
        'gst_amount',
        'total_fees_inclusive_tax',
        'single_installment_amount',
        'installment_1',
        'installment_2',
        'installment_3',
        'fees_calculated_at',
        
        // Fee tracking fields
        'paid_fees',
        'remaining_fees',
        'fee_status',
<<<<<<< HEAD
>>>>>>> f511f3813e8a0d0efe4af9d77513ecef0a386326
=======
>>>>>>> 119b3e2f306b00c0441094f32a236d5c2973aaed
    ];

    protected $casts = [
        'dob' => 'date',
        'batchStartDate' => 'date',
        'admission_date' => 'datetime',
        'percentage' => 'float',
        'lastBoardPercentage' => 'float',
        'total_fee_before_discount' => 'float',
        'discount_percentage' => 'float',
        'discounted_fee' => 'float',
        'total_fees' => 'float',
        'gst_amount' => 'float',
        'total_fees_inclusive_tax' => 'float',
        'single_installment_amount' => 'float',
        'installment_1' => 'float',
        'installment_2' => 'float',
        'installment_3' => 'float',
        'paid_fees' => 'float',
        'remaining_fees' => 'float',
<<<<<<< HEAD
<<<<<<< HEAD
        'admission_date' => 'datetime',
        'dob' => 'date',
        'batchStartDate' => 'date', // ADD THIS
        'onboardedAt' => 'datetime', // ADD THIS
        'transferredToPendingFeesAt' => 'datetime', // ADD THIS
        'percentage' => 'float',
        'lastBoardPercentage' => 'float',
        'paymentHistory' => 'array',
        'payment_history' => 'array',
=======
        'fees_calculated_at' => 'datetime',
>>>>>>> f511f3813e8a0d0efe4af9d77513ecef0a386326
=======
        'fees_calculated_at' => 'datetime',
>>>>>>> 119b3e2f306b00c0441094f32a236d5c2973aaed
    ];

    /**
     * Get all pending fees students
     */
    public static function getPendingFeesStudents()
    {
        return self::where('status', self::STATUS_PENDING_FEES)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all active students
     */
    public static function getActiveStudents()
    {
        return self::where('status', self::STATUS_ACTIVE)
            ->orderBy('created_at', 'desc')
            ->get();
    }
<<<<<<< HEAD
<<<<<<< HEAD

    /**
     * Check if student has pending fees
     */
    public function hasPendingFees()
    {
        return $this->remaining_fees > 0;
    }

    /**
     * Get fee status badge color
     */
    public function getFeeStatusBadgeColor()
    {
        return match($this->fee_status) {
            'paid' => 'success',
            'partial' => 'warning',
            'pending' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get fee status text
     */
    public function getFeeStatusText()
    {
        return match($this->fee_status) {
            'paid' => 'Fully Paid',
            'partial' => 'Partially Paid',
            'pending' => 'Pending',
            default => 'Unknown'
        };
    }

    /**
     * Create a new student
     */
    public static function createStudent($data)
    {
        return self::create($data);
    }

    /**
     * Update student details
     */
    public function updateStudent($data)
    {
        return $this->update($data);
    }

    /**
     * Record a payment
     */
    public function recordPayment($amount)
    {
        $this->paid_fees += $amount;
        $this->remaining_fees -= $amount;

        // Update status
        if ($this->remaining_fees <= 0) {
            $this->status = self::STATUS_ACTIVE;
            $this->fee_status = 'paid';
            $this->remaining_fees = 0;
        } elseif ($this->paid_fees > 0) {
            $this->fee_status = 'partial';
        }

        return $this->save();
    }

    /**
     * Get payment percentage
     */
    public function getPaymentPercentage()
    {
        if ($this->total_fees == 0) {
            return 0;
        }
        return round(($this->paid_fees / $this->total_fees) * 100, 2);
    }

    /**
     * Scope for searching students
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('mobileNumber', 'like', "%{$term}%")
              ->orWhere('father', 'like', "%{$term}%");
        });
    }

    /**
     * Get students for "Pending Inquiries" tab (incomplete profiles)
     */
    public static function getPendingStudents()
    {
        return self::where('status', self::STATUS_INQUIRY)
                   ->orWhere(function($query) {
                       $query->whereNull('status')
                             ->where('remaining_fees', '>', 0);
                   })
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    /**
     * Get students for "Pending Fees Students" page (transferred from onboard)
     * UPDATED: Now fetches students with pending_fees status
     */
    public static function getPendingFeesStudents()
    {
        return self::where(function($query) {
                    $query->where('status', self::STATUS_PENDING_FEES)
                          ->orWhere('remaining_fees', '>', 0);
                })
                ->orderBy('transferredToPendingFeesAt', 'desc')
                ->get();
    }

    /**
     * Get students for "Onboarding Students" tab (from onboarded_students collection)
     * NOTE: This is handled by the Onboard model, not this Student model
     */
    public static function getActiveStudents()
    {
        return self::where('remaining_fees', '<=', 0)
                   ->where('status', self::STATUS_ACTIVE)
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    /**
     * NEW: Check if student profile is complete
     */
    public function isProfileComplete()
    {
        $requiredFields = [
            'name', 'father', 'mother', 'dob', 'mobileNumber',
            'category', 'gender', 'state', 'city', 'pinCode',
            'courseName', 'deliveryMode', 'medium', 'board',
            'batchName'
        ];

        foreach ($requiredFields as $field) {
            if (empty($this->$field)) {
                return false;
            }
        }

        return true;
    }

    /**
     * NEW: Transfer from Pending Inquiry to Onboarded (when profile is complete)
     */
    public function transferToOnboarded()
    {
        // This method would create an Onboard record and delete this inquiry
        // Implemented in PendingFeesController->update() method
    }
=======
>>>>>>> f511f3813e8a0d0efe4af9d77513ecef0a386326
=======
>>>>>>> 119b3e2f306b00c0441094f32a236d5c2973aaed
}