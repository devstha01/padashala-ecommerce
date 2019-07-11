<?php

namespace App\Models;

use App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class FeatureProduct extends Model
{
    protected $fillable = ['product_id','admin_id','feature_from','feature_till','flag'];

function getProduct()
{
    return $this->hasOne(Product::class,'id','product_id');
}

}

