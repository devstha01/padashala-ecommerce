<?php

use Illuminate\Database\Seeder;

class MemberStandardTree extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $standard =[
            array(
                'member_id'=>'1',
                'parent_id'=>NUll,
                'node'=>'0',
                'placement_position_id'=>NULL,
            )
        ];
        \App\Models\Members\MemberStandardTree::insert($standard);
    }
}
