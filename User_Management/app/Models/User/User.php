<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Auth\User as Authenticatable;
use App\Models\User\Role;
use App\Models\User\Department;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    //referenced mongo db here and the specific collection used
    protected $connection = 'mongodb';
    protected $collection = 'users';

    //name of these fillables must be same as in the db
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

    //the roles and dept are stored as array in db so here they are stored in $casts as array and object
    protected $casts = [
        'department' => 'array',
        'role' => 'array',
        'branch' => 'object',
        'email_verified_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    //reference the many to many relation between role and dept
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
