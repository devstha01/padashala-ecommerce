<?php

namespace App\Models;

use App\Models\Members\MemberAsset;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'surname', 'name', 'user_name', 'email', 'password', 'country_id', 'address', 'contact_number',
        'dob', 'gender', 'status', 'marital_status', 'joining_date',
        'identification_type', 'identification_number', 'city', 'postal_code', 'qr_code', 'qr_image', 'created_by', 'jwt_token_handle',
        'provider','provider_id'
    ];
    protected $table = 'users';

    protected $hidden = [
        'password', 'remember_token', 'jwt_token_handle'
    ];
    protected $appends = ['package_type'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    function getCountry()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    function getWallet()
    {
        return $this->hasOne('App\Models\Members\MemberAsset', 'member_id', 'id');
    }

    function getMemberCashWithdraw()
    {
        return $this->hasOne('App\Models\Members\MemberCashWithdraw', 'member_id', 'id');
    }

    function getAsset()
    {
        return $this->hasOne('App\Models\Members\MemberAsset', 'member_id', 'id');
    }

    function getQrImageAttribute($value)
    {
        return url('image/qr_image') . '/' . $value;
    }
}
