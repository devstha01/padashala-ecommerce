<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'product_id', 'order_id', 'product_variant_id', 'quantity', 'deliver_date',
        'marked_price', 'sell_price', 'discount', 'status', 'order_status_id', 'invoice',
        'category_share', 'product_share', 'tax', 'vat', 'excise', 'net_tax', 'payment_status', 'merchant_status'
    ];

    function getProduct()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    function getOrder()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    function getProductVariant()
    {
        return $this->hasOne(ProductVariant::class, 'id', 'product_variant_id');
    }

    function getOrderStatus()
    {
        return $this->hasOne(OrderStatus::class, 'key', 'order_status_id');
    }

    function getShoppingLog()
    {
        return $this->hasOne(ShoppingLog::class, 'order_item_id', 'id');
    }
}
