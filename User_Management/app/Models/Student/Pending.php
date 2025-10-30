<?php

namespace App\Models\Student;

use MongoDB\Laravel\Eloquent\Model;

class Pending extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'pending_fees_students'; // This is the collection for pending fees
    
    public $timestamps = true;
<<<<<<< HEAD
    
    protected $fillable = [
        // Basic Details
        'name',
        'father',
        'mother',
        'dob',
        'mobileNumber',
        'fatherWhatsapp',
        'motherContact',
=======
    
    // Allow mass assignment for ALL fields
    protected $guarded = [];
    
    protected $fillable = [
        // Basic Details
        'name', 
        'father', 
        'mother', 
        'dob', 
        'mobileNumber', 
        'fatherWhatsapp', 
        'motherContact', 
>>>>>>> f511f3813e8a0d0efe4af9d77513ecef0a386326
        'studentContact',
        'category',
        'gender',
        'fatherOccupation',
        'fatherGrade',
        'motherOccupation',
        
        // Address Details
<<<<<<< HEAD
        'state',
        'city',
        'pinCode',
        'address',
        'belongToOtherCity',
        'economicWeakerSection',
        'armyPoliceBackground',
        'speciallyAbled',
        
        // Course Details
        'courseType',
        'courseName',
        'deliveryMode',
        'medium',
        'board',
        'courseContent',
        
        // Academic Details
        'previousClass',
        'previousMedium',
        'schoolName',
        'previousBoard',
        'passingYear',
        'percentage',
        
        // Additional Details
        'isRepeater',
        'scholarshipTest',
        'lastBoardPercentage',
        'competitionExam',
=======
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
        'courseType', 
        'courseName', 
        'deliveryMode',
        'medium', 
        'board', 
        'courseContent', 
        
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
>>>>>>> f511f3813e8a0d0efe4af9d77513ecef0a386326
        
        // Batch Details
        'batchName',
        'batchStartDate',
        
        // Metadata
        'email',
        'alternateNumber',
        'branch',
        'session',
<<<<<<< HEAD
        'onboardedAt',
        'transferredToPendingFeesAt',
        
        // Fees Details (for pending fees)
        'totalFees',
        'paidAmount',
        'remainingAmount',
        'paymentHistory'
    ];

    protected $casts = [
        'dob' => 'date',
        'batchStartDate' => 'date',
        'onboardedAt' => 'datetime',
        'transferredToPendingFeesAt' => 'datetime',
        'percentage' => 'float',
        'lastBoardPercentage' => 'float',
        'totalFees' => 'float',
        'paidAmount' => 'float',
        'remainingAmount' => 'float',
        'paymentHistory' => 'array',
    ];
=======
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
        'totalFees',
        'paidAmount',
        'remainingAmount',
        'paymentHistory',
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
        'fees_calculated_at' => 'datetime',
        'totalFees' => 'float',
        'paidAmount' => 'float',
        'remainingAmount' => 'float',
        'paymentHistory' => 'array',
    ];
    
    // Add this to help with debugging
    protected static function boot()
    {
        parent::boot();
        
        static::updating(function ($model) {
            \Log::info('Pending model updating event fired for: ' . $model->name);
        });
        
        static::updated(function ($model) {
            \Log::info('Pending model updated event fired for: ' . $model->name);
        });
    }
>>>>>>> f511f3813e8a0d0efe4af9d77513ecef0a386326
}