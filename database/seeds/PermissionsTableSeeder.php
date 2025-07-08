<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DB::table('permissions')->insert([
            [
                'route_name'     => 'main',            
                'display_name'   => 'Dashboard',
                'display_status' => 1,
                'icon_class'     => 'fa-bar-chart-o',
                'group_id' => null
            ],
            [
                'route_name'     => 'user.index',            
                'display_name'   => 'Users',
                'display_status' => 1,
                'icon_class'     => null,
                'group_id' => 1
            ],
            [
                'route_name'     => 'role.index',            
                'display_name'   => 'Roles',
                'display_status' => 1,
                'icon_class'     => null,
                'group_id' => 1
            ],
            [
                'route_name'     => 'permission.index',            
                'display_name'   => 'Routes',
                'display_status' => 1,
                'icon_class'     => null,
                'group_id' => 2
            ],
            [
                'route_name'     => 'pgroup.index',            
                'display_name'   => 'Groups',
                'display_status' => 1,
                'icon_class'     => null,
                'group_id' => 2
            ],
            [
                'route_name'     => 'user.datatable',            
                'display_name'   => '',
                'display_status' => 0,
                'icon_class'     => '',
                'group_id' => null
            ],
            [
                'route_name'     => 'role.datatable',            
                'display_name'   => '',
                'display_status' => 0,
                'icon_class'     => '',
                'group_id' => null
            ],
            [
                'route_name'     => 'permission.datatable',            
                'display_name'   => '',
                'display_status' => 0,
                'icon_class'     => '',
                'group_id' => null
            ],
            [
                'route_name'     => 'pgroup.datatable',            
                'display_name'   => '',
                'display_status' => 0,
                'icon_class'     => '',
                'group_id' => null
            ],
            [
                'route_name'     => 'user.add',            
                'display_name'   => '',
                'display_status' => 0,
                'icon_class'     => '',
                'group_id' => null
            ],
            [
                'route_name'     => 'do.user.add',            
                'display_name'   => '',
                'display_status' => 0,
                'icon_class'     => '',
                'group_id' => null
            ],
            [
                'route_name'     => 'user.edit',            
                'display_name'   => '',
                'display_status' => 0,
                'icon_class'     => '',
                'group_id' => null
            ],
            [
                'route_name'     => 'do.user.edit',            
                'display_name'   => '',
                'display_status' => 0,
                'icon_class'     => '',
                'group_id' => null
            ],
            [
                'route_name'     => 'do.user.delete',            
                'display_name'   => '',
                'display_status' => 0,
                'icon_class'     => '',
                'group_id' => null
            ],
            [
                'route_name'     => 'role.add',            
                'display_name'   => '',
                'display_status' => 0,
                'icon_class'     => '',
                'group_id' => null
            ] ,
            [
                'route_name'     => 'do.role.add',            
                'display_name'   => '',
                'display_status' => 0,
                'icon_class'     => '',
                'group_id' => null
            ] ,
            [
                'route_name'     => 'role.edit',            
                'display_name'   => '',
                'display_status' => 0,
                'icon_class'     => '',
                'group_id' => null
            ] ,
            [
                'route_name'     => 'do.role.edit',            
                'display_name'   => '',
                'display_status' => 0,
                'icon_class'     => '',
                'group_id' => null
            ] ,
            [
                'route_name'     => 'permission.add',            
                'display_name'   => '',
                'display_status' => 0,
                'icon_class'     => '',
                'group_id' => null
            ] ,
            [
                'route_name'     => 'do.permission.add',            
                'display_name'   => '',
                'display_status' => 0,
                'icon_class'     => '',
                'group_id' => null
            ] ,
            [
                'route_name'     => 'permission.edit',            
                'display_name'   => '',
                'display_status' => 0,
                'icon_class'     => '',
                'group_id' => null
            ]  ,
            [
                'route_name'     => 'do.permission.edit',            
                'display_name'   => '',
                'display_status' => 0,
                'icon_class'     => '',
                'group_id' => null
            ],
            [
                'route_name'     => 'pgroup.add',            
                'display_name'   => '',
                'display_status' => 0,
                'icon_class'     => '',
                'group_id' => null
            ],
            [
                'route_name'     => 'do.pgroup.add',            
                'display_name'   => '',
                'display_status' => 0,
                'icon_class'     => '',
                'group_id' => null
            ],
            [
                'route_name'     => 'pgroup.edit',            
                'display_name'   => '',
                'display_status' => 0,
                'icon_class'     => '',
                'group_id' => null
            ],
            [
                'route_name'     => 'do.pgroup.edit',            
                'display_name'   => '',
                'display_status' => 0,
                'icon_class'     => '',
                'group_id' => null
            ],
            [
                'route_name'     => 'do.pgroup.delete',            
                'display_name'   => '',
                'display_status' => 0,
                'icon_class'     => '',
                'group_id' => null
            ]            
        ]);
        
        DB::table('role_permission')->insert([
            [
                'role_id' => 1,
                'permission_id' => 1
            ],
            [
                'role_id' => 2,
                'permission_id' => 1
            ],
            [
                'role_id' => 2,
                'permission_id' => 2
            ],
            [
                'role_id' => 2,
                'permission_id' => 3
            ],
            [
                'role_id' => 2,
                'permission_id' => 4
            ],
            [
                'role_id' => 2,
                'permission_id' => 5
            ],
            [
                'role_id' => 2,
                'permission_id' => 6
            ],
            [
                'role_id' => 2,
                'permission_id' => 7
            ],
            [
                'role_id' => 2,
                'permission_id' => 8
            ],
            [
                'role_id' => 2,
                'permission_id' => 9
            ],
            [
                'role_id' => 2,
                'permission_id' => 10
            ] ,
            [
                'role_id' => 2,
                'permission_id' => 11
            ] ,
            [
                'role_id' => 2,
                'permission_id' => 12
            ] ,
            [
                'role_id' => 2,
                'permission_id' => 13
            ] ,
            [
                'role_id' => 2,
                'permission_id' => 14
            ] ,
            [
                'role_id' => 2,
                'permission_id' => 15
            ] ,
            [
                'role_id' => 2,
                'permission_id' => 16
            ] ,
            [
                'role_id' => 2,
                'permission_id' => 17
            ] ,
            [
                'role_id' => 2,
                'permission_id' => 18
            ] ,
            [
                'role_id' => 2,
                'permission_id' => 19
            ] ,
            [
                'role_id' => 2,
                'permission_id' => 20
            ] ,
            [
                'role_id' => 2,
                'permission_id' => 21
            ] ,
            [
                'role_id' => 2,
                'permission_id' => 22
            ] ,
            [
                'role_id' => 2,
                'permission_id' => 23
            ] ,
            [
                'role_id' => 2,
                'permission_id' => 24
            ] ,
            [
                'role_id' => 2,
                'permission_id' => 25
            ] ,
            [
                'role_id' => 2,
                'permission_id' => 26
            ] ,
            [
                'role_id' => 2,
                'permission_id' => 27
            ]       
        ]);     
    }
}

