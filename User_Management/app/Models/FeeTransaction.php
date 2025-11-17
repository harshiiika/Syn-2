<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Student\SMstudents;
use App\Models\Master\Courses;
use App\Models\Master\Batch;

class FeeTransaction extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'fee_transactions';

    protected $fillable = [
        'student_id',
        'course_id',
        'batch_id',
        'amount',
        'payment_type',
        'payment_mode',
        'transaction_number',
        'transaction_date',
        'session',
        'receipt_number',
        'remarks',
        'installment_number',
        'collected_by',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'amount' => 'decimal:2',
        'installment_number' => 'integer',
    ];

    public $timestamps = true;

    /**
     * Relationship: Transaction belongs to a Student
     */
    public function student()
    {
        return $this->belongsTo(SMstudents::class, 'student_id', '_id');
    }

    /**
     * Relationship: Transaction belongs to a Course
     */
    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id', '_id');
    }

    /**
     * Relationship: Transaction belongs to a Batch
     */
    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id', '_id');
    }

    /**
     * Scopes
     */
    public function scopeDateRange($query, $fromDate, $toDate)
    {
        return $query->whereBetween('transaction_date', [$fromDate, $toDate]);
    }

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('transaction_date', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('transaction_date', now()->month)
                     ->whereYear('transaction_date', now()->year);
    }

    /**
     * Accessor: Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return '₹' . number_format($this->amount, 2);
    }
}