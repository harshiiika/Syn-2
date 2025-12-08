<?php

namespace App\Models\study_material;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'units';

    protected $fillable = [
        'course_name',
        'subject',
        'units',
        'session',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}