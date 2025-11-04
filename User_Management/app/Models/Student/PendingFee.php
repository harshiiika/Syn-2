<?php

namespace App\Models\Student;

use MongoDB\Laravel\Eloquent\Model;

class PendingFee extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'student_pending_fee';
    
    public $timestamps = true;
    protected $guarded = [];
    
    protected $fillable = [
        // Basic Details
        'name', 'father', 'mother', 'dob', 
        'mobileNumber', 'fatherWhatsapp', 'motherContact', 'studentContact',
        'category', 'gender', 'fatherOccupation', 'fatherGrade', 'motherOccupation',
        
        // Address Details
        'state', 'city', 'pinCode', 'address', 
        'belongToOtherCity', 'economicWeakerSection',
        'armyPoliceBackground', 'speciallyAbled',
        
        // Course Details
        'course_type', 'courseType', 'courseName', 'deliveryMode',
        'medium', 'board', 'courseContent',
        
        // Academic Details
        'previousClass', 'previousMedium', 'schoolName',
        'previousBoard', 'passingYear', 'percentage',
        
        // Scholarship Eligibility
        'isRepeater', 'scholarshipTest', 'lastBoardPercentage', 'competitionExam',
        
        // Batch Details
        'batchName', 'batchStartDate', 'batch_id', 'course_id',
        
        // Metadata
        'email', 'alternateNumber', 'branch', 'session', 'status', 'admission_date',
        
        // Scholarship & Fees
        'eligible_for_scholarship', 'scholarship_name', 'total_fee_before_discount',
        'discretionary_discount', 'discretionary_discount_type', 'discretionary_discount_value',
        'discretionary_discount_reason', 'discount_percentage', 'discounted_fee',
        'fees_breakup', 'total_fees', 'gst_amount', 'total_fees_inclusive_tax',
        'single_installment_amount', 'installment_1', 'installment_2', 'installment_3',
        'fees_calculated_at',
        
        // Payment Tracking
        'paid_fees', 'remaining_fees', 'fee_status',
        'totalFees', 'paidAmount', 'remainingAmount', 'paymentHistory',
        'last_payment_date',
        
        // Documents
        'passport_photo', 'marksheet', 'caste_certificate', 'scholarship_proof',
        'secondary_marksheet', 'senior_secondary_marksheet',
        
        // Transfer Audit
        'transferred_from', 'transfer_reason', 'transfer_date', 'transferred_at',
        'created_by', 'updated_by'
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
        'totalFees' => 'float',
        'paidAmount' => 'float',
        'remainingAmount' => 'float',
        'paymentHistory' => 'array',
        'fees_calculated_at' => 'datetime',
        'transferred_at' => 'datetime',
        'transfer_date' => 'datetime',
    ];
}