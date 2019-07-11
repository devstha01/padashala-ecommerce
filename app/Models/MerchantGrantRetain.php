<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantGrantRetain extends Model
{
    protected $table='merchant_grant_retain_reports';
    protected $fillable = ['merchant_id','value','transaction_type'];
    function getMerchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }
}
