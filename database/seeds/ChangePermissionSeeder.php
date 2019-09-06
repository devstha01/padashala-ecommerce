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

//        E-commerce
        \Spatie\Permission\Models\Permission::create(['name' => '1.E-Commerce.Category', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '1.E-Commerce.Merchant Cash Withdrawal Request', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '1.E-Commerce.Featured Product Request', 'guard_name' => 'admin']);

//        Merchant Master
        \Spatie\Permission\Models\Permission::create(['name' => '1.Merchant Master.Add New Merchant', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '1.Merchant Master.List', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '1.Merchant Master.Product Approval List', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '1.Merchant Master.Product List', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '1.Merchant Master.Order List', 'guard_name' => 'admin']);

//Reports
        \Spatie\Permission\Models\Permission::create(['name' => '1.Reports.Merchant Cash Withdrawal Request', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '1.Reports.Product Purchase Report', 'guard_name' => 'admin']);

        //Customer
        \Spatie\Permission\Models\Permission::create(['name' => '1.Customer.List', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '1.Customer.Banners', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '1.Customer.Subscribers', 'guard_name' => 'admin']);

//
//
//Edit Permission : 2

//        E-commerce
        \Spatie\Permission\Models\Permission::create(['name' => '2.E-Commerce.Category', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '2.E-Commerce.Merchant Cash Withdrawal Request', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '2.E-Commerce.Featured Product Request', 'guard_name' => 'admin']);

//        Merchant Master
        \Spatie\Permission\Models\Permission::create(['name' => '2.Merchant Master.Add New Merchant', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '2.Merchant Master.List', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '2.Merchant Master.Product Approval List', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '2.Merchant Master.Product List', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '2.Merchant Master.Order List', 'guard_name' => 'admin']);

//Reports
        \Spatie\Permission\Models\Permission::create(['name' => '2.Reports.Merchant Cash Withdrawal Request', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '2.Reports.Product Purchase Report', 'guard_name' => 'admin']);

        //Customer
        \Spatie\Permission\Models\Permission::create(['name' => '2.Customer.List', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '2.Customer.Banners', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Permission::create(['name' => '2.Customer.Subscribers', 'guard_name' => 'admin']);

    }
}
