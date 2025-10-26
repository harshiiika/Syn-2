<?php

namespace App\Models\Student;

use MongoDB\Laravel\Eloquent\Model;

class Pending extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'students';
    
    // CRITICAL: Make sure this is set
    public $timestamps = true; // or false if you don't have timestamps
    
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
        'batchName'
    ];
    
    // Add this to help with debugging
    protected static function boot()
    {
        parent::boot();
        
        static::updating(function ($model) {
            \Log::info('Model updating event fired for: ' . $model->name);
        });
        
        static::updated(function ($model) {
            \Log::info('Model updated event fired for: ' . $model->name);
        });
    }

    protected $casts = [
        'totalFees' => 'float',
        'paidAmount' => 'float',
        'remainingAmount' => 'float',
        'paymentHistory' => 'array',
        'dob' => 'date',
        'batchStartDate' => 'date'
    ];
}