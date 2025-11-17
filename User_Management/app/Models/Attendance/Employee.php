<?php

namespace App\Models\Attendance;

use MongoDB\Laravel\Eloquent\Model;

class Employee extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'attendance_employees';

    protected $fillable = [
        'employee_id',
        'employee_name',
        'employee_email',
        'role',
        'department',
        'branch',
        'date',
        'status',
        'marked_at',
        'marked_by'
    ];

    protected $casts = [
        'marked_at' => 'datetime',
    ];
}