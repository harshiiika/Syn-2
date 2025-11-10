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
        'email',
        'phone',
        
        // Basic Details (from onboarding)
        'father',
        'mother',
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
        'scholarship_name',
        'total_fee_before_discount',
        'discretionary_discount',
        'discretionary_discount_type',
        'discretionary_discount_value',
        'discretionary_discount_reason',
        'discount_percentage',
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
        'remaining_fees',
        
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
    ];
    
    protected $casts = [
        'dob' => 'date',
        'admission_date' => 'datetime',
        'transferred_at' => 'datetime',
        'percentage' => 'float',
        'last_board_percentage' => 'float',
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
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id', '_id');
    }

    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id', '_id');
    }

    /**
     * (Optional) Relationship: Student belongs to a Shift
     */
    public function shift()
    {
        return $this->belongsTo( Shift::class, 'shift_id', '_id');
    }
}