<?php

namespace App\Models\Commisions;

use Illuminate\Database\Eloquent\Model;

class ShoppingBonusSpecial extends Model
{
    protected $table = 'shopping_bonus_special';
    protected $fillable = ['generation_position', 'percentage'];
}
