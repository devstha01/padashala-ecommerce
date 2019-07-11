<?php

use Illuminate\Database\Seeder;

class GenerationBonusDistributionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $config = array(

                'ecash_percentage'=>30,
                'evoucher_percentage'=>30,
                'chip_percentage'=>30,
                'rpoint_percentage'=>30,

        );
            \DB::table('generation_bonus_distribution')->insert([
                'ecash_percentage'=>$config['ecash_percentage'],
                'evoucher_percentage'=>$config['evoucher_percentage'],
                'chip_percentage'=>$config['chip_percentage'],
                'rpoint_percentage'=>$config['rpoint_percentage'],

            ]);


    }
}
