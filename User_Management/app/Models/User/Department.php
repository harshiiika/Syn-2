<?php

namespace App\Models\User;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\User\User;

class Department extends Model
{
    //same reference to mongo db and the specific collection used
    protected $connection = 'mongodb';
    protected $collection = 'departments';

    protected $fillable = ['name'];

    //reference the one to many relation between user and dept
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
