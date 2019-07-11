<?php

namespace App\Models\Members;

use App\Models\Package;
use Illuminate\Database\Eloquent\Model;

class MemberAsset extends Model
{
    protected $fillable = ['member_id', 'ecash_wallet', 'evoucher_wallet', 'r_point', 'chip', 'package_id', 'capital', 'shop_point','capital_amount','dividend','capital_withdraw'];

    function getPackage()
    {
        return $this->hasOne(Package::class, 'id', 'package_id');
    }
}
