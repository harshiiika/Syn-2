<?php

namespace App\Models\Master;

use MongoDB\Laravel\Eloquent\Model;

class OtherFee extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'other_fees';

    protected $fillable = [
        'fee_type',
        'amount',
        'session_id',
        'branch_id',
        'created_by'
    ];

    protected $casts = [
        'amount' => 'float'
    ];
}