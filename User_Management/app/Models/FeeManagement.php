<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class FeeManagement extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'fee_transactions';
    
    protected $fillable = [
        'student_id',
        'student_name',
        'roll_no',
        'course',
        'session',
        'amount',
        'paid_amount',
        'payment_type',
        'payment_method',
        'payment_mode',
        'transaction_id',
        'transaction_number',
        'receipt_number',
        'transaction_date',
        'payment_date',
        'transaction_type',
        'status',
        'remarks',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'amount' => 'float',
        'paid_amount' => 'float',
        'transaction_date' => 'datetime',
        'payment_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship with student
    public function student()
    {
        return $this->belongsTo(Student\SMstudents::class, 'student_id', '_id');
    }
}