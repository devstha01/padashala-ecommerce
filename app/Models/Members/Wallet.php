<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = ['name', 'status', 'detail'];
    protected $hidden = ['created_at', 'updated_at'];

    function getDetailAttribute($value)
    {
        if ($value === 'Register Points') $value = 'R Wallet';
        return $value;
    }
}
