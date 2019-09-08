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


    }
}
