<?php

use Illuminate\Database\Seeder;

class MemberAsset extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $asset = [
            'member_id' => '1',
            'ecash_wallet' => 10000,
//            'evoucher_wallet' => 10000,
//            'r_point' => 10000,
//            'chip' => 10000,
//            'package_id' => 1,

        ];
        \App\Models\Members\MemberAsset::create($asset);

        $asset1 = [
            'member_id' => '2',
            'ecash_wallet' => 1000,
//            'evoucher_wallet' => 0,
//            'r_point' => 0,
//            'chip' => 0,
//            'package_id' => 1,
        ];
        \App\Models\Members\MemberAsset::create($asset1);



//        for ($i = 1; $i < 23; $i++) {
//            $asset1 = [
//                'member_id' => $i,
//                'ecash_wallet' => 1000,
//                'evoucher_wallet' => 1000,
//                'r_point' => 1000,
//                'chip' => 100,
//                'package_id' => 1,
//            ];
//            \App\Models\Members\MemberAsset::create($asset1);
//        }
    }
}
