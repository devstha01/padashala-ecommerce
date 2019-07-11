<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GenerationBonusDistribution extends Model
{
    protected $table='generation_bonus_distribution';
    protected $fillable = ['ecash_percentage','evoucher_percentage','chip_percentage'];
}
