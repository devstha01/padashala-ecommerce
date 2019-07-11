<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChipsConfig extends Model
{
    protected $table = 'chips_config';

    protected $fillable = [
        'price_per_chips'
    ];

}
