<?php

namespace App\Models\User;

use MongoDB\Laravel\Eloquent\Model;

class BatchAssignment extends Model
{
<<<<<<< Updated upstream
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
=======
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

>>>>>>> Stashed changes
}
