<?php

namespace App\Models\Members;

use Illuminate\Database\Eloquent\Model;

class DividendWithdraw extends Model
{
    protected $fillable = ['member_id', 'amount'];
}
