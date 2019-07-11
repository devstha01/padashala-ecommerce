<?php

use Illuminate\Database\Seeder;

class ShoppingBonusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bonuses = [

            //For Gold
            [
                'package_id' => 1,
                'generation_position' => 0,
                'percentage' => 7,
            ],
            [
                'package_id' => 1,
                'generation_position' => 1,
                'percentage' => 2,
            ],
            [
                'package_id' => 1,
                'generation_position' => 2,
                'percentage' => 1,
            ],
            [
                'package_id' => 1,
                'generation_position' => 3,
                'percentage' => 1,
            ],
            [
                'package_id' => 1,
                'generation_position' => 4,
                'percentage' => 1,
            ],
            [
                'package_id' => 1,
                'generation_position' => 5,
                'percentage' => 1,
            ],
            [
                'package_id' => 1,
                'generation_position' => 6,
                'percentage' => 1,
            ],
            [
                'package_id' => 1,
                'generation_position' => 7,
                'percentage' => 1,
            ],
            [
                'package_id' => 1,
                'generation_position' => 8,
                'percentage' => 1,
            ],

            //For Platinum

            [
                'package_id' => 2,
                'generation_position' => 0,
                'percentage' => 8,
            ],
            [
                'package_id' => 2,
                'generation_position' => 1,
                'percentage' => 3,
            ],
            [
                'package_id' => 2,
                'generation_position' => 2,
                'percentage' => 2,
            ],
            [
                'package_id' => 2,
                'generation_position' => 3,
                'percentage' => 1,
            ],
            [
                'package_id' => 2,
                'generation_position' => 4,
                'percentage' => 1,
            ],
            [
                'package_id' => 2,
                'generation_position' => 5,
                'percentage' => 1,
            ],
            [
                'package_id' => 2,
                'generation_position' => 6,
                'percentage' => 1,
            ],
            [
                'package_id' => 2,
                'generation_position' => 7,
                'percentage' => 1,
            ],
            [
                'package_id' => 2,
                'generation_position' => 8,
                'percentage' => 1,
            ],
            [
                'package_id' => 2,
                'generation_position' => 9,
                'percentage' => 1,
            ],
            //For Diamond

            [
                'package_id' => 3,
                'generation_position' => 0,
                'percentage' => 9,
            ],
            [
                'package_id' => 3,
                'generation_position' => 1,
                'percentage' => 4,
            ],
            [
                'package_id' => 3,
                'generation_position' => 2,
                'percentage' => 3,
            ],
            [
                'package_id' => 3,
                'generation_position' => 3,
                'percentage' => 2,
            ],
            [
                'package_id' => 3,
                'generation_position' => 4,
                'percentage' => 1,
            ],
            [
                'package_id' => 3,
                'generation_position' => 5,
                'percentage' => 1,
            ],
            [
                'package_id' => 3,
                'generation_position' => 6,
                'percentage' => 1,
            ],
            [
                'package_id' => 3,
                'generation_position' => 7,
                'percentage' => 1,
            ],
            [
                'package_id' => 3,
                'generation_position' => 8,
                'percentage' => 1,
            ],
            [
                'package_id' => 3,
                'generation_position' => 9,
                'percentage' => 1,
            ],
            [
                'package_id' => 3,
                'generation_position' => 10,
                'percentage' => 1,
            ],


        ];

        foreach ($bonuses as $ref) {
            \App\Models\Commisions\ShoppingBonusStandard::create($ref);
        }


        $auto = [
            [
                'generation_position' => 0,
                'percentage' => 5,
            ],
            [
                'generation_position' => 1,
                'percentage' => 2,
            ],
            [
                'generation_position' => 2,
                'percentage' => 1,
            ],
            [
                'generation_position' => 3,
                'percentage' => 1,
            ],
            [
                'generation_position' => 4,
                'percentage' => 1,
            ],
            [
                'generation_position' => 5,
                'percentage' => 1,
            ],
            [
                'generation_position' => 6,
                'percentage' => 1,
            ],
        ];

        foreach ($auto as $aut){
            \App\Models\Commisions\ShoppingBonusAuto::create($aut);
            \App\Models\Commisions\ShoppingBonusSpecial::create($aut);
        }

    }
}
