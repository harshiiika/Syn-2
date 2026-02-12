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
        'profile_picture', // Ensure this is in fillable
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['departmentNames', 'roleNames', 'profilePictureUrl'];

    public $timestamps = true;

    /**
     * Get department names from department IDs
     */
    public function getDepartmentNamesAttribute()
    {
        $deptIds = $this->attributes['departments'] ?? [];
        
        if (empty($deptIds) || !is_array($deptIds)) {
            return collect();
        }

        $deptIds = collect($deptIds)
            ->map(fn($id) => (string) (is_object($id) ? $id : $id))
            ->filter()
            ->unique()
            ->toArray();

        if (empty($deptIds)) {
            return collect();
        }

        return \App\Models\User\Department::whereIn('_id', $deptIds)
            ->pluck('name');
    }

    /**
     * Get role names from role IDs
     */
    public function getRoleNamesAttribute()
    {
        $roleIds = $this->attributes['roles'] ?? [];
        
        if (empty($roleIds) || !is_array($roleIds)) {
            return collect();
        }

        $roleIds = collect($roleIds)
            ->map(fn($id) => (string) (is_object($id) ? $id : $id))
            ->filter()
            ->unique()
            ->toArray();

        if (empty($roleIds)) {
            return collect();
        }

        return \App\Models\User\Role::whereIn('_id', $roleIds)
            ->pluck('name');
    }

    /**
     * Get full profile picture URL
     */
    public function getProfilePictureUrlAttribute()
    {
        if (empty($this->profile_picture)) {
            return null;
        }

        return asset('storage/' . $this->profile_picture);
    }

    /**
     * Check if user has profile picture
     */
    public function hasProfilePicture()
    {
        return !empty($this->profile_picture) && 
               \Storage::disk('public')->exists($this->profile_picture);
    }

    /**
     * Get profile picture or default initial avatar
     */
    public function getProfilePictureOrDefault()
    {
        if ($this->hasProfilePicture()) {
            return $this->profilePictureUrl;
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . 
               '&size=150&background=667eea&color=fff&bold=true';
    }

    /**
     * Delete profile picture file
     */
    public function deleteProfilePicture()
    {
        if ($this->profile_picture && \Storage::disk('public')->exists($this->profile_picture)) {
            \Storage::disk('public')->delete($this->profile_picture);
        }
    }
}