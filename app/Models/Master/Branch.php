<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Auth\User as Authenticatable;

class Branch extends Authenticatable
{
    use HasFactory, Notifiable;

    //referenced mongo db here and the specific collection used
    protected $connection = 'mongodb';
    protected $collection = 'branches';

    //name of these fillables must be same as in the db
    protected $fillable = [
        'name',
        'city',
        'status',
    ];

}
