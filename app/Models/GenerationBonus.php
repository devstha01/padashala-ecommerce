<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GenerationBonus extends Model
{
    protected $table='member_generation_bonus';
    protected $fillable = ['member_id','generation','bonus_value','created_member_id'];

    function getMember()
    {
        return $this->belongsTo(User::class, 'created_member_id');
    }

}
