<?php

namespace App\Models\User;

use MongoDB\Laravel\Eloquent\Model;

class BatchAssignment extends Model
{
    protected $connection = 'mongodb';     
    protected $collection = 'batch_assignments';

    //fields to be filled same as in db
    protected $fillable = [
        'batch_id', 
        'start_date', 
        'username', 
        'shift', 
        'status', 
        'Action'
    ]; // Fields are different from that of Employee

//  public function assignments()
//     {
//         return $this->hasMany(BatchAssignment::class, 'batch_id', 'batch_id');
//     }

}

