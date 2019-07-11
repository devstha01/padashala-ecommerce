<?php

use Illuminate\Database\Seeder;

class PackagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $packages = array(
          [
            'name'=>'Gold',
            'amount'=>100,
            'capital_value'=>0,
            'dividend'=>0,
          ],

          [
            'name'=>'Platinum',
            'amount'=>500,
              'capital_value'=>1000,
              'dividend'=>1.5,
          ],

          [
            'name'=>'Diamond',
            'amount'=>1000,
              'capital_value'=>2000,
              'dividend'=>3,
          ],
        );

        foreach($packages as $package){
            \DB::table('packages')->insert([
                'name'=>$package['name'],
                'capital_value'=>$package['capital_value'],
                'dividend'=>$package['dividend'],
                'amount'=>$package['amount'],
            ]);
        }
    }
}
