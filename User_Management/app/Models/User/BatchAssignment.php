<?php

namespace App\Models\User;

use MongoDB\Laravel\Eloquent\Model; 
use App\Models\User\Department;
use App\Models\User\Role;

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


