<?php

namespace App\Models\Master;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Student\SMstudents;

class Batch extends Model
{
    protected $connection = 'mongodb';     
    protected $collection = 'batches';

    protected $fillable = [
        'batch_id',
        'name',
        'class',
        'course',              // Direct course name string
        'course_id',           // Reference to courses collection
        'course_type',     
        'medium',
        'mode',
        'delivery_mode',
        'shift',
        'branch_name',
        'branch_id',
        'start_date',
        'end_date',
        'installment_date_2',
        'installment_date_3',
        'timing',
        'capacity',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'installment_date_2' => 'date',
        'installment_date_3' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'capacity' => 'integer',
    ];

    public $timestamps = true;

    /**
     * Relationship: Batch belongs to a Course
     */
    public function courseRelation()
    {
        return $this->belongsTo(Courses::class, 'course_id', '_id');
    }

    /**
     * Relationship: Batch has many Students
     */
    public function students()
    {
        return $this->hasMany(SMstudents::class, 'batch_id', '_id');
    }

    /**
     * Scope: Active batches
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Filter by course
     */
    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    /**
     * Accessor: Get batch_name (for fees management compatibility)
     */
    public function getBatchNameAttribute()
    {
        return $this->attributes['name'] ?? $this->batch_id;
    }

    /**
     * Accessor: Get delivery_mode (alias for mode)
     */
    public function getDeliveryModeAttribute()
    {
        return $this->attributes['delivery_mode'] ?? $this->mode;
    }

    /**
     * Mutator: Set 'name' when setting batch_id
     */
    public function setBatchIdAttribute($value)
    {
        $this->attributes['batch_id'] = $value;
        if (!isset($this->attributes['name'])) {
            $this->attributes['name'] = $value;
        }
    }

    /**
     * Mutator: Set 'delivery_mode' when setting mode
     */
    public function setModeAttribute($value)
    {
        $this->attributes['mode'] = $value;
        $this->attributes['delivery_mode'] = $value;
    }

    // REMOVED THE PROBLEMATIC getCourseNameAttribute() METHOD
    // REMOVED THE getCourseTypeAttribute() METHOD
}