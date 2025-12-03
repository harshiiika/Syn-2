<?php

namespace App\Models\TestSeries;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Master\Courses;
use App\Models\Student\SMstudents;

class Test extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'test_series';
    
    protected $fillable = [
        'course_id',
        'course_name',
        'test_name',
        'test_series_name',
        'test_type', // Type1 or Type2
        'subject_type', // Single or Double
        'subjects', // Array of subjects
        'subject_marks', // Array of marks per subject
        'test_count',
        'test_number', // For sequential numbering
        'status', // Pending, Active, Completed
        'total_marks',
        'duration', // In minutes
        'scheduled_date',
        'students_enrolled', // Array of student IDs
        'students_count',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'subjects' => 'array',
        'subject_marks' => 'array',
        'students_enrolled' => 'array',
        'test_count' => 'integer',
        'test_number' => 'integer',
        'total_marks' => 'integer',
        'duration' => 'integer',
        'students_count' => 'integer',
        'scheduled_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $timestamps = true;

    /**
     * Relationship: Test Series belongs to a Course
     */
    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id', '_id');
    }

    /**
     * Get students enrolled in this test
     */
    public function getEnrolledStudents()
    {
        if (empty($this->students_enrolled)) {
            return collect([]);
        }
        
        return SMstudents::whereIn('_id', $this->students_enrolled)->get();
    }

    /**
     * Scope: Filter by test type
     */
    public function scopeType($query, $type)
    {
        return $query->where('test_type', $type);
    }

    /**
     * Scope: Filter by course
     */
    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    /**
     * Scope: Active tests only
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }
}