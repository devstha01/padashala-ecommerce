<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantWalletTransferMerchant extends Model
{
    protected $table = 'merchant_wallet_transfers_merchant';
    protected $fillable = ['from_merchant_id', 'to_merchant_id', 'wallet_id', 'amount', 'remarks', 'qr_token', 'status'];

    protected $hidden = ['qr_token', 'created_at'];

    function getFromMerchant()
    {
        return $this->hasOne(Merchant::class, 'id', 'from_merchant_id');
    }

    function getToMerchant()
    {
        return $this->hasOne(Merchant::class, 'id', 'to_merchant_id');
    }

    function getWallet()
    {
        return $this->hasOne(Wallet::class, 'id', 'wallet_id');
    }
}
