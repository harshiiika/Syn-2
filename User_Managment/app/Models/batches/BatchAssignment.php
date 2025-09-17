<?php

namespace App\Models\Batches;

use MongoDB\Laravel\Eloquent\Model; 
use App\Models\Department;
use App\Models\Role;

class BatchAssignment extends Model
{
    protected $connection = 'mongodb';  // Important: specify MongoDB connection
    protected $collection = 'batches';

    //fields to be filled same as in db
    protected $fillable = [
        'Batch_Code', 
        'Start_Date', 
        'Username', 
        'Shift', 
        'Status', 
        'Action']; // Fields are different from that of Employee

}


