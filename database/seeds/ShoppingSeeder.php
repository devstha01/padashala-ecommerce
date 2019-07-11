<?php

use Illuminate\Database\Seeder;

class ShoppingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $inserts = [
            ['key' => 'shopping_bonus_rate', 'name' => 'Shopping Bonus Rate', 'value' => 49, 'detail' => '2nd level percentile division for Shopping Bonus'],

            ['key' => 'bonus_rate', 'name' => 'Bonus Rate', 'value' => 21, 'detail' => '3st level percentile division for Bonus'],
            ['key' => 'admin_rate', 'name' => 'Administration Rate', 'value' => 79, 'detail' => '3st level percentile division for administration'],

            ['key' => 'standard_shopping_bonus', 'name' => 'Standard Shopping Bonus', 'value' => 25, 'detail' => 'Shopping Bonus percentile division for Standard'],
            ['key' => 'auto_shopping_bonus', 'name' => 'Auto Shopping Bonus', 'value' => 12, 'detail' => 'Shopping Bonus percentile division for Auto'],
            ['key' => 'special_shopping_bonus', 'name' => 'Special Shopping Bonus', 'value' => 12, 'detail' => 'Shopping Bonus percentile division for Special'],

            ['key' => 'ecash_shopping_bonus', 'name' => 'Cash Shopping Bonus', 'value' => 50, 'detail' => 'Shopping Bonus percentile division for E-cash'],
            ['key' => 'evoucher_shopping_bonus', 'name' => 'Voucher Shopping Bonus', 'value' => 20, 'detail' => 'Shopping Bonus percentile division for E-voucher'],
            ['key' => 'bcoin_shopping_bonus', 'name' => 'Bidding Coin Shopping Bonus', 'value' => 30, 'detail' => 'Shopping Bonus percentile division for Bidding Coin'],

            ['key' => 'hk_bonus', 'name' => 'HK Bonus', 'value' => 10, 'detail' => 'Bonus percentile division for HK'],
            ['key' => 'asia_bonus', 'name' => 'Asia Bonus', 'value' => 10, 'detail' => 'Bonus percentile division for Asia'],
            ['key' => 'top_shopper_bonus', 'name' => 'Top Shopper Bonus', 'value' => 1, 'detail' => 'Bonus percentile division for Top Shopper'],

            ['key' => 'ecash_bonus', 'name' => 'Cash Bonus', 'value' => 50, 'detail' => 'Bonus percentile division for E-cash'],
            ['key' => 'evoucher_bonus', 'name' => 'Voucher Bonus', 'value' => 20, 'detail' => 'Bonus percentile division for E-voucher'],
            ['key' => 'bcoin_bonus', 'name' => 'Bidding Coin Bonus', 'value' => 30, 'detail' => 'Bonus percentile division for Bidding Coin'],

//          customer shopping bonus
            ['key' => 'customer_bonus', 'name' => 'Customer Shopping Bonus', 'value' => 49, 'detail' => 'Customer Shopping Bonus'],
        ];

        foreach ($inserts as $insert) {
            \App\Models\Commisions\Shopping::create($insert);
        }

        \App\Models\Commisions\ShoppingMerchant::create([
            'merchant_id' => 1,
            'merchant_rate' => 95,
            'admin_rate' => 5,
        ]);
    }
}
