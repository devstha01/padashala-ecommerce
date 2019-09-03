<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specification extends Model
{
    protected $table = 'specification';
    protected $fillable = ['product_id', 'name', 'detail', 'status'];
}
