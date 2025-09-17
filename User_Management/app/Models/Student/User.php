<?php

namespace App\Models\Student;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'students';

    protected $fillable = ['name','email','password'];
    protected $hidden   = ['password','remember_token'];

    // normalize & hash
    public function setPasswordAttribute($value): void
    {
        $v = (string) $value;
        if (str_starts_with($v, '$2b$')) $v = '$2y$' . substr($v, 4);
        $isBcrypt = (bool) preg_match('/^\$2[ay]\$\d{2}\$[\.\/A-Za-z0-9]{53}$/', $v);
        $isArgon  = str_starts_with($v, '$argon2i$') || str_starts_with($v, '$argon2id$');
        $this->attributes['password'] = ($isBcrypt || $isArgon) ? $v : Hash::make($v);
    }
}
