<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bidding extends Model
{
    protected $table = 'bidding';
    protected $fillable = ['title','slug','description','product_image','wining_number_chips','bidding_date','status'];

}
