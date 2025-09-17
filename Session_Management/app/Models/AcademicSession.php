<?php

namespace App\Models;

// the official MongoDB Eloquent base class
use MongoDB\Laravel\Eloquent\Model as Eloquent;

class AcademicSession extends Eloquent
{
    // protected $connection = 'mongodb';     // <- use your mongodb connection from config/database.php
    protected $collection = 'Lara';    // <- name of your Mongo collection

// fields 
    protected $fillable = [
        'serial',      // optional stored serial number
        'name',
        'start_date',
        'end_date',
        'status'
    ];

    // enable timestamps  (created_at/updated_at saved)
    // public $timestamps = true;

}