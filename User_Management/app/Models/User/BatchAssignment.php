<?php

namespace App\Models\User;

use MongoDB\Laravel\Eloquent\Model; 
use App\Models\User\Department;
use App\Models\User\Role;

class BatchAssignment extends Model
{
    protected $connection = 'mongodb'; 
    protected $collection = 'batches';

    //fields to be filled same as in db
    protected $fillable = [
        'batch_id', 
        'start_date', 
        'username', 
        'shift', 
        'status', 
        'Action']; // Fields are different from that of Employee

}


