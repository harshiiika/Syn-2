<?php

namespace App\Models\Student;

use MongoDB\Laravel\Eloquent\Model;

class Pending extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'pending_fees_students'; // This is the collection for pending fees
    
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
        'onboardedAt',
        'transferredToPendingFeesAt',
        
        // Fees Details (for pending fees)
        'totalFees',
        'paidAmount',
        'remainingAmount',
        'paymentHistory'
    ];

    protected $casts = [
        'dob' => 'date',
        'batchStartDate' => 'date',
        'onboardedAt' => 'datetime',
        'transferredToPendingFeesAt' => 'datetime',
        'percentage' => 'float',
        'lastBoardPercentage' => 'float',
        'totalFees' => 'float',
        'paidAmount' => 'float',
        'remainingAmount' => 'float',
        'paymentHistory' => 'array',
    ];
}