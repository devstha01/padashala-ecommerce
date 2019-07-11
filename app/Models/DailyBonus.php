<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyBonus extends Model
{
    protected $table='daily_bonus_record';
    protected $fillable = ['member_id','package_id','value'];
}
