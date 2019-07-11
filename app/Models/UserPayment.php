<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPayment extends Model
{
    protected $table = 'user_payments';
    protected $fillable = ['from_member_id', 'to_member_id', 'to_merchant_id', 'wallet_id', 'amount', 'remarks', 'qr_token', 'flag', 'status'];

    protected $hidden = ['to_member_id', 'qr_token', 'wallet_id', 'from_member_id', 'to_merchant_id'];

    function getFromMember()
    {
        return $this->hasOne(User::class, 'id', 'from_member_id');
    }

    function getToMember()
    {
        return $this->hasOne(User::class, 'id', 'to_member_id');
    }

    function getToMerchant()
    {
        return $this->hasOne(Merchant::class, 'id', 'to_merchant_id');
    }

    function getWallet()
    {
        return $this->hasOne(Wallet::class, 'id', 'wallet_id');
    }

    function getPayBonus()
    {
        return $this->hasOne(UserPayLog::class, 'user_payment_id', 'id');
    }

}
