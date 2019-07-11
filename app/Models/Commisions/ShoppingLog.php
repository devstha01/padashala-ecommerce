<?php

namespace App\Models\Commisions;

use Illuminate\Database\Eloquent\Model;

class ShoppingLog extends Model
{
    protected $table = 'shopping_logs';
    protected $fillable = ['order_item_id', 'merchant_id', 'admin_id', 'total', 'merchant', 'shopping_bonus', 'administration', 'bonus', 'status'];

    function getOrderItem()
    {
        return $this->hasOne('App\Models\OrderItem', 'id', 'order_item_id');
    }

    function getMerchant()
    {
        return $this->hasOne('App\Models\Merchant', 'id', 'merchant_id');
    }

    function getWallet()
    {
        return $this->hasOne('App\Models\Wallet', 'name', 'payment_method');
    }
}
