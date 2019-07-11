<?php

use Illuminate\Database\Seeder;

class ChipsConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $config = array(

                'price_per_chips'=>2,


        );
            \DB::table('chips_config')->insert([
                'price_per_chips'=>$config['price_per_chips'],


            ]);


    }
}
