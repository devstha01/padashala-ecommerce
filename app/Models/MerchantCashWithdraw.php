<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantCashWithdraw extends Model
{
    protected $table = 'merchant_cash_withdraws';
    protected $fillable = ['merchant_id', 'contact_number', 'amount', 'bank_name', 'acc_name', 'acc_number', 'remarks', 'flag', 'status',
        'updated_by', 'withdraw_date'];

    function getUser()
    {
        return $this->hasOne('App\Models\Merchant', 'id', 'merchant_id');
    }

    function admin()
    {
        return $this->hasOne('App\Models\Admin', 'id', 'updated_by');
    }
}
