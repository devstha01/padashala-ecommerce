<?php

namespace App\Models\Commisions;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;

class MonthlyBonus extends Model
{
    protected $table = 'monthly_bonuses';
    protected $fillable = ['order_item_id', 'bonus', 'hk', 'asia', 'top_shopper', 'status'];

    function getOrderItem()
    {
    return $this->hasOne(OrderItem::class,'id','order_item_id');
    }
}
