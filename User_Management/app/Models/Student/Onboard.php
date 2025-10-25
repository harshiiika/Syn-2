<?php

namespace App\Models\Student;

use MongoDB\Laravel\Eloquent\Model;

class Onboard extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'onboarded_students';
    
    public $timestamps = true;

    protected $fillable = [
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
        'state',
        'city',
        'pinCode',
        'address',
        'belongToOtherCity',
        'economicWeakerSection',
        'armyPoliceBackground',
        'speciallyAbled',
        'courseType',
        'courseName',
        'deliveryMode',
        'medium',
        'board',
        'courseContent',
        'previousClass',
        'previousMedium',
        'schoolName',
        'previousBoard',
        'passingYear',
        'percentage',
        'isRepeater',
        'scholarshipTest',
        'lastBoardPercentage',
        'competitionExam',
        'batchName',
        'batchStartDate',
        // Payment related fields
        'totalFees',
        'paidAmount',
        'remainingAmount',
        'paymentStatus', // 'fully_paid', 'partial'
        'paymentHistory',
        'onboardedAt'
    ];

    protected $casts = [
        'totalFees' => 'float',
        'paidAmount' => 'float',
        'remainingAmount' => 'float',
        'paymentHistory' => 'array',
        'onboardedAt' => 'datetime',
        'dob' => 'date',
        'batchStartDate' => 'date'
    ];

    /**
     * Get all onboarded students
     */
    public static function getAllOnboarded()
    {
        return self::where('paymentStatus', 'fully_paid')
                   ->orderBy('onboardedAt', 'desc')
                   ->get();
    }

    /**
     * Get partially paid students
     */
    public static function getPartiallyPaid()
    {
        return self::where('paymentStatus', 'partial')
                   ->where('remainingAmount', '>', 0)
                   ->orderBy('created_at', 'desc')
                   ->get();
    }
}