<?php

namespace App\Models\Student;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Master\Batch;
use App\Models\Master\Courses;

class Onboard extends Model
{
    protected $connection = 'mongodb';
protected $collection = 'onboarded_students';    
    public $timestamps = true;
    
    protected $guarded = [];
    
    protected $fillable = [
        // All same fields as Student model
        'name', 'father', 'mother', 'dob', 
        'mobileNumber', 'fatherWhatsapp', 'motherContact', 'studentContact',
        'category', 'gender', 'fatherOccupation', 'fatherGrade', 'motherOccupation',
        'state', 'city', 'pinCode', 'address', 
        'belongToOtherCity', 'economicWeakerSection',
        'armyPoliceBackground', 'speciallyAbled',
        'course_type', 'courseType', 'courseName', 'deliveryMode',
        'medium', 'board', 'courseContent',
        'previousClass', 'previousMedium', 'schoolName',
        'previousBoard', 'passingYear', 'percentage',
        'isRepeater', 'scholarshipTest', 'lastBoardPercentage', 'competitionExam',
        'batchName', 'batchStartDate', 'batch_id', 'course_id',
        'email', 'alternateNumber', 'branch', 'session', 'status', 'admission_date',
        'eligible_for_scholarship', 'scholarship_name', 'total_fee_before_discount',
        'discretionary_discount', 'discretionary_discount_type', 'discretionary_discount_value',
        'discretionary_discount_reason', 'discount_percentage', 'discounted_fee',
        'fees_breakup', 'total_fees', 'gst_amount', 'total_fees_inclusive_tax',
        'single_installment_amount', 'installment_1', 'installment_2', 'installment_3',
        'fees_calculated_at',
        'paid_fees', 'remaining_fees', 'fee_status',
        'totalFees', 'paidAmount', 'remainingAmount', 'paymentHistory',
        'last_payment_date',
        'passport_photo', 'marksheet', 'caste_certificate', 'scholarship_proof',
        'secondary_marksheet', 'senior_secondary_marksheet',
        'onboardedAt', 'transferred_from', 'transferred_at', 'created_by', 'updated_by'
    ];
     /**
     * Treat MongoDB _id as string instead of ObjectId
     */
    // protected $keyType = 'string';
    // public $incrementing = false;

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
        'onboardedAt' => 'datetime',
        'transferred_at' => 'datetime',
    ];
    
    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id', '_id');
    }
    
    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id', '_id');
    }
}
// namespace App\Models\Student;

// use MongoDB\Laravel\Eloquent\Model;

// class Onboard extends Model
// {
//     protected $connection = 'mongodb';
//     protected $collection = 'onboarded_students';
    
//     public $timestamps = true;

//     // Allow mass assignment for ALL fields
//     protected $guarded = [];

//     protected $fillable = [
//         // Basic Details
//         'name',
//         'father',
//         'mother',
//         'dob',
//         'mobileNumber',
//         'fatherWhatsapp',
//         'motherContact',
//         'studentContact',
//         'category',
//         'gender',
//         'fatherOccupation',
//         'fatherGrade',
//         'motherOccupation',
        
//         // Address Details
//         'state',
//         'city',
//         'pinCode',
//         'address',
//         'belongToOtherCity',
//         'economicWeakerSection',
//         'armyPoliceBackground',
//         'speciallyAbled',
        
//         // Course Details
//         'course_type',
//         'course',
//         'courseType',
//         'courseName',
//         'deliveryMode',
//         'medium',
//         'board',
//         'courseContent',
        
//         // Academic Details
//         'previousClass',
//         'previousMedium',
//         'schoolName',
//         'previousBoard',
//         'passingYear',
//         'percentage',
        
//         // Scholarship Eligibility
//         'isRepeater',
//         'scholarshipTest',
//         'lastBoardPercentage',
//         'competitionExam',
        
//         // Batch Details
//         'batchName',
//         'batchStartDate',
        
//         // Metadata
//         'email',
//         'alternateNumber',
//         'branch',
//         'session',
//         'status',
//         'onboardedAt',
        
//         'eligible_for_scholarship',
//         'scholarship_name',
//         'total_fee_before_discount',
//         'discretionary_discount',
//         'discretionary_discount_type',
//         'discretionary_discount_value',
//         'discretionary_discount_reason',
//         'discount_percentage',
//         'discounted_fee',
//         'fees_breakup',
//         'total_fees',
//         'gst_amount',
//         'total_fees_inclusive_tax',
//         'single_installment_amount',
//         'installment_1',
//         'installment_2',
//         'installment_3',
//         'fees_calculated_at',
        
//         // Existing fee fields
//         'paid_fees',
//         'remaining_fees',
//         'fee_status',
//     ];

//     protected $casts = [
//         'dob' => 'date',
//         'batchStartDate' => 'date',
//         'onboardedAt' => 'datetime',
//         'percentage' => 'float',
//         'lastBoardPercentage' => 'float',
//         'total_fee_before_discount' => 'float',
//         'discount_percentage' => 'float',
//         'discounted_fee' => 'float',
//         'total_fees' => 'float',
//         'gst_amount' => 'float',
//         'total_fees_inclusive_tax' => 'float',
//         'single_installment_amount' => 'float',
//         'installment_1' => 'float',
//         'installment_2' => 'float',
//         'installment_3' => 'float',
//         'paid_fees' => 'float',
//         'remaining_fees' => 'float',
//         'fees_calculated_at' => 'datetime',
//     ];

//     /**
//      * Get all onboarded students
//      */
//     public static function getAllOnboarded()
//     {
//         return self::orderBy('onboardedAt', 'desc')->get();
//     }
// }