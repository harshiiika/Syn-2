<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model; 
use App\Models\Department;
use App\Models\User;

class Role extends Model
{
//same reference to mongo db and the specific collection used
    protected $connection = 'mongodb';
    protected $collection = 'roles';

    protected $fillable = ['name'];

//reference the one to many relation between user and role
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
