<?php

namespace App\Models\Members;

use Illuminate\Database\Eloquent\Model;

class MemberCashWithdraw extends Model
{
    protected $table = 'member_cash_withdraws';
    protected $fillable = ['member_id', 'contact_number', 'amount', 'bank_name', 'acc_name', 'acc_number', 'remarks', 'flag', 'status',
        'updated_by', 'withdraw_date'];

    function getUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'member_id');
    }

    function admin()
    {
        return $this->hasOne('App\Models\Admin', 'id', 'updated_by');
    }
}
