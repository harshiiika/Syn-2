<?php

namespace App\Models\User;

use MongoDB\Laravel\Eloquent\Model;

class BatchAssignment extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'batch_assignments';

    // Fields for batch_assignments collection
    protected $fillable = [
        'batch_id',       // From batches
        'start_date',     // From batches
        'username',       // Assigned manually
        'shift',          // From batches or auto-calculated
        'status',         // Default: Active
    ];

    public $timestamps = true;

    /**
     * Helper method to determine shift based on time
     */
    public static function getShift($time = null)
    {
        $hour = $time ? $time->hour : now()->hour;
        
        if ($hour >= 6 && $hour < 12) {
            return 'Morning';
        } elseif ($hour >= 12 && $hour < 18) {
            return 'Afternoon';
        } else {
            return 'Evening';
        }
    }
}