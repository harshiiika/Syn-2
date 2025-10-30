<?php

namespace App\Models\Student;

use App\Models\Master\Batch;
use App\Models\Master\Courses;
use MongoDB\Laravel\Eloquent\Model;

class SMstudents extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 's_mstudents';

    protected $fillable = [
    'roll_no',
    'student_name', // or keep 'name' if using accessor
    'email',
    'phone',
    'batch_id',
    'course_id',
    'course_content',
    'delivery', // or use accessor 'delivery_mode'
    'shift',
    'status',
    'password' // if storing hashed passwords
];


    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Accessor for 'name' to use 'student_name'
     */
    public function getNameAttribute()
    {
        return $this->attributes['student_name'] ?? null;
    }

    /**
     * Mutator for 'name' to set 'student_name'
     */
    public function setNameAttribute($value)
    {
        $this->attributes['student_name'] = $value;
    }

    /**
     * Accessor for 'delivery_mode' to use 'delivery'
     */
    public function getDeliveryModeAttribute()
    {
        return $this->attributes['delivery'] ?? null;
    }

    /**
     * Mutator for 'delivery_mode' to set 'delivery'
     */
    public function setDeliveryModeAttribute($value)
    {
        $this->attributes['delivery'] = $value;
    }

    /**
     * Get the batch that the student belongs to
     */
    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id', '_id');
    }

    /**
     * Get the course that the student is enrolled in
     */
    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id', '_id');
    }

    /**
     * Scope to get only active students
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get only inactive students
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}