<?php

namespace App\Models\Commisions;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ShoppingWithdraw extends Model
{
    protected $table = 'shopping_withdraws';
    protected $fillable = ['member_id', 'shop_point', 'ecash_wallet', 'evoucher_wallet', 'chip', 'remarks'];

    public function getMember()
    {
        return $this->hasOne(User::class, 'id', 'member_id');
    }
}
