<?php

use Illuminate\Database\Seeder;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $details = [
            array(
                'surname' => 'Merchant',
                'name' => 'Root',
                'identification_type' => 'citizenship',
                'identification_number' => '1038/40638',
                'user_name' => 'merchant',
                'email' => 'merchant@mlm.com',
                'password' => bcrypt('password'),
//                'transaction_password' => bcrypt('password'),
                'contact_number' => '9849898311',
                'dob' => '1994-04-01',
                'country_id' => 9,
                'gender' => 'Male',
                'address' => 'Kathmandu',
                'marital_status' => 'no',
                'joining_date' => '2019-01-01',
                'qr_image' => 'merchant1.png',

            )
        ];
        $merchant = \App\Models\Merchant::insert($details);

        $business = [
            'name' => 'Root business',
            'slug' => 'root-business-123123',
            'merchant_id' => 1,
            'registration_number' => '6363625263',
            'contact_number' => '9849898311',
            'country_id' => 9,
            'address' => 'Kathmandu',
        ];
        \App\Models\MerchantBusiness::create($business);
        \App\Models\MerchantAsset::create(['merchant_id' => 1, 'ecash_wallet' => 1000]);
    }
}
