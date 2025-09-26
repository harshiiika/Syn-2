<?php

namespace App\Models\User;

use MongoDB\Laravel\Eloquent\Model;

class BatchAssignment extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'batches_assignment';

    //fields to be filled same as in db
    protected $fillable = [
        'batch_id',
        'start_date',
        'username',
        'shift',
        'status', // Fields are different from that of Employee
    ];
}
