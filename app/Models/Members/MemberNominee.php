<?php

namespace App\Models\Members;;

use Illuminate\Database\Eloquent\Model;

class MemberNominee extends Model
{
    protected $table = "member_nominee_details";
    protected $fillable = ['nominee_name','member_id','identification_type','identification_number','contact_number','relationship'];
}
