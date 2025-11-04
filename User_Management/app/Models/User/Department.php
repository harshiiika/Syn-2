<?php

namespace App\Models\User;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\User\User;

class Department extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'departments';

    protected $fillable = ['name', 'default_role_id'];  // ✅ Add default_role_id

    public function users()
    {
        return $this->hasMany(User::class);
    }
    
    // ✅ Add relationship to default role
    public function defaultRole()
    {
        return $this->belongsTo(Role::class, 'default_role_id');
    }
}