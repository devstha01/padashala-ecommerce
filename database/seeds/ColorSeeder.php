<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Color::create([
            'name' => 'Free Color',
            'color_code' => '#ffffff',
        ]);

        $faker = Faker::create();
        for ($i = 0; $i < 15; $i++)
            \App\Models\Color::create([
                'name' => $faker->safeColorName,
                'color_code' => $faker->hexcolor,
            ]);
    }
}
