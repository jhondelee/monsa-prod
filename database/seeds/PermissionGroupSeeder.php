<?php

use Illuminate\Database\Seeder;

class PermissionGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('permission_group')->insert([
            [
                'name' => 'User Management',
                'icon_class' => 'fa-key',
            ],
            [
                'name' => 'Route Management',
                'icon_class' => 'fa-map-marker',
            ]
        ]);
    }
}
