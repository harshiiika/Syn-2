<?php

namespace App\Models\Session;

// the official MongoDB Eloquent base class
use MongoDB\Laravel\Eloquent\Model as Eloquent;

class AcademicSession extends Eloquent
{
    protected $connection = 'mongodb';     // <- use your mongodb connection from config/database.php
    protected $collection = 'academic_sessions';    // <- name of your Mongo collection

// fields 
    protected $fillable = [
        'serial',      // optional stored serial number
        'name',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
    '_id' => 'string',
];


    // enable timestamps  (created_at/updated_at saved) for future use idea
    // public $timestamps = true;

}