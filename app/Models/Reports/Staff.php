<?php

namespace App\Models\Reports;

use App\Models\Student\Inquiry;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class Staff extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'staff';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'designation',
        'department',
        'branch',
        'status',
        'joining_date',
        'address',
        'emergency_contact',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'joining_date' => 'date',
        'status' => 'boolean'
    ];

    // Relationships
    public function inquiries()
    {
        return $this->hasMany(Inquiry::class, 'staff_name', 'name');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeByBranch($query, $branch)
    {
        return $query->where('branch', $branch);
    }
}