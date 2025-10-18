<?php

namespace App\Models\Master;

use MongoDB\Laravel\Eloquent\Model;

class StudentOnboard extends Model
{
 protected $connection = 'mongodb';
    protected $collection = 'student_onboards';
    protected $fillable = [
        'student_name',
        'father_name',
        'father_contact',
        'course_name',
        'delivery_mode',
        'course_content',
        'status',
    ];

    // Example relationship if student has transfer/history logs
    // public function history()
    // {
    //     return $this->hasMany(StudentHistory::class, 'student_id');
    // }
}
