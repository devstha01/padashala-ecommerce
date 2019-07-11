<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantBankInfo extends Model
{
    protected $table = 'merchant_bankinfos';
    protected $fillable = ['merchant_id', 'bank_name', 'acc_name', 'acc_number', 'contact_number', 'status'];
}
