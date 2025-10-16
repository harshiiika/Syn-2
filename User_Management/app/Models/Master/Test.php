<?php

namespace App\Models\Master;

use MongoDB\Laravel\Eloquent\Model;
use Carbon\Carbon;

class Test extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'tests';
    
    protected $fillable = [
        'date',
        'description',
        'test_name'
    ];

        protected $casts = [
        'date' => 'datetime',
    ];
    
    // protected $casts = [
    //     'date' => 'date',
    //     'created_at' => 'datetime',
    //     'updated_at' => 'datetime'
    // ];
    
    /**
     * Get formatted date
     */
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->date)->format('d M Y');
    }
    
    /**
     * Scope for upcoming tests
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', Carbon::today())
                     ->orderBy('date', 'asc');
    }
    
    /**
     * Scope for past tests
     */
    public function scopePast($query)
    {
        return $query->where('date', '<', Carbon::today())
                     ->orderBy('date', 'desc');
    }
    
    /**
     * Scope by month and year
     */
    public function scopeByMonth($query, $year, $month)
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();
        
        return $query->whereBetween('date', [$startDate, $endDate]);
    }
}