<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawConfig extends Model
{
    protected $table = 'withdraw_config';
    protected $fillable = ['name', 'min', 'max'];
}
