<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpgradeCustomer extends Model
{
    protected $table = 'upgrade_customers';
    protected $fillable = ['name', 'email', 'contact_number', 'status'];
}
