<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerWalletTransfer extends Model
{
    protected $table = 'customer_wallet_transfers';
    protected $fillable = ['from_id', 'to_id', 'wallet_id', 'amount', 'remarks', 'flag', 'status'];

    protected $hidden = ['created_at'];

    function getFrom()
    {
        return $this->hasOne(User::class, 'id', 'from_id');
    }

    function getTo()
    {
        return $this->hasOne(User::class, 'id', 'to_id');
    }

    function getWallet()
    {
        return $this->hasOne(Wallet::class, 'id', 'wallet_id');
    }

}
