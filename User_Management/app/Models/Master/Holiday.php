<?php

namespace App\Models\Master;

use MongoDB\Laravel\Eloquent\Model;
use Carbon\Carbon;

class Holiday extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'holidays';
    
    protected $fillable = [
        'date',
        'description',
        'type'
    ];
    
        protected $casts = [
        'date' => 'datetime',
    ];
    
    /**
     * Get formatted date
     */
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->date)->format('d M Y');
    }
}