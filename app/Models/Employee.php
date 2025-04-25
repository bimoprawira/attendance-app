<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Authenticatable
{
    use HasFactory;

    protected $primaryKey = 'employee_id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'position',
        'date_joined',
        'annual_leave_quota',
        'sick_leave_quota',
        'emergency_leave_quota',
        'used_annual_leave',
        'used_sick_leave',
        'used_emergency_leave'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'date_joined' => 'date',
        'password' => 'hashed',
    ];

    public function presences()
    {
        return $this->hasMany(Presence::class, 'employee_id');
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class, 'employee_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function getAvailableQuota($type)
    {
        $quotaField = "{$type}_leave_quota";
        $usedField = "used_{$type}_leave";
        return $this->$quotaField - $this->$usedField;
    }
    
    public function gajis()
    {
        return $this->hasMany(Gaji::class, 'employee_id', 'employee_id');
    }

} 