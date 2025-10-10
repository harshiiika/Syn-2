<?php

namespace App\Models\Fees;

use MongoDB\Laravel\Eloquent\Model as Eloquent; // ✅ official package


class FeesMaster extends Eloquent
{
    // Force this model to use the mongodb connection
    protected $connection = 'mongodb';

    // Set your collection name (no SQL tables)
    protected $collection = 'fees_masters'; // change if your actual collection is different

    protected $fillable = [
        'fee_name',
        'amount',
        'currency',
        'status',
        'description',
        'created_at', 'updated_at', // optional if you store them
    ];

    // If you are NOT storing timestamps in docs, disable:
    // public $timestamps = false;
}
