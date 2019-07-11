<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantWalletTransfer extends Model
{
    protected $table = 'merchant_wallet_transfers';
    protected $fillable = ['from_merchant_id', 'to_member_id', 'wallet_id', 'amount', 'remarks', 'qr_token', 'status'];

    protected $hidden = ['qr_token', 'created_at'];

    function getFromMerchant()
    {
        return $this->hasOne(Merchant::class, 'id', 'from_merchant_id');
    }

    function getToMember()
    {
        return $this->hasOne(User::class, 'id', 'to_member_id');
    }

    function getWallet()
    {
        return $this->hasOne(Wallet::class, 'id', 'wallet_id');
    }
}
