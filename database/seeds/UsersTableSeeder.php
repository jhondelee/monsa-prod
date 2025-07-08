<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'parent_id'  => '1',
                'hash_id'  => '1',
                'email'    => 'admin@monsa.com',           
                'username' => 'admin',
                'password' => bcrypt('p@$$w0rd'),
                'activated'   => 1,
                'created_by'  => '1',
                'updated_by'  => '1',
            ],
            [
                'parent_id'  => '1',
                'hash_id'  => '2',
                'email'    => 'manager@monsa.com',           
                'username' => 'manager',
                'password' => bcrypt('p@$$w0rd'),
                'activated'   => 1,
                'created_by'  => '1',
                'updated_by'  => '1',
            ]        
        ]);
    }
}
