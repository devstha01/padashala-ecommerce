<?php

use Illuminate\Database\Seeder;

class RoleAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('model_has_roles')->insert(
            ['role_id' => 2,
                'model_type' => 'App\Models\Admin',
                'model_id' => 1]
        );
    }
}
