<?php

namespace App\Models\Members;;

use App\Models\Package;
use Illuminate\Database\Eloquent\Model;

class ReferalBonusRegister extends Model
{
    protected $table = "referal_bonus_register";
    protected $fillable = ['generation_position','package_id','refaral_percentage'];

    function package()
    {
        return $this->hasOne(Package::class, 'id', 'package_id');
    }
}
