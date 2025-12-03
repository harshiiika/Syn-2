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
        'departments',
        'roles',
        'branch',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // REMOVE array casts for departments and roles
    protected $casts = [
        'email_verified_at' => 'datetime',
        // REMOVED: 'departments' => 'array',
        // REMOVED: 'roles' => 'array',
    ];

    public $timestamps = true;
}