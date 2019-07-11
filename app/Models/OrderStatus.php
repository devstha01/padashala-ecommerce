<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $fillable = [
        'key','name', 'status'
    ];

    function getMerchant()
    {
        return $this->hasOne(Merchant::class, 'id', 'merchant_id');
    }
}
