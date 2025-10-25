<?php

namespace App\Models\Master;

use MongoDB\Laravel\Eloquent\Model;

class Batch extends Model
{
    protected $connection = 'mongodb';     
    protected $collection = 'batches';

    // All fields from your create batch form
    protected $fillable = [
        'batch_id',        // Batch code
        'class',           // Class Name
        'course',          // Course
        'course_type',     
        'medium',          // Medium (English/Hindi)
        'mode',            // Delivery Mode (Offline/Online/Hybrid)
        'shift',           // Shift (Morning/Evening)
        'branch_name',     // Branch Name
        'start_date',      // Start Date
        'installment_date_2',  // Installment Date 2
        'installment_date_3',  // Installment Date 3
        'status'           // Status (Active/Inactive)
    ];

    public $timestamps = true;
}