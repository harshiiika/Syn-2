<?php

namespace App\Models\Student;

use MongoDB\Laravel\Eloquent\Model;

class Onboard extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'onboarded_students';
    
    public $timestamps = true;

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
        
        // Batch Details
        'batchName',
        'batchStartDate',
        
        // Metadata
        'email',
        'alternateNumber',
        'branch',
        'session',
        'onboardedAt'
    ];

    protected $casts = [
        'dob' => 'date',
        'batchStartDate' => 'date',
        'onboardedAt' => 'datetime',
        'percentage' => 'float',
        'lastBoardPercentage' => 'float',
    ];

    /**
     * Get all onboarded students
     */
    public static function getAllOnboarded()
    {
        return self::orderBy('onboardedAt', 'desc')->get();
    }
}