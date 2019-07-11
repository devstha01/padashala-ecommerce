<?php

namespace App\Models\Members;

use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Member extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $guard = 'member';

    protected $fillable = [
        'surname','name','user_name','email','password','transaction_password','nominee_name',
        'identification_type','identification_number','country_id','address','contact_number',
        'dob','gender','marital_status','joining_date'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    function getStandardTree()
    {
        return $this->hasOne(MemberStandardTree::class, 'id', 'member_id');
    }

    public function getJWTIdentifier()
    {
      return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
      return [];
    }
    
}
