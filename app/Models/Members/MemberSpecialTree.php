<?php

namespace App\Models\Members;

use Illuminate\Database\Eloquent\Model;

class MemberSpecialTree extends Model
{
    protected $table = "member_special_tree";
    protected $fillable = ['member_id','parent_id','node','placement_position_id','last_updated'];
}
