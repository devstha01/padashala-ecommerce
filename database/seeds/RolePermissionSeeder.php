<?php

use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Spatie\Permission\Models\Role::create(['name' => 'SuperAdmin', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Role::create(['name' => 'Admin', 'guard_name' => 'admin']);
        \Spatie\Permission\Models\Role::create(['name' => 'Staff', 'guard_name' => 'admin']);


//        \Spatie\Permission\Models\Permission::create(['name' => 'Member Master View', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'Member Master Edit', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'Ecommerce View', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'Ecommerce Edit', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'Merchant View', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'Merchant Edit', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'Staff View', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'Staff Edit', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'Report View', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'Report Edit', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'Pages View', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'Pages Edit', 'guard_name' => 'admin']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'Configurations View', 'guard_name' => 'admin']);

    }
}
