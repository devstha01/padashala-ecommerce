<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = [
            'name' => 'Admin',
            'user_name' => 'admin',
            'email' => 'admin@mlm.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
            'transaction_password' => bcrypt('password')
        ];
        $id = \App\Models\Admin::create($admin);

        \Illuminate\Support\Facades\DB::table('model_has_roles')->insert(
            ['role_id' => 2,
                'model_type' => 'App\Models\Admin',
                'model_id' => $id->id]
        );

    }
}
