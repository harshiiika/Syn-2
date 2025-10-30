<?php

namespace App\Models\Student;

use MongoDB\Laravel\Eloquent\Model;

class Onboard extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'onboarded_students';
    
    public $timestamps = true;

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
        
        // Course Details
        'course_type',
        'course',
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
        
        // Batch Details
        'batchName',
        'batchStartDate',
        
        // Metadata
        'email',
        'alternateNumber',
        'branch',
        'session',
        'status',
        'onboardedAt',
        
        // Fee Details (if any)
        'total_fees',
        'paid_fees',
        'remaining_fees',
        'fee_status',
    ];

    protected $casts = [
        'dob' => 'date',
        'batchStartDate' => 'date',
        'onboardedAt' => 'datetime',
        'percentage' => 'float',
        'lastBoardPercentage' => 'float',
        'total_fees' => 'float',
        'paid_fees' => 'float',
        'remaining_fees' => 'float',
    ];

    /**
     * Get all onboarded students
     */
    public static function getAllOnboarded()
    {
        return self::orderBy('onboardedAt', 'desc')->get();
    }
}