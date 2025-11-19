<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;
use Carbon\Carbon;

class TestSeries extends Model
{
    /**
     * The connection name for the model.
     */
    protected $connection = 'mongodb';
    
    /**
     * The collection associated with the model.
     */
    protected $collection = 'test_series';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'test_master_id',
        'course_id',
        'test_name',
        'test_type',              // Type1 or Type2
        'subject_type',           // Single or Double
        'subjects',               // Array of subjects (Physics, Chemistry, Mathematics)
        'test_count',             // No. of test counts
        'test_series_name',       // Only for Type1
        'status',
        'total_marks',
        'duration_minutes',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'subjects' => 'array',
        'test_count' => 'integer',
        'total_marks' => 'integer',
        'duration_minutes' => 'integer',
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [];

    /**
     * Boot method to set default values
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!isset($model->status)) {
                $model->status = 'Pending';
            }
            if (!isset($model->created_at)) {
                $model->created_at = Carbon::now();
            }
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    /**
     * Get the test master that owns the test series.
     */
    public function testMaster(): BelongsTo
    {
        return $this->belongsTo(TestMaster::class, 'test_master_id', '_id');
    }

    /**
     * Get the course that owns the test series.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id', '_id');
    }

    /**
     * Scope a query to only include pending test series.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    /**
     * Scope a query to only include active test series.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Scope a query to only include completed test series.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }

    /**
     * Scope a query to filter by test type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('test_type', $type);
    }

    /**
     * Scope a query to filter by subject type.
     */
    public function scopeBySubjectType($query, $subjectType)
    {
        return $query->where('subject_type', $subjectType);
    }

    /**
     * Scope a query to filter by test master.
     */
    public function scopeForTestMaster($query, $testMasterId)
    {
        return $query->where('test_master_id', $testMasterId);
    }

    /**
     * Scope a query to filter by course.
     */
    public function scopeForCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    /**
     * Get formatted subjects list.
     */
    public function getFormattedSubjectsAttribute()
    {
        if (!$this->subjects || !is_array($this->subjects)) {
            return 'N/A';
        }
        return implode(', ', $this->subjects);
    }

    /**
     * Get formatted duration.
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration_minutes) {
            return 'N/A';
        }

        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0) {
            return $hours . 'h ' . ($minutes > 0 ? $minutes . 'm' : '');
        }
        
        return $minutes . ' minutes';
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'Active' => 'bg-success',
            'Completed' => 'bg-primary',
            'Pending' => 'bg-warning',
            default => 'bg-secondary',
        };
    }

    /**
     * Get status icon.
     */
    public function getStatusIconAttribute()
    {
        return match($this->status) {
            'Active' => 'fa-check-circle',
            'Completed' => 'fa-flag-checkered',
            'Pending' => 'fa-clock',
            default => 'fa-circle',
        };
    }

    /**
     * Check if test series is Type1.
     */
    public function isType1(): bool
    {
        return $this->test_type === 'Type1';
    }

    /**
     * Check if test series is Type2.
     */
    public function isType2(): bool
    {
        return $this->test_type === 'Type2';
    }

    /**
     * Check if test series is Single subject type.
     */
    public function isSingle(): bool
    {
        return $this->subject_type === 'Single';
    }

    /**
     * Check if test series is Double subject type.
     */
    public function isDouble(): bool
    {
        return $this->subject_type === 'Double';
    }

    /**
     * Check if test series is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    /**
     * Check if test series is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'Active';
    }

    /**
     * Check if test series is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'Completed';
    }

    /**
     * Activate the test series.
     */
    public function activate()
    {
        $this->update(['status' => 'Active']);
    }

    /**
     * Complete the test series.
     */
    public function complete()
    {
        $this->update(['status' => 'Completed']);
    }

    /**
     * Get formatted created date.
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('d M Y, h:i A') : 'N/A';
    }

    /**
     * Get formatted updated date.
     */
    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at ? $this->updated_at->format('d M Y, h:i A') : 'N/A';
    }

    /**
     * Check if subject is included in this test series.
     */
    public function hasSubject($subject): bool
    {
        if (!$this->subjects || !is_array($this->subjects)) {
            return false;
        }
        return in_array($subject, $this->subjects);
    }

    /**
     * Get display name for the test series.
     */
    public function getDisplayNameAttribute()
    {
        if ($this->isType1() && $this->test_series_name) {
            return $this->test_series_name;
        }
        return $this->test_name;
    }
}