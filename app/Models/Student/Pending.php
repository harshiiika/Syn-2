<?php

namespace App\Models\Student;

use MongoDB\Laravel\Eloquent\Model;

class Pending extends Model
{
     protected $connection = 'mongodb';
    protected $collection = 'student_pending';
    
    public $timestamps = true;
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
        'studentContact',
        'category',
        'gender',
        
        // 🔥 CRITICAL: Parent occupation details
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
        'courseType',
        'courseName',
        'deliveryMode',
        'delivery_mode',
        'courseContent',
        'course_content',
        
        // 🔥 CRITICAL: Medium and Board
        'medium',
        'board',
        
        // 🔥 CRITICAL: Academic Details
        'previousClass',
        'previousMedium',
        'schoolName',
        'previousBoard',
        'passingYear',
        'percentage',
        
        // 🔥 CRITICAL: Scholarship Eligibility
        'isRepeater',
        'scholarshipTest',
        'lastBoardPercentage',
        'competitionExam',
        
        // Batch Details
        'batchName',
        'batch',
        'batch_id',
        'course_id',
        'course',
        
        // Scholarship & Fees Details
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
        
        // Metadata
        'branch',
        'session',
        'status',
        'transferred_from_inquiry',
        'inquiry_id',
        'transferred_at',
        'created_by',
        'updated_by',
        'history',
        
        // Additional fields for pending students
        'email',
        'alternateNumber',
        'admission_date',
    ];

    protected $casts = [
        'dob' => 'date',
        'percentage' => 'float',
        'lastBoardPercentage' => 'float',
        'total_fee_before_discount' => 'float',
        'discount_percentage' => 'float',
        'discretionary_discount_value' => 'float',
        'discounted_fee' => 'float',
        'total_fees' => 'float',
        'gst_amount' => 'float',
        'total_fees_inclusive_tax' => 'float',
        'single_installment_amount' => 'float',
        'installment_1' => 'float',
        'installment_2' => 'float',
        'installment_3' => 'float',
        'fees_calculated_at' => 'datetime',
        'transferred_at' => 'datetime',
        'admission_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'history' => 'array',
    ];
    
    public function batch()
    {
        return $this->belongsTo(\App\Models\Master\Batch::class, 'batch_id', '_id');
    }
    
    public function course()
    {
        return $this->belongsTo(\App\Models\Master\Courses::class, 'course_id', '_id');
    }
}