<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [                        
            ['level' => '1', 'name' => 'admin',              'display_name' => 'Administrator'],       

            ['level' => '2', 'name' => 'manager',                'display_name' => 'Manager'],
        ];
        
        DB::table('roles')->insert($roles);
        
        DB::table('user_role')->insert([
            ['user_id' => 1, 'role_id' => 1],
            ['user_id' => 2, 'role_id' => 2],
        ]);
    }
}
