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

        $adminRole = new \Bican\Roles\Models\Role();
        $adminRole->name = 'Admin';
        $adminRole->slug = 'admin';
        $adminRole->description = 'Administrator';
        $adminRole->level = 1;
        $adminRole->save();

        $clientRole = new \Bican\Roles\Models\Role();
        $clientRole->name = 'Client';
        $clientRole->slug = 'client';
        $clientRole->description = 'Clients';
        $clientRole->level = 3;
        $clientRole->save();

        $staffRole = new \Bican\Roles\Models\Role();
        $staffRole->name = 'Staff';
        $staffRole->slug = 'staff';
        $staffRole->description = 'The staff';
        $staffRole->level = 2;
        $staffRole->save();


    }
}
