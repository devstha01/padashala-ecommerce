<?php

use Illuminate\Database\Seeder;

class MemberSpecialTree extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $special =[
            array(
                'member_id'=>'1',
                'parent_id'=>NUll,
                'node'=>'0',
                'placement_position_id'=>NULL,
                'last_updated'=>1,
            )
        ];
        \App\Models\Members\MemberSpecialTree::insert($special);
    }
}
