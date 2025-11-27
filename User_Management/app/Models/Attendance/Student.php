<?php

namespace App\Models\Attendance;

use MongoDB\Laravel\Eloquent\Model;

class Student extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'attendance_students';

    protected $fillable = [
        'student_id',
        'student_name',
        'student_email',
        'roll_no',
        'batch_id',
        'batch_name',
        'course_id',
        'course_name',
        'shift',
        'branch',
        'date',
        'status',
        'marked_at',
        'marked_by',
        'remarks'
    ];

    protected $casts = [
        'marked_at' => 'datetime',
    ];

    /**
     * Relationship: Attendance belongs to a student
     */
    public function student()
    {
        return $this->belongsTo(\App\Models\Student\SMstudents::class, 'student_id', '_id');
    }
}