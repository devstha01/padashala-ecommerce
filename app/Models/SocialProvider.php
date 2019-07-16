<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialProvider extends Model
{
    protected $table = 'social_provider';
    protected $fillable = ['provider', 'provider_id', 'user_id', 'status'];
}
