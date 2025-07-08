<?php

use Illuminate\Database\Seeder;

class EmployeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employees')->insert([
            [
                'user_id'    => 1,
                'emp_number' => '1',
                'firstname'  => 'Ramon',
                'lastname'   => 'Saavedra',
                'middlename' => '.',
                'position'   => 'CEO / Owner',
                'department' => 'CEO',
                'status'     => 1,

            ],
            [
                'user_id'    => 2,
                'emp_number' => '1',
                'firstname'  => 'Juan',
                'lastname'   => 'Dela Cruz',
                'middlename' => 'C',
                'position'   => 'Manager',
                'department' => 'Sales',
                'status'     => 1,

            ]
        ]);
    }
}
