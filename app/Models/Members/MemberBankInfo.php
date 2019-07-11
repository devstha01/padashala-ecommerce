<?php

namespace App\Models\Members;

use Illuminate\Database\Eloquent\Model;

class MemberBankInfo extends Model
{
    protected $table = 'member_bankinfos';
    protected $fillable = ['member_id', 'bank_name', 'acc_name', 'acc_number', 'contact_number', 'status'];
}
