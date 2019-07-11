<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class BiddingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 0; $i < 10; $i++) {
            $str = $faker->name;
            $text = $faker->text;
            $filepath = public_path('image/bidding/');
            \App\Models\Bidding::create([
                'title' => $str,
                'slug' => str_slug($str),
                'description' => $text,
                'product_image' => $faker->image($filepath, 800, 750, null, false),
                'wining_number_chips' => 1000,
                'bidding_date' => '2019-06-26',
                'status' => '1',
            ]);
        }
    }
}
