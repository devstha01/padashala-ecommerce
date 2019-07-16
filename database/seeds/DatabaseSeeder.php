<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CountriesSeeder::class);
//        $this->call(PackagesSeeder::class);
        $this->call(WalletSeeder::class);
//        $this->call(PositionSeeder::class);
        $this->call(MerchantSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(UserSeeder::class);
//        $this->call(MemberStandardTree::class);
//        $this->call(MemberSpecialTree::class);
//        $this->call(MemberAutoTree::class);
        $this->call(OrderStatusSeeder::class);
//        $this->call(ReferalBonusRegister::class);
        $this->call(MemberAsset::class);
//        $this->call(ShoppingSeeder::class);
//        $this->call(ShoppingBonusSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(AdminSeeder::class);
//        $this->call(GenerationBonusDistributionSeeder::class);
//        $this->call(ChipsConfigSeeder::class);
//        $this->call(BiddingSeeder::class);
        $this->call(ColorSeeder::class);
        $this->call(ChangePermissionSeeder::class);

        //included in AdminSeeder
        //         $this->call(RoleAdminSeeder::class);
    }
}
