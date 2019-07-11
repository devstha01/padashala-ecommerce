<?php

namespace App\Models\Commisions;

use Illuminate\Database\Eloquent\Model;

class ShoppingBonusDistribution extends Model
{
    protected $table = 'shopping_bonus_distibutions';
    protected $fillable = ['buyer_id', 'item_id', 'bonus_list', 'status'];
}
