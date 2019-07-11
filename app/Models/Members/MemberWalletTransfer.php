<?php

namespace App\Models\Members;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Model;

class MemberWalletTransfer extends Model
{
    protected $table = 'member_wallet_transfers';
    protected $fillable = ['from_member_id', 'to_member_id', 'wallet_id', 'amount', 'remarks', 'qr_token', 'flag', 'status'];

    protected $hidden = ['qr_token', 'created_at'];

    function getFromMember()
    {
        return $this->hasOne(User::class, 'id', 'from_member_id');
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
