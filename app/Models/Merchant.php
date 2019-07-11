<?php

namespace App\Models;

use App\Models\Commisions\ShoppingMerchant;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Merchant extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $guard = 'merchant';

    protected $fillable = [
        'surname', 'name', 'user_name', 'email', 'password', 'transaction_password',
        'identification_type', 'identification_number', 'country_id', 'address', 'contact_number',
        'dob', 'gender', 'marital_status', 'joining_date', 'status', 'logo', 'banner', 'qr_code', 'qr_image','city','owner_type'
    ];

    protected $hidden = [
        'password', 'remember_token', 'transaction_password', 'address', 'country_id','city'
    ];

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

    function getBusiness()
    {
        return $this->hasOne(MerchantBusiness::class, 'merchant_id', 'id');
    }

    function getQrImageAttribute($value)
    {
        return url('image/qr_image/merchant') . '/' . $value;
    }

    function getShoppingRate()
    {
        return $this->hasOne(ShoppingMerchant::class, 'merchant_id', 'id');
    }
}
