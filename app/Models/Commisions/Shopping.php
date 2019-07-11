<?php

namespace App\Models\Commisions;

use Illuminate\Database\Eloquent\Model;

class Shopping extends Model
{
    protected $table = 'shoppings';
    protected $fillable = ['key', 'name', 'value', 'default', 'status'];
}
