<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantDocument extends Model
{
    protected $fillable = ['merchant_id', 'name', 'file', 'mime'];
}
