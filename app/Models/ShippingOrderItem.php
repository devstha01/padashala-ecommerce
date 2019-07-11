<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingOrderItem extends Model
{
    protected $table = 'shipping_order_items';
    protected $fillable = ['invoice', 'tracking_id', 'carrier', 'weight', 'url', 'notify'];
}
