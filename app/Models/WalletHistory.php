<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletHistory extends Model
{
    protected $table='wallet_history';
    protected $fillable = ['member_id','desc','value','transaction_type','cash_flow'];

}
