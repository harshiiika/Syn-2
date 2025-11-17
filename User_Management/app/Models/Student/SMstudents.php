<?php

namespace App\Models\Student;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Master\Batch;
use App\Models\Master\Courses;

class SMstudents extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 's_mstudents';
    
    public $timestamps = true;
    protected $guarded = [];
    
    protected $fillable = [
        // ✅ PRIMARY FIELDS (used in view)
        'roll_no',
        'student_name',
        'email',
        'phone',
        'shift_id', 

        // Basic Details (from onboarding)
        'father_name',
        'mother_name',
        'dob',
        'father_contact',
        'father_whatsapp',
        'mother_contact',
        'category',
        'gender',
        'father_occupation',
        'mother_occupation',
        'state',
        'city',
        'pincode',
        'address',
        'belongs_other_city',
        'economic_weaker_section',
        'army_police_background',
        'specially_abled',
        'previous_class',
        'academic_medium',
        'school_name',
        'academic_board',
        'passing_year',
        'percentage',
        'is_repeater',
        'scholarship_test',
        'last_board_percentage',
        'competition_exam',
        'batch_id',
        'batch_name',
        'course_id',
        'course_name',
        'course_type',
        'delivery_mode',
        'course_content',
        'medium',
        'board',
        
        // ✅ ALTERNATE FIELD NAMES (from Pending table - for compatibility)
        'name',                  // maps to student_name
        'father',                // maps to father_name
        'mother',                // maps to mother_name
        'mobileNumber',          // maps to father_contact
        'fatherWhatsapp',        // maps to father_whatsapp
        'motherContact',         // maps to mother_contact
        'studentContact',        // maps to phone
        'pinCode',               // maps to pincode
        'belongToOtherCity',     // maps to belongs_other_city
        'economicWeakerSection', // maps to economic_weaker_section
        'armyPoliceBackground',  // maps to army_police_background
        'speciallyAbled',        // maps to specially_abled
        'courseType',            // maps to course_type
        'courseName',            // maps to course_name
        'deliveryMode',          // maps to delivery_mode
        'courseContent',         // maps to course_content
        'previousClass',         // maps to previous_class
        'previousMedium',        // maps to academic_medium
        'schoolName',            // maps to school_name
        'previousBoard',         // maps to academic_board
        'passingYear',           // maps to passing_year
        'isRepeater',            // maps to is_repeater
        'scholarshipTest',       // maps to scholarship_test
        'lastBoardPercentage',   // maps to last_board_percentage
        'competitionExam',       // maps to competition_exam
        'batchName',             // maps to batch_name
        
        // Scholarship & Fees
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
        'paid_fees',
        'remaining_fees',
        'paidAmount',
        
        // Documents (Base64 encoded)
        // 'passport_photo',
        // 'marksheet',
        // 'caste_certificate',
        // 'scholarship_proof',
        // 'secondary_marksheet',
        // 'senior_secondary_marksheet',
        // Documents (Base64)
        // 'passport_photo',
        // 'marksheet',
        // 'caste_certificate',
        // 'scholarship_proof',
        // 'secondary_marksheet',
        // 'senior_secondary_marksheet',
        
        // Arrays
        'fees',
        'other_fees',
        'transactions',
        'paymentHistory',
        'activities',
        'history',
        'documents',
        
        // Status & Meta
        'status',
        'admission_date',
        'transferred_from',
        'transferred_at',
        'created_by',
        'updated_by',
        'session',
        'branch',

        'father_grade',
    'paidAmount',
    'remainingAmount',
    'paymentHistory',
    'last_payment_date',
    'admission_date',
    'activities',
    
    // Documents
    'passport_photo',
    'caste_certificate',
    'scholarship_proof',
    'secondary_marksheet',
    'senior_secondary_marksheet',
];
    
    protected $casts = [
        'dob' => 'date',
        'admission_date' => 'datetime',
        'transferred_at' => 'datetime',
        'batchStartDate' => 'date',
        'fees_calculated_at' => 'datetime',
        'percentage' => 'float',
        'last_board_percentage' => 'float',
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
        'paymentHistory' => 'array',
    'activities' => 'array',
    'last_payment_date' => 'date',
        'paidAmount' => 'float',
        'fees' => 'array',
        'other_fees' => 'array',
        'transactions' => 'array',
        'fees_breakup' => 'array',
        'history' => 'array',
        'documents' => 'array',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id', '_id');
    }

    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id', '_id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id', '_id');
    }
    
    /**
     * ✅ ACCESSOR: Get student_name from either 'student_name' or 'name' field
     */
    public function getStudentNameAttribute($value)
    {
        return $value ?? $this->attributes['name'] ?? 'N/A';
    }
    
    /**
     * ✅ ACCESSOR: Get father_name from either 'father_name' or 'father' field
     */
    public function getFatherNameAttribute($value)
    {
        return $value ?? $this->attributes['father'] ?? 'N/A';
    }
    
    /**
     * ✅ ACCESSOR: Get mother_name from either 'mother_name' or 'mother' field
     */
    public function getMotherNameAttribute($value)
    {
        return $value ?? $this->attributes['mother'] ?? 'N/A';
    }
    
    /**
     * ✅ ACCESSOR: Get father_contact from either 'father_contact' or 'mobileNumber'
     */
    public function getFatherContactAttribute($value)
    {
        return $value ?? $this->attributes['mobileNumber'] ?? 'N/A';
    }
    
    /**
     * ✅ ACCESSOR: Get father_whatsapp from either field
     */
    public function getFatherWhatsappAttribute($value)
    {
        return $value ?? $this->attributes['fatherWhatsapp'] ?? 'N/A';
    }
    
    /**
     * ✅ ACCESSOR: Get mother_contact from either field
     */
    public function getMotherContactAttribute($value)
    {
        return $value ?? $this->attributes['motherContact'] ?? 'N/A';
    }
    
    /**
     * ✅ ACCESSOR: Get phone from either 'phone' or 'studentContact'
     */
    public function getPhoneAttribute($value)
    {
        return $value ?? $this->attributes['studentContact'] ?? 'N/A';
    }
    
    /**
     * ✅ ACCESSOR: Get pincode
     */
    public function getPincodeAttribute($value)
    {
        return $value ?? $this->attributes['pinCode'] ?? 'N/A';
    }
    
    /**
     * ✅ ACCESSOR: Get course_type
     */
    public function getCourseTypeAttribute($value)
    {
        return $value ?? $this->attributes['courseType'] ?? 'N/A';
    }
    
    /**
     * ✅ ACCESSOR: Get course_name
     */
    public function getCourseNameAttribute($value)
    {
        return $value ?? $this->attributes['courseName'] ?? ($this->course->name ?? 'N/A');
    }
    
    /**
     * ✅ ACCESSOR: Get delivery_mode
     */
    public function getDeliveryModeAttribute($value)
    {
        return $value ?? $this->attributes['deliveryMode'] ?? $this->attributes['delivery'] ?? 'N/A';
    }
    
    /**
     * ✅ ACCESSOR: Get academic_medium
     */
    public function getAcademicMediumAttribute($value)
    {
        return $value ?? $this->attributes['previousMedium'] ?? 'N/A';
    }
    
    /**
     * ✅ ACCESSOR: Get academic_board
     */
    public function getAcademicBoardAttribute($value)
    {
        return $value ?? $this->attributes['previousBoard'] ?? 'N/A';
    }
}