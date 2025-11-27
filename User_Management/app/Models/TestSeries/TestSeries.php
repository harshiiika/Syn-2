<?php

namespace App\Models\TestSeries;

use App\Models\Master\Courses;
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
        'course_name',
        'test_name',
        'test_type',
        'subject_type',
        'subjects',
        'test_count',
        'test_number',
        'test_series_name',
        'status',
        'total_marks',
        'duration_minutes',
        'subject_marks',
        'students_enrolled',
        'students_count',
        
        // Result fields
        'result_uploaded',
        'result_uploaded_at',
        'result_uploaded_by',
        'result_locked',
        'result_locked_at',
        'result_locked_by',
        'total_students_appeared',
        
        'created_by',
        'updated_by',

        // Syllabus fields
        'syllabus_file_path',
        'syllabus_file_name',
        'syllabus_uploaded_at',
        'syllabus_uploaded_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'syllabus' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'result_uploaded_at' => 'datetime',
        'result_locked_at' => 'datetime',
        'subjects' => 'array',
        'subject_marks' => 'array',
        'students_enrolled' => 'array',
        'test_count' => 'integer',
        'test_number' => 'integer',
        'total_marks' => 'integer',
        'duration_minutes' => 'integer',
        'students_count' => 'integer',
        'total_students_appeared' => 'integer',
        'result_uploaded' => 'boolean',
        'result_locked' => 'boolean',
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
            if (!isset($model->result_uploaded)) {
                $model->result_uploaded = false;
            }
            if (!isset($model->result_locked)) {
                $model->result_locked = false;
            }
            if (!isset($model->created_at)) {
                $model->created_at = Carbon::now();
            }
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function course(): BelongsTo
    {
        return $this->belongsTo(Courses::class, 'course_id', '_id');
    }

    public function results()
    {
        return $this->hasMany(TestResult::class, 'test_series_id', '_id');
    }

    /**
     * ⭐ NEW: Relationship to individual tests
     */
    public function tests()
    {
        return $this->hasMany(Test::class, 'test_series_id', '_id');
    }

    /**
     * ⭐ NEW: Get only scheduled tests
     */
    public function scheduledTests()
    {
        return $this->hasMany(Test::class, 'test_series_id', '_id')
                    ->where('status', 'Scheduled')
                    ->orderBy('scheduled_date', 'asc');
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('test_type', $type);
    }

    public function scopeBySubjectType($query, $subjectType)
    {
        return $query->where('subject_type', $subjectType);
    }

    public function scopeForCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    public function scopeResultUploaded($query)
    {
        return $query->where('result_uploaded', true);
    }

    public function scopeResultLocked($query)
    {
        return $query->where('result_locked', true);
    }

    // ============================================
    // GETTERS / ATTRIBUTES
    // ============================================

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

    public function getFormattedSubjectsAttribute()
    {
        if (!$this->subjects || !is_array($this->subjects)) {
            return 'N/A';
        }
        return implode(', ', $this->subjects);
    }

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
     * Get display name for the test series.
     */
    public function getDisplayNameAttribute()
    {
        if ($this->isType1() && $this->test_series_name) {
            return $this->test_series_name;
        }
        return $this->test_name;
    }

    public function getFormattedPercentageAttribute()
    {
        return number_format($this->percentage ?? 0, 2) . '%';
    }

    public function getGradeAttribute()
    {
        $percentage = $this->percentage ?? 0;
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C';
        if ($percentage >= 40) return 'D';
        return 'F';
    }

    // ============================================
    // CHECKERS / BOOLEAN METHODS
    // ============================================

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
     * Check if subject is included in this test series.
     */
    public function hasSubject($subject): bool
    {
        if (!$this->subjects || !is_array($this->subjects)) {
            return false;
        }
        return in_array($subject, $this->subjects);
    }

    public function isType1(): bool
    {
        return $this->test_type === 'Type1';
    }

    public function isType2(): bool
    {
        return $this->test_type === 'Type2';
    }

    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    public function isActive(): bool
    {
        return $this->status === 'Active';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'Completed';
    }

    public function isResultUploaded(): bool
    {
        return $this->result_uploaded === true;
    }

    public function isResultLocked(): bool
    {
        return $this->result_locked === true;
    }

    // ============================================
    // ACTIONS
    // ============================================

    public function activate()
    {
        $this->update(['status' => 'Active']);
    }

    public function complete()
    {
        $this->update(['status' => 'Completed']);
    }

    // ============================================
    // ADDITIONAL SCOPES
    // ============================================

    public function scopeForTestSeries($query, $testSeriesId)
    {
        return $query->where('test_series_id', $testSeriesId);
    }

    public function scopeTopPerformers($query, $limit = 10)
    {
        return $query->orderBy('rank', 'asc')->limit($limit);
    }
}