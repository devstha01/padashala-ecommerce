<?php

use Illuminate\Database\Seeder;

class MemberAutoTree extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $auto =[
            array(
                'member_id'=>'1',
                'parent_id'=>NUll,
                'node'=>'0',
                'placement_position_id'=>NULL,
            )
        ];
        \App\Models\Members\MemberAutoTree::insert($auto);
    }
}
