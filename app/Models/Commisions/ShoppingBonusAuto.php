<?php

namespace App\Models\Commisions;

use Illuminate\Database\Eloquent\Model;

class ShoppingBonusAuto extends Model
{
    protected $table = 'shopping_bonus_auto';
    protected $fillable = ['generation_position', 'percentage'];
}
