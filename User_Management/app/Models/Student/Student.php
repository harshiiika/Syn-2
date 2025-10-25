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

protected $fillable = [
    // Basic Details
    'name',
    'father',
    'mother',
    'dob',
    'mobileNumber',
    'alternateNumber',
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
    
    // Course Details (IMPORTANT - note the field names)
    'course_type',      // NEW FIELD
    'course',           // NEW FIELD
    'courseName',       // Keep for backward compatibility
    'courseType',       // Keep for backward compatibility
    'deliveryMode',
    'medium',
    'board',
    'courseContent',
    'email',
    'branch',
    
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
    
    // Batch & Fees
    'batchName',
    'status',
    'total_fees',
    'paid_fees',
    'remaining_fees',
    'fee_status',
    'admission_date',
    'session',
    'paymentHistory'
];

    protected $casts = [
        'total_fees' => 'float',
        'paid_fees' => 'float',
        'remaining_fees' => 'float',
        'admission_date' => 'datetime',
    ];

    /**
     * Get all students
     */
    public static function getAllStudents()
    {
        return self::all();
    }

    /**
     * Get student by ID
     */
    public static function getStudentById($id)
    {
        return self::find($id);
    }

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
 * Get students for "Pending Inquires" tab (with any remaining fees)
 */
public static function getPendingStudents()
{
    return self::where('remaining_fees', '>', 0)
               ->where('status', self::STATUS_PENDING_FEES)
               ->orderBy('created_at', 'desc')
               ->get();
}

/**
 * Get students for "Pending Fees Students" page (same as pending)
 */
public static function getPendingFeesStudents()
{
    return self::where('remaining_fees', '>', 0)
               ->orderBy('created_at', 'desc')
               ->get();
}

/**
 * Get students for "Onboarding Students" tab (fully paid)
 */
public static function getActiveStudents()
{
    return self::where('remaining_fees', '<=', 0)
               ->where('status', self::STATUS_ACTIVE)
               ->orderBy('created_at', 'desc')
               ->get();
}
}