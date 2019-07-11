<?php

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SubChildCategory;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Fashion', 'Health and beauty', 'Jewelry', 'Vehicles and assessories', 'Machinery and Tools', 'Home appliance', 'Stationary', 'Arts and gifts'
        ];

        $faker = Faker::create();
//        $filepath = public_path('image/admin/category');

        foreach ($categories as $category) {
            $str = $category;
            $cat_id = Category::create([
                'name' => $str,
                'slug' => str_slug($str),
//                'image' => $faker->image($filepath, 150, 90, null, false),

            ])->id;

            for ($j = 0; $j < rand(0, 3); $j++) {
                $str = $faker->name;
                $subcat_id = SubCategory::create([
                    'name' => $str,
                    'slug' => str_slug($str),
                    'category_id' => $cat_id,
//                    'image' => $faker->image($filepath, 150, 90, null, false),
                ])->id;


                for ($k = 0; $k < rand(0, 5); $k++) {
                    $str = $faker->name;
                    SubChildCategory::create([
                        'name' => $str,
                        'slug' => str_slug($str),
                        'sub_category_id' => $subcat_id,
//                        'image' => $faker->image($filepath, 150, 90, null, false),
                    ]);
                }
            }
        }
    }
}
