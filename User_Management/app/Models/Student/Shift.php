<?php

namespace App\Models\Student;

use App\Models\Student\SMstudents;
use MongoDB\Laravel\Eloquent\Model;

class Shift extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'shifts';
    
    protected $fillable = ['name', 'start_time', 'end_time', 'is_active'];

    public function students()
{
    return $this->hasMany(SMstudents::class, 'shift_id', '_id');
}
}