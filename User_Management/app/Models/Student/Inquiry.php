<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $connection = 'mongodb'; // ensure it uses MongoDB
    protected $collection = 'inquiries'; // use MongoDB collection name

   protected $fillable = [
    'student_name',
    'father_name',
    'mother',
    'dob',
    'father_contact',
    'father_whatsapp',
    'motherContact',
    'student_contact',
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
    'course_name',
    'delivery_mode',
    'medium',
    'board',
    'course_content',
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
    'status',
];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'follow_up_date' => 'datetime',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return \Database\Factories\Student\InquiryFactory::new();
    }
}