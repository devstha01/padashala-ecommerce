<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrantRetain extends Model
{
    protected $table='grant_retain_report';
    protected $fillable = ['member_id','value','transaction_type'];

    function getMember()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

}
