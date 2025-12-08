<?php

namespace App\Models\study_material;

use MongoDB\Laravel\Eloquent\Model;

class Dispatch extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'study_material_dispatches';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id',
        'roll_no',
        'student_name',
        'father_name',
        'batch_id',
        'course_id',
        'dispatched_at',
        'dispatched_by',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'dispatched_at' => 'datetime',
    ];

    /**
     * Get the student associated with the dispatch.
     */
    public function student()
    {
        return $this->belongsTo(\App\Models\Student::class, 'student_id');
    }

    /**
     * Get the batch associated with the dispatch.
     */
    public function batch()
    {
        return $this->belongsTo(\App\Models\Master::class, 'batch_id');
    }

    /**
     * Get the course associated with the dispatch.
     */
    public function course()
    {
        return $this->belongsTo(\App\Models\Master::class, 'course_id');
    }
}