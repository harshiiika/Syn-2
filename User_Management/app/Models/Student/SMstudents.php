<?php

namespace App\Models\Student;

use App\Models\Master\Batch;
use App\Models\Master\Courses;
use MongoDB\Laravel\Eloquent\Model;

class SMstudents extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 's_mstudents';

    protected $guarded = [];

    protected $fillable = [
        // Basic Info
        'roll_no', 'student_name', 'email', 'phone',
        
        // Personal Details
        'father_name', 'mother_name', 'dob',
        'father_contact', 'father_whatsapp', 'mother_contact',
        'gender', 'father_occupation', 'father_caste', 'mother_occupation',
        
        // Address
        'state', 'city', 'pincode', 'address', 'belongs_other_city',
        
        // Academic Details
        'previous_class', 'academic_medium', 'school_name',
        'academic_board', 'passing_year', 'percentage',
        
        // Course Details
        'batch_id', 'batch_name', 'course_id', 'course_name',
        'course_content', 'delivery', 'delivery_mode', 'shift','shift_id',
        
        // Fee Details (ALL scholarship and payment data)
        'eligible_for_scholarship', 'scholarship_name', 'total_fee_before_discount',
        'discretionary_discount', 'discount_percentage', 'discounted_fee',
        'total_fees', 'gst_amount', 'total_fees_inclusive_tax',
        'paid_fees', 'paidAmount', 'remaining_fees', 'remainingAmount',
        'fee_status', 'paymentHistory', 'last_payment_date',
        'single_installment_amount', 'installment_1', 'installment_2', 'installment_3',
        
        // Documents
        'passport_photo', 'marksheet', 'caste_certificate', 'scholarship_proof',
        'secondary_marksheet', 'senior_secondary_marksheet',
        
        // Status & Audit
        'status', 'password',
        'transferred_from', 'transferred_at', 'created_by', 'updated_by'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'dob' => 'date',
        'percentage' => 'float',
        'total_fee_before_discount' => 'float',
        'discount_percentage' => 'float',
        'discounted_fee' => 'float',
        'total_fees' => 'float',
        'gst_amount' => 'float',
        'total_fees_inclusive_tax' => 'float',
        'paid_fees' => 'float',
        'paidAmount' => 'float',
        'remaining_fees' => 'float',
        'remainingAmount' => 'float',
        'single_installment_amount' => 'float',
        'installment_1' => 'float',
        'installment_2' => 'float',
        'installment_3' => 'float',
        'paymentHistory' => 'array',
        'transferred_at' => 'datetime',
    ];

    // Accessors
    public function getNameAttribute()
    {
        return $this->attributes['student_name'] ?? null;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['student_name'] = $value;
    }

    public function getDeliveryModeAttribute()
    {
        return $this->attributes['delivery'] ?? null;
    }

    public function setDeliveryModeAttribute($value)
    {
        $this->attributes['delivery'] = $value;
    }

    // Relationships

     public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }
        public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id', '_id');
    }

    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id', '_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
// namespace App\Models\Student;

// use App\Models\Master\Batch;
// use App\Models\Master\Courses;
// use MongoDB\Laravel\Eloquent\Model;

// class SMstudents extends Model
// {
//     protected $connection = 'mongodb';
//     protected $collection = 's_mstudents';

//     protected $fillable = [
//     'roll_no',
//     'student_name', // or keep 'name' if using accessor
//     'email',
//     'phone',
//     'batch_id',
//     'course_id',
//     'course_content',
//     'delivery', // or use accessor 'delivery_mode'
//     'shift',
//     'status',
//     'password' // if storing hashed passwords
// ];


//     protected $hidden = [
//         'password'
//     ];

//     protected $casts = [
//         'created_at' => 'datetime',
//         'updated_at' => 'datetime'
//     ];

//     /**
//      * Accessor for 'name' to use 'student_name'
//      */
//     public function getNameAttribute()
//     {
//         return $this->attributes['student_name'] ?? null;
//     }

//     /**
//      * Mutator for 'name' to set 'student_name'
//      */
//     public function setNameAttribute($value)
//     {
//         $this->attributes['student_name'] = $value;
//     }

//     /**
//      * Accessor for 'delivery_mode' to use 'delivery'
//      */
//     public function getDeliveryModeAttribute()
//     {
//         return $this->attributes['delivery'] ?? null;
//     }

//     /**
//      * Mutator for 'delivery_mode' to set 'delivery'
//      */
//     public function setDeliveryModeAttribute($value)
//     {
//         $this->attributes['delivery'] = $value;
//     }

//     /**
//      * Get the batch that the student belongs to
//      */
//     public function batch()
//     {
//         return $this->belongsTo(Batch::class, 'batch_id', '_id');
//     }

//     /**
//      * Get the course that the student is enrolled in
//      */
//     public function course()
//     {
//         return $this->belongsTo(Courses::class, 'course_id', '_id');
//     }

//     /**
//      * Scope to get only active students
//      */
//     public function scopeActive($query)
//     {
//         return $query->where('status', 'active');
//     }

//     /**
//      * Scope to get only inactive students
//      */
//     public function scopeInactive($query)
//     {
//         return $query->where('status', 'inactive');
//     }
// }