<?php

namespace App\Models\Members;

use Illuminate\Database\Eloquent\Model;

class MemberAutoTree extends Model
{
    protected $table = "member_auto_tree";
    protected $fillable = ['member_id','parent_id','node','placement_position_id'];
}
