<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use Notifiable, HasRoles;


    protected $guard = 'admin';
    protected $guard_name = 'admin';

    public $timestamps = false;

    protected $fillable = [
        'name', 'surname', 'email', 'role', 'user_name', 'password', 'country_id', 'address', 'gender', 'contact_number', 'dob', 'marital_status',
        'identification_type', 'transaction_password', 'identification_number', 'joining_date', 'status','position'
    ];

    protected $hidden = [
        'password', 'remember_token', 'transaction_password',
    ];
}
