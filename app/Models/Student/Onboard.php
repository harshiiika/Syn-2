<?php

namespace App\Models\Student;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Master\Batch;
use App\Models\Master\Courses;

class Onboard extends Model
{
    protected $connection = 'mongodb';
    
    protected $collection = 'student_onboard'; // NOT 'students'
    
    public $timestamps = true;
    protected $guarded = [];
    
    protected $fillable = [
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
        'passport_photo', 'marksheet', 'caste_certificate', 'scholarship_proof',
        'secondary_marksheet', 'senior_secondary_marksheet',
        'onboardedAt', 'transferred_from', 'transferred_at', 'created_by', 'updated_by',
        'history', //   ADDED - This allows history field to be saved
        'alternateMobileNumber' //   ADDED - This was in your tracked fields but missing here
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
        'fees_calculated_at' => 'datetime',
        'onboardedAt' => 'datetime',
        'transferred_at' => 'datetime',
        'history' => 'array', //   ADDED - Cast history as array for MongoDB
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