<?php

use Illuminate\Database\Seeder;

class ChangePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//
//
//View Permission : 1

//        Member Master
//        \Spatie\Permission\Models\Permission::create(['name' => '1.Member Master.Add New Member', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '1.Member Master.List', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '1.Member Master.Profile', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '1.Member Master.Password', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '1.Member Master.Placement Tree', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '1.Member Master.Upgrade Membership', 'guard_name' => 'admin']);
//
//        E-commerce
        \Spatie\Permission\Models\Permission::create(['name' => '1.E-Commerce.Category', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '1.E-Commerce.Member Cash Withdrawal Request', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '1.E-Commerce.Merchant Cash Withdrawal Request', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '1.E-Commerce.Featured Product Request', 'guard_name' => 'admin']);

//        Merchant Master
        \Spatie\Permission\Models\Permission::create(['name' => '1.Merchant Master.Add New Merchant', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '1.Merchant Master.List', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '1.Merchant Master.Profile', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '1.Merchant Master.Password', 'guard_name' => 'admin']);

//Reports
//        \Spatie\Permission\Models\Permission::create(['name' => '1.Reports.Member Cash Withdrawal Request', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '1.Reports.Merchant Cash Withdrawal Request', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '1.Reports.Member Wallet Convert Report', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '1.Reports.Member Wallet Transfer Report', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '1.Reports.Merchant Wallet Transfer Report', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '1.Reports.Merchant Payment Report', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '1.Reports.Monthly Bonus Report', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '1.Reports.Shopping Point Transform Report', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '1.Reports.Dividend Transform Report', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '1.Reports.Product Purchase Report', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '1.Reports.Grant Wallet/ Retain Wallet Report', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '1.Reports.Asia Bonus', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '1.Reports.HK Bonus', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '1.Reports.Top Shopper', 'guard_name' => 'admin']);

        //Customer
        \Spatie\Permission\Models\Permission::create(['name' => '1.Customer.List', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '1.Customer.Profile', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '1.Customer.Password', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '1.Customer.Banners', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '1.Customer.Subscribers', 'guard_name' => 'admin']);

//
//
//Edit Permission : 2

//        Member Master
//        \Spatie\Permission\Models\Permission::create(['name' => '2.Member Master.Add New Member', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '2.Member Master.List', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '2.Member Master.Profile', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '2.Member Master.Password', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '2.Member Master.Placement Tree', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '2.Member Master.Upgrade Membership', 'guard_name' => 'admin']);

//        E-commerce
        \Spatie\Permission\Models\Permission::create(['name' => '2.E-Commerce.Category', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '2.E-Commerce.Member Cash Withdrawal Request', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '2.E-Commerce.Merchant Cash Withdrawal Request', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '2.E-Commerce.Featured Product Request', 'guard_name' => 'admin']);

//        Merchant Master
        \Spatie\Permission\Models\Permission::create(['name' => '2.Merchant Master.Add New Merchant', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '2.Merchant Master.List', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '2.Merchant Master.Profile', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '2.Merchant Master.Password', 'guard_name' => 'admin']);

//Reports
//        \Spatie\Permission\Models\Permission::create(['name' => '2.Reports.Member Cash Withdrawal Request', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '2.Reports.Merchant Cash Withdrawal Request', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '2.Reports.Member Wallet Convert Report', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '2.Reports.Member Wallet Transfer Report', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '2.Reports.Merchant Wallet Transfer Report', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '2.Reports.Merchant Payment Report', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '2.Reports.Monthly Bonus Report', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '2.Reports.Shopping Point Transform Report', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '2.Reports.Dividend Transform Report', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '2.Reports.Product Purchase Report', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '2.Reports.Grant Wallet/ Retain Wallet Report', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '2.Reports.Asia Bonus', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '2.Reports.HK Bonus', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => '2.Reports.Top Shopper', 'guard_name' => 'admin']);

        //Customer
        \Spatie\Permission\Models\Permission::create(['name' => '2.Customer.List', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '2.Customer.Profile', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '2.Customer.Password', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '2.Customer.Banners', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '2.Customer.Subscribers', 'guard_name' => 'admin']);

    }
}
