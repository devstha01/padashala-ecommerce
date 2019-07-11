<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $details = [
//                'salutation'=>'Mr.',
            'surname' => 'Member',
            'name' => 'Root',
            'identification_type' => 'citizenship',
            'identification_number' => '1038/40638',
            'user_name' => 'member',
            'email' => 'member@mlm.com',
            'password' => bcrypt('password'),
            'transaction_password' => bcrypt('password'),
            'is_member' => 1,
            'contact_number' => '9849898311',
            'dob' => '1994-04-01',
            'country_id' => 9,
            'gender' => 'Male',
            'city' => 'Kathmandu',
            'address' => 'Old Baneshwor, 12 Street',
            'marital_status' => 'no',
            'joining_date' => '2019-01-01',
            'qr_image' => 'user1.png',
        ];
        $member = \App\Models\User::create($details);

        \App\Models\Members\MemberNominee::create([
            'member_id'=>$member->id,
            'nominee_name'=>'Nominee Test',
            'identification_type'=>'citizenship',
            'identification_number'=>'123456789',
            'contact_number'=>'0123456789',
            'relationship'=>'master'
        ]);

        \App\Models\Members\MemberBankInfo::create([
            'member_id' => $member->id,
            'bank_name' => 'Test Bank',
            'acc_name' => 'Root Member',
            'acc_number' => '00085436956665',
            'contact_number' => '9845663546'
        ]);
        $details2 = [
//                'salutation'=>'Mr.',
            'surname' => 'Customer',
            'name' => 'Root',
            'identification_type' => 'citizenship',
            'identification_number' => '1038/40638',
            'user_name' => 'customer',
            'email' => 'customer@mlm.com',
            'password' => bcrypt('password'),
            'transaction_password' => bcrypt('password'),
            'is_member' => 0,
            'contact_number' => '9849898311',
            'dob' => '1994-04-01',
            'country_id' => 8,
            'gender' => 'Male',
            'city' => 'Kathmandu',
            'address' => 'Old Baneshwor, 12 Street',
            'marital_status' => 'no',
            'joining_date' => '2019-01-01',
            'qr_image' => 'user2.png',
        ];
        $customer = \App\Models\User::create($details2);

//        $faker = Faker::create();
//        for ($i = 0; $i < 20; $i++) {
//            $details1 = [
////                'salutation'=>'Mr.',
//                'surname' => $faker->name,
//                'name' => $faker->name,
//                'identification_type' => 'passport',
//                'identification_number' => rand(10000, 99999),
//                'user_name' => 'test' . $i,
//                'email' => 'test@mlm.com',
//                'password' => bcrypt('password'),
//                'transaction_password' => bcrypt('password'),
//                'is_member' => rand(0, 1),
//                'contact_number' => '9849898341',
//                'dob' => '1992-04-01',
//                'country_id' => rand(1, 10),
//                'gender' => 'Female',
//                'city' => 'London',
//                'address' => 'Old Baneshwor, 12 Street',
//                'marital_status' => 'yes',
//                'joining_date' => '2019-02-01'
//            ];
//            \App\Models\User::create($details1);
//        }
    }
}
