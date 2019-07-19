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
    {$list = [
        ['name' => 'Free Color', 'color_code' => '#ffffff'],
        ['name' => 'yellow', 'color_code' => '#FFFF00'],
        ['name' => 'silver', 'color_code' => '#C0C0C0'],
        ['name' => 'green', 'color_code' => '#008000'],
        ['name' => 'blue', 'color_code' => '#0000FF'],
        ['name' => 'black', 'color_code' => '#000000'],
        ['name' => 'olive', 'color_code' => '#808000'],
        ['name' => 'maroon', 'color_code' => '#800000'],
        ['name' => 'lime', 'color_code' => '#00FF00'],
        ['name' => 'aqua', 'color_code' => '#00FFFF'],
        ['name' => 'teal', 'color_code' => '#008080'],
        ['name' => 'white', 'color_code' => '#ffffff'],
        ['name' => 'gray', 'color_code' => '#808080'],
        ['name' => 'red', 'color_code' => '#FF0000'],
        ['name' => 'navy', 'color_code' => '#000080'],
        ['name' => 'fuchsia', 'color_code' => '#FF00FF'],

//            gray
        ['name' => 'charcoal', 'color_code' => '#2B1B17'],
        ['name' => 'gray wolf', 'color_code' => '#504A4B'],
        ['name' => 'ash gray', 'color_code' => '#666362'],
        ['name' => 'platinum', 'color_code' => '#e5e4e2'],
        ['name' => 'blue gray', 'color_code' => '#98afc7'],

//            blue
        ['name' => 'steel blue', 'color_code' => '#4863a0'],
        ['name' => 'cobalt blue', 'color_code' => '#0020c2'],
        ['name' => 'royal blue', 'color_code' => '#2b60de'],
        ['name' => 'sky blue', 'color_code' => '#6698ff'],
        ['name' => 'sea blue', 'color_code' => '#c2dfff'],
//            green
        ['name' => 'turquiose', 'color_code' => '#43c6db'],
        ['name' => 'dark forest green', 'color_code' => '#254117'],
        ['name' => 'jade green', 'color_code' => '#5efb6e'],
//            yellow
        ['name' => 'blonde', 'color_code' => '#fbf6d9'],
        ['name' => 'peach', 'color_code' => '#ffe5b4'],
        ['name' => 'mustard', 'color_code' => '#ffdb58'],
        ['name' => 'brass', 'color_code' => '#b5a642'],
        ['name' => 'bronze', 'color_code' => '#cd7f32'],
//            brown
        ['name' => 'mocha', 'color_code' => '#493d26'],
        ['name' => 'coffee', 'color_code' => '#6f4e37'],
        ['name' => 'coral', 'color_code' => '#ff7f50'],
        ['name' => 'light coral', 'color_code' => '#e77471'],
//            red
        ['name' => 'rose', 'color_code' => '#e8adaa'],
        ['name' => 'rose gold', 'color_code' => '#ecc5c0'],
        ['name' => 'pink', 'color_code' => '#f660ab'],
        ['name' => 'deep pink', 'color_code' => '#f52887'],
        ['name' => 'indigo', 'color_code' => '#4b0082'],
        ['name' => 'purple', 'color_code' => '#8e35ef'],
        ['name' => 'crimson', 'color_code' => '#e238ec'],
        ['name' => 'pearl', 'color_code' => '#fdeef4'],


    ];
        foreach ($list as $key => $item) {
            $id = ++$key;
            $color = \App\Models\Color::find($id);
            if (!$color)
                \App\Models\Color::create(['id' => $id, 'name' => $item['name'], 'color_code' => $item['color_code']]);
            else
                $color->update(['name' => $item['name'], 'color_code' => $item['color_code']]);
        }
    }
}
