<?php

namespace App\Models\Members;

use Illuminate\Database\Eloquent\Model;

class MemberStandardTree extends Model
{
    protected $table = "member_standard_tree";
    protected $fillable = ['member_id','sponser_id','parent_id','node','placement_position_id'];
}
