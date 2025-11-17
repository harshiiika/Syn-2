<?php

namespace App\Models\Master;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use App\Models\Student\SMstudents;

class Courses extends Eloquent
{
    protected $connection = 'mongodb';     
    protected $collection = 'courses';    
    
    protected $fillable = [
        'course_name',
        'name',            // Alias for course_name (for fees management)
        'course_type',
        'class_name',
        'content',         // Alias for class_name (for fees management)
        'course_code',
        'subjects',
        'status',
        'description',
        'duration',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'subjects' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $timestamps = true;

    /**
     * Relationship: Course has many Batches
     */
    public function batches()
    {
        return $this->hasMany(Batch::class, 'course_id', '_id');
    }

    /**
     * Relationship: Course has many Students
     */
    public function students()
    {
        return $this->hasMany(SMstudents::class, 'course_id', '_id');
    }

    /**
     * Scope: Active courses only
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Accessor: Get 'name' attribute (for fees management compatibility)
     */
    public function getNameAttribute()
    {
        return $this->attributes['name'] ?? $this->course_name;
    }

    /**
     * Accessor: Get 'content' attribute (for fees management compatibility)
     */
    public function getContentAttribute()
    {
        return $this->attributes['content'] ?? $this->class_name;
    }

    /**
     * Mutator: Set 'name' when setting course_name
     */
    public function setCourseNameAttribute($value)
    {
        $this->attributes['course_name'] = $value;
        $this->attributes['name'] = $value;
    }

    /**
     * Mutator: Set 'content' when setting class_name
     */
    public function setClassNameAttribute($value)
    {
        $this->attributes['class_name'] = $value;
        $this->attributes['content'] = $value;
    }
}