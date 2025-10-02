<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = [
        'name',
        'email',
        'mobileNumber',
        'alternateNumber',
        'departments',  // Changed from 'department'
        'roles',        // Changed from 'role'
        'branch',
        'password',
        'status',
    ];

    // REMOVE THE CASTS - MongoDB handles arrays automatically
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // No relationships needed - we're handling this manually
}