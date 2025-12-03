<?php

namespace App\Models\TestSeries;

use MongoDB\Laravel\Eloquent\Model;
use Carbon\Carbon;

class Test extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'tests';

    protected $fillable = [
        'test_series_id',
        'test_number',
        'scheduled_date',
        'scheduled_time',
        'duration_minutes',
        'batch_code',
        'status',
        'students_enrolled',
        'students_appeared',
        'result_uploaded',
        'result_locked',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'duration_minutes' => 'integer',
        'students_enrolled' => 'array',
        'students_appeared' => 'integer',
        'result_uploaded' => 'boolean',
        'result_locked' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function testSeries()
    {
        return $this->belongsTo(TestSeries::class, 'test_series_id', '_id');
    }

    public function results()
    {
        return $this->hasMany(TestResult::class, 'test_id', '_id');
    }

    public function getFormattedScheduledDateAttribute()
    {
        return $this->scheduled_date ? $this->scheduled_date->format('d M Y, h:i A') : 'N/A';
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'Scheduled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }
}