<?php

namespace App\Models\Student;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Master\Batch;
use App\Models\Master\Courses;

class SMstudents extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 's_mstudents'; // Final students table
    
    public $timestamps = true;
    protected $guarded = [];
    
    protected $fillable = [
        'roll_no',
        'student_name',
        'name',                // Alias for student_name
        'email',
        'phone',
        'shift_id',

        // Basic Details (from onboarding)
        'father',
        'father_name',         // Alias for father
        'mother',
        'mother_name',         // Alias for mother
        'dob',
        'father_contact',
        'father_whatsapp',
        'mother_contact',
        'student_contact',
        'category',
        'gender',
        'father_occupation',
        'father_grade',
        'mother_occupation',
        
        // Address Details
        'state',
        'city',
        'pincode',
        'address',
        'belongs_other_city',
        'economic_weaker_section',
        'army_police_background',
        'specially_abled',
        
        // Academic Details
        'previous_class',
        'academic_medium',
        'school_name',
        'academic_board',
        'passing_year',
        'percentage',
        
        // Scholarship Eligibility
        'is_repeater',
        'scholarship_test',
        'last_board_percentage',
        'competition_exam',
        
        // Course & Batch
        'batch_id',
        'batch_name',
        'course_id',
        'course_name',
        'course_type',
        'delivery_mode',
        'course_content',
        'medium',
        'board',
        
        // Scholarship Details
        'eligible_for_scholarship',
        'scholarship_id',
        'scholarship_name',
        'total_fee_before_discount',
        'discretionary_discount',
        'discretionary_discount_type',
        'discretionary_discount_value',
        'discretionary_discount_reason',
        'discount_percentage',
        'discount_amount',
        'discounted_fee',
        
        // Fees Details
        'fees_breakup',
        'total_fees',
        'gst_amount',
        'total_fees_inclusive_tax',
        'single_installment_amount',
        'installment_1',
        'installment_2',
        'installment_3',
        'paid_fees',
        'paid_amount',         // Alias for paid_fees
        'remaining_fees',
        'pending_amount',      // Alias for remaining_fees
        'fee_status',          // CRITICAL: Paid, Pending, 2nd Installment due, etc.
        'fee_installments',    // Number of installments
        
        // Documents (Base64 encoded)
        'passport_photo',
        'marksheet',
        'caste_certificate',
        'scholarship_proof',
        'secondary_marksheet',
        'senior_secondary_marksheet',
        
        // Status & Metadata
        'status',
        'admission_date',
        'transferred_from',
        'transferred_at',
        'created_by',
        'updated_by',
        'session',
        'branch',
        'branch_id',
    ];
    
    protected $casts = [
        'dob' => 'date',
        'admission_date' => 'datetime',
        'transferred_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'percentage' => 'float',
        'last_board_percentage' => 'float',
        'total_fee_before_discount' => 'decimal:2',
        'discount_percentage' => 'float',
        'discount_amount' => 'decimal:2',
        'discounted_fee' => 'decimal:2',
        'total_fees' => 'decimal:2',
        'gst_amount' => 'decimal:2',
        'total_fees_inclusive_tax' => 'decimal:2',
        'single_installment_amount' => 'decimal:2',
        'installment_1' => 'decimal:2',
        'installment_2' => 'decimal:2',
        'installment_3' => 'decimal:2',
        'paid_fees' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_fees' => 'decimal:2',
        'pending_amount' => 'decimal:2',
        'fee_installments' => 'integer',
    ];

    /**
     * Relationship: Student belongs to a Batch
     */
    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id', '_id');
    }

    /**
     * Relationship: Student belongs to a Course
     */
    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id', '_id');
    }

    /**
     * Relationship: Student has many Fee Transactions
     */
    public function feeTransactions()
    {
        return $this->hasMany(\App\Models\FeeTransaction::class, 'student_id', '_id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    public function scopeByBatch($query, $batchId)
    {
        return $query->where('batch_id', $batchId);
    }

    public function scopeByFeeStatus($query, $status)
    {
        return $query->where('fee_status', $status);
    }

    public function scopePendingFees($query)
    {
        return $query->whereIn('fee_status', [
            '2nd Installment due',
            '3rd Installment due',
            'Pending'
        ]);
    }

    /**
     * Accessors (for compatibility with fees management)
     */
    public function getNameAttribute()
    {
        return $this->attributes['name'] ?? $this->student_name;
    }

    public function getFatherNameAttribute()
    {
        return $this->attributes['father_name'] ?? $this->father;
    }

    public function getPaidAmountAttribute()
    {
        return $this->attributes['paid_amount'] ?? $this->paid_fees ?? 0;
    }

    public function getPendingAmountAttribute()
    {
        return $this->attributes['pending_amount'] ?? $this->remaining_fees ?? 0;
    }

    /**
     * Mutators (to ensure consistency)
     */
    public function setStudentNameAttribute($value)
    {
        $this->attributes['student_name'] = $value;
        $this->attributes['name'] = $value;
    }

    public function setFatherAttribute($value)
    {
        $this->attributes['father'] = $value;
        $this->attributes['father_name'] = $value;
    }

    public function setPaidFeesAttribute($value)
    {
        $this->attributes['paid_fees'] = $value;
        $this->attributes['paid_amount'] = $value;
    }

    public function setRemainingFeesAttribute($value)
    {
        $this->attributes['remaining_fees'] = $value;
        $this->attributes['pending_amount'] = $value;
    }

    /**
     * Helper: Calculate total paid amount from transactions
     */
    public function calculatePaidAmount()
    {
        return $this->feeTransactions()->sum('amount');
    }

    /**
     * Helper: Calculate pending amount
     */
    public function calculatePendingAmount()
    {
        $paid = $this->calculatePaidAmount();
        return max(0, ($this->total_fees ?? 0) - $paid);
    }

    /**
     * Helper: Update fee status based on payments
     */
    public function updateFeeStatus()
    {
        $totalPaid = $this->calculatePaidAmount();
        $totalFees = $this->total_fees ?? 0;
        
        if ($totalPaid >= $totalFees) {
            $this->fee_status = 'Paid';
        } else {
            $installments = $this->fee_installments ?? 1;
            $perInstallment = $totalFees / $installments;
            
            if ($totalPaid < $perInstallment) {
                $this->fee_status = '2nd Installment due';
            } elseif ($totalPaid < ($perInstallment * 2)) {
                $this->fee_status = '3rd Installment due';
            } else {
                $this->fee_status = 'Pending';
            }
        }
        
        $this->pending_amount = $this->calculatePendingAmount();
        $this->paid_amount = $totalPaid;
        $this->save();
    }
}