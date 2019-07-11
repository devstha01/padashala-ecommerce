<?php

namespace App\Models\Commisions;

use App\Models\Merchant;
use Illuminate\Database\Eloquent\Model;

class ShoppingMerchant extends Model
{
    protected $table = 'shopping_merchants';
    protected $fillable = ['merchant_id', 'merchant_rate', 'admin_rate', 'status'];

    function getMerchant()
    {
        return $this->hasOne(Merchant::class, 'id', 'merchant_id');
    }
}