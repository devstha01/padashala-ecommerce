<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\URL;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $categories = \App\Models\Category::all()->pluck('id')->toArray();
        for ($i = 0; $i < 9; $i++) {
            $str = $faker->name;
            $text = $faker->text;
            $filepath = public_path('image/products/');
            $catId = $faker->randomElement($categories);
            $subCategories = \App\Models\SubCategory::where('category_id', $catId)->get()->pluck('id')->toArray();
            \App\Models\Product::create([
                'category_id' => $catId,
                'sub_category_id' => $faker->randomElement($subCategories),
                'name' => $str,
                'slug' => str_slug($str),
                'detail' => $text,
                'merchant_business_id' => 1,
                'featured_image' => $faker->image($filepath, 800, 800, null, false),
                'marked_price' => rand(99, 999),
                'sell_price' => rand(99, 999),
                'discount' => rand(0, 99),
                'quantity' => rand(5, 15),
                'is_featured' => rand(0, 1),
                'status' => 1,
                'admin_flag' => 1,
            ]);
            if ($i < 3) {
                \App\Models\HomeBanner::create([
                    'image' => $faker->image(public_path('image/homebanner/'), 550, 341, null, false),
                    'url' => env('APP_URL') . '/product/' . str_slug($str),
                    'type' => 'product',
                    'slug' => str_slug($str),
                ]);
            }

            \App\Models\ProductVariant::create([
                'name' => $faker->word,
                'color_id' => rand(1, 3),
                'size' => str_random(2),
                'product_id' => $i,
                'marked_price' => rand(99, 999),
                'sell_price' => rand(99, 999),
                'discount' => rand(0, 99),
                'quantity' => rand(5, 15),
            ]);
            \App\Models\ProductVariant::create([
                'name' => $faker->word,
                'color_id' => rand(1, 3),
                'size' => str_random(2),
                'product_id' => $i,
                'marked_price' => rand(99, 999),
                'sell_price' => rand(99, 999),
                'discount' => rand(0, 99),
                'quantity' => rand(5, 15),
            ]);
        }
    }
}
