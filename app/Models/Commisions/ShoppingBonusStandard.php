<?php

namespace App\Models\Commisions;

use App\Models\Package;
use Illuminate\Database\Eloquent\Model;

class ShoppingBonusStandard extends Model
{
    protected $table = 'shopping_bonus_standard';
    protected $fillable = ['generation_position', 'percentage','package_id'];

    function package(){
        return $this->hasOne(Package::class,'id','package_id');
    }
}
