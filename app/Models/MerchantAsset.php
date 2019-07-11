<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantAsset extends Model
{
    protected $fillable = [
        'merchant_id','ecash_wallet'
    ];
}
