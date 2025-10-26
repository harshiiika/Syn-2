<?php

namespace App\Models\Master;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class CourseImport extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'course_imports'; // DIFFERENT collection - just for logs
    
    protected $fillable = [
        'filename',
        'imported_count',
        'updated_count',
        'failed_count',
        'errors',
    ];

    protected $casts = [
        'errors' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $timestamps = true;
}