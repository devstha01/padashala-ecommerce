<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantBusiness extends Model
{
    protected $table = 'merchant_business';

    protected $fillable = [
        'merchant_id', 'name', 'slug', 'country_id', 'address', 'contact_number', 'registration_number', 'city', 'pan', 'vat'
    ];

    function getMerchant()
    {
        return $this->hasOne(Merchant::class, 'id', 'merchant_id');
    }

    function getCountry()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    function getProducts()
    {
        return $this->hasMany(Product::class, 'merchant_business_id', 'id');
    }
}
