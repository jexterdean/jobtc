<?php

use Illuminate\Database\Seeder;
use Bican\Roles\Models\Permission;

class PermissionsTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        //Permissions for Projects
        $viewProjectPermission = Permission::create([
                    'name' => 'View Projects',
                    'slug' => 'view.projects',
                    'description' => 'Projects', // optional
                    'model' => 'App\Models\Project'
        ]);
        
        $createProjectPermission = Permission::create([
                    'name' => 'Create Projects',
                    'slug' => 'create.projects',
                    'description' => 'Projects', // optional
                    'model' => 'App\Models\Project'
        ]);
        
        $editProjectPermission = Permission::create([
                    'name' => 'Edit Projects',
                    'slug' => 'edit.projects',
                    'description' => 'Projects', // optional
                    'model' => 'App\Models\Project'
        ]);
        
        $deleteProjectPermission = Permission::create([
                    'name' => 'Delete Projects',
                    'slug' => 'delete.projects',
                    'description' => 'Projects', // optional
                    'model' => 'App\Models\Project'
        ]);
    }

}
