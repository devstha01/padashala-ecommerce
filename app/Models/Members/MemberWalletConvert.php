<?php

namespace App\Models\Members;

use Illuminate\Database\Eloquent\Model;

class MemberWalletConvert extends Model
{
    protected $table = 'member_wallet_converts';
    protected $fillable = ['member_id', 'from_wallet_id', 'to_wallet_id', 'amount', 'remarks'];


    function getFromWallet()
    {
        return $this->hasOne('App\Models\Wallet', 'id', 'from_wallet_id');
    }

    function getToWallet()
    {
        return $this->hasOne('App\Models\Wallet', 'id', 'to_wallet_id');
    }

    function getMember()
    {
        return $this->hasOne('App\Models\User', 'id', 'member_id');
    }

}
