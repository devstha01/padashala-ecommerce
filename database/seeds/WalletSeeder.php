<?php

use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $wallets = [
            ['ecash_wallet', 'Cash Wallet'],
            ['evoucher_wallet', 'Voucher Wallet'],
            ['r_point', 'R Wallet'],
            ['bid_coin', 'Bid Coin'],
            ['chip', 'Chip']
        ];

        foreach ($wallets as $wallet) {
            \DB::table('wallets')->insert([
                'name' => $wallet[0],
                'detail' => $wallet[1]
            ]);
        }
    }
}
