<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingLog extends Model
{
    protected $table = 'shopping_logs';
    protected $fillable = ['order_item_id', 'user_id', 'merchant_id', 'status', 'total', 'merchant_amount', 'admin_amount'];
}
