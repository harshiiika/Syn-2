<?php

namespace App\Models\Master;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class Courses extends Eloquent
{
    protected $connection = 'mongodb';     
    protected $collection = 'courses';    
    
    protected $fillable = [
        'course_name',
        'course_type',
        'class_name',
        'course_code',
        'subjects',
        'status'
    ];

    // Automatically cast subjects to array when retrieving from DB
    protected $casts = [
        'subjects' => 'array'
    ];


    public $timestamps = true;

}