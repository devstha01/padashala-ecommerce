<?php

use Illuminate\Database\Seeder;

class ReferalBonusRegister extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bonusReferals = array(

            //For Gold
            [
                'package_id'=>1,
                'generation_position'=>1,
                'refaral_percentage'=>8,
            ],
            [
                'package_id'=>1,
                'generation_position'=>2,
                'refaral_percentage'=>2,
            ],
            [
                'package_id'=>1,
                'generation_position'=>3,
                'refaral_percentage'=>1.5,
            ],
            [
                'package_id'=>1,
                'generation_position'=>4,
                'refaral_percentage'=>1,
            ],
            [
                'package_id'=>1,
                'generation_position'=>5,
                'refaral_percentage'=>1,
            ],
            [
                'package_id'=>1,
                'generation_position'=>6,
                'refaral_percentage'=>1,
            ],
            [
                'package_id'=>1,
                'generation_position'=>7,
                'refaral_percentage'=>0.5,
            ],
            [
                'package_id'=>1,
                'generation_position'=>8,
                'refaral_percentage'=>0.5,
            ],
            [
                'package_id'=>1,
                'generation_position'=>9,
                'refaral_percentage'=>0.5,
            ],
              //For Platinum

            [
                'package_id'=>2,
                'generation_position'=>1,
                'refaral_percentage'=>9,
            ],
            [
                'package_id'=>2,
                'generation_position'=>2,
                'refaral_percentage'=>3,
            ],
            [
                'package_id'=>2,
                'generation_position'=>3,
                'refaral_percentage'=>2,
            ],
            [
                'package_id'=>2,
                'generation_position'=>4,
                'refaral_percentage'=>1.5,
            ],
            [
                'package_id'=>2,
                'generation_position'=>5,
                'refaral_percentage'=>1.5,
            ],
            [
                'package_id'=>2,
                'generation_position'=>6,
                'refaral_percentage'=>1,
            ],
            [
                'package_id'=>2,
                'generation_position'=>7,
                'refaral_percentage'=>1,
            ],
            [
                'package_id'=>2,
                'generation_position'=>8,
                'refaral_percentage'=>1,
            ],
            [
                'package_id'=>2,
                'generation_position'=>9,
                'refaral_percentage'=>0.5,
            ],
            //For Diamond

            [
                'package_id'=>3,
                'generation_position'=>1,
                'refaral_percentage'=>10,
            ],
            [
                'package_id'=>3,
                'generation_position'=>2,
                'refaral_percentage'=>4,
            ],
            [
                'package_id'=>3,
                'generation_position'=>3,
                'refaral_percentage'=>3,
            ],
            [
                'package_id'=>3,
                'generation_position'=>4,
                'refaral_percentage'=>2,
            ],
            [
                'package_id'=>3,
                'generation_position'=>5,
                'refaral_percentage'=>2,
            ],
            [
                'package_id'=>3,
                'generation_position'=>6,
                'refaral_percentage'=>1.5,
            ],
            [
                'package_id'=>3,
                'generation_position'=>7,
                'refaral_percentage'=>1.5,
            ],
            [
                'package_id'=>3,
                'generation_position'=>8,
                'refaral_percentage'=>1,
            ],
            [
                'package_id'=>3,
                'generation_position'=>9,
                'refaral_percentage'=>1,
            ],







        );

        foreach($bonusReferals as $ref){
            \DB::table('referal_bonus_register')->insert([
                'generation_position'=>$ref['generation_position'],
                'package_id'=>$ref['package_id'],
                'refaral_percentage'=>$ref['refaral_percentage'],

            ]);
        }
    }
}
