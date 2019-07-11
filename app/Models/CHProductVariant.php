<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CHProductVariant extends Model
{
    protected $fillable = [
        'name', 'product_variant_id',
    ];
}
