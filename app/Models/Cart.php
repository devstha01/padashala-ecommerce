<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'variant_id', 'quantity', 'status'
    ];

    function getVariant()
    {
        return $this->hasOne(ProductVariant::class, 'id', 'variant_id');
    }

    function getProduct()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
