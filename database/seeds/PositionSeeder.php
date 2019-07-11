<?php

use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $positions = array(
            [
                'position'=>1,
                'position_name'=>'One',
            ],
            [
                'position'=>2,
                'position_name'=>'Two',
            ],
            [
                'position'=>3,
                'position_name'=>'Three',
            ],
            [
                'position'=>4,
                'position_name'=>'Four',
            ],
            [
                'position'=>5,
                'position_name'=>'Five',
            ],


        );

        foreach($positions as $position){
            \DB::table('placement_positions')->insert([
                'position'=>$position['position'],
                'position_name'=>$position['position_name'],

            ]);
        }

    }
}
