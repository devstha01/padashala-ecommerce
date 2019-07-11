<?php

namespace App\Models\Commisions;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;

class CashDeliveryBonusRecord extends Model
{
    protected $table = 'cash_delivery_bonus_records';
    protected $fillable = ['merchant_id', 'order_item_id', 'total', 'admin', 'paid_status', 'status'];

    function getOrderItem()
    {
        return $this->hasOne(OrderItem::class, 'id', 'order_item_id');
    }
}
