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
        
        $assignProjectPermission = Permission::create([
                    'name' => 'Assign Projects',
                    'slug' => 'assign.projects',
                    'description' => 'Projects', // optional
                    'model' => 'App\Models\Project'
        ]);
        
        //Permissions for Briefcases
        $viewBriefCasePermission = Permission::create([
                    'name' => 'View Briefcases',
                    'slug' => 'view.briefcases',
                    'description' => 'Projects', // optional
                    'model' => 'App\Models\Project'
        ]);
        
        $createBriefCasePermission = Permission::create([
                    'name' => 'Create Briefcases',
                    'slug' => 'create.briefcases',
                    'description' => 'Projects', // optional
                    'model' => 'App\Models\Project'
        ]);
        
        $editBriefCasePermission = Permission::create([
                    'name' => 'Edit Briefcases',
                    'slug' => 'edit.briefcases',
                    'description' => 'Projects', // optional
                    'model' => 'App\Models\Project'
        ]);
        
        $deleteBriefCasePermission = Permission::create([
                    'name' => 'Delete Briefcases',
                    'slug' => 'delete.briefcases',
                    'description' => 'Projects', // optional
                    'model' => 'App\Models\Project'
        ]);
        
        //Permissions for Task List Items
        $viewTaskListItemsPermission = Permission::create([
                    'name' => 'View Tasks',
                    'slug' => 'view.tasks',
                    'description' => 'Projects', // optional
                    'model' => 'App\Models\Task'
        ]);
        
        $createTaskListItemsPermission = Permission::create([
                    'name' => 'Create Tasks',
                    'slug' => 'create.tasks',
                    'description' => 'Projects', // optional
                    'model' => 'App\Models\Task'
        ]);
        
        $editTaskListItemsPermission = Permission::create([
                    'name' => 'Edit Tasks',
                    'slug' => 'edit.tasks',
                    'description' => 'Projects', // optional
                    'model' => 'App\Models\Task'
        ]);
        
        $deleteTaskListItemsPermission = Permission::create([
                    'name' => 'Delete Tasks',
                    'slug' => 'delete.tasks',
                    'description' => 'Projects', // optional
                    'model' => 'App\Models\Task'
        ]);
        
        
        
        //Permissions for Jobs
        $viewJobPermission = Permission::create([
                    'name' => 'View Jobs',
                    'slug' => 'view.jobs',
                    'description' => 'Jobs', // optional
                    'model' => 'App\Models\Job'
        ]);
        
        $createJobPermission = Permission::create([
                    'name' => 'Create Jobs',
                    'slug' => 'create.jobs',
                    'description' => 'Jobs', // optional
                    'model' => 'App\Models\Job'
        ]);
        
        $editJobPermission = Permission::create([
                    'name' => 'Edit Jobs',
                    'slug' => 'edit.jobs',
                    'description' => 'Jobs', // optional
                    'model' => 'App\Models\Job'
        ]);
        
        $deleteJobPermission = Permission::create([
                    'name' => 'Delete Jobs',
                    'slug' => 'delete.jobs',
                    'description' => 'Jobs', // optional
                    'model' => 'App\Models\Job'
        ]);
        
        $shareJobPermission = Permission::create([
                    'name' => 'Share Jobs',
                    'slug' => 'share.jobs',
                    'description' => 'Jobs', // optional
                    'model' => 'App\Models\Job'
        ]);
        
        //Permissions for Employees
        $viewEmployeesPermission = Permission::create([
                    'name' => 'View Employees',
                    'slug' => 'view.employees',
                    'description' => 'Employees', // optional
                    'model' => 'App\Models\User'
        ]);
        
        $createEmployeesPermission = Permission::create([
                    'name' => 'Create Employees',
                    'slug' => 'create.employees',
                    'description' => 'Employees', // optional
                    'model' => 'App\Models\User'
        ]);
        
        $editEmployeesPermission = Permission::create([
                    'name' => 'Edit Employees',
                    'slug' => 'edit.employees',
                    'description' => 'Employees', // optional
                    'model' => 'App\Models\User'
        ]);
        
        $removeEmployeesPermission = Permission::create([
                    'name' => 'Remove Employees',
                    'slug' => 'remove.employees',
                    'description' => 'Employees', // optional
                    'model' => 'App\Models\User'
        ]);
        
        //Permissions for Tests
        $viewTestPermission = Permission::create([
                    'name' => 'View Tests',
                    'slug' => 'view.tests',
                    'description' => 'Tests', // optional
                    'model' => 'App\Models\Test'
        ]);
        
        $createTestPermission = Permission::create([
                    'name' => 'Create Tests',
                    'slug' => 'create.tests',
                    'description' => 'Tests', // optional
                    'model' => 'App\Models\Test'
        ]);
        
        $editTestPermission = Permission::create([
                    'name' => 'Edit Tests',
                    'slug' => 'edit.tests',
                    'description' => 'Tests', // optional
                    'model' => 'App\Models\Test'
        ]);
        
        $deleteTestPermission = Permission::create([
                    'name' => 'Delete Tests',
                    'slug' => 'delete.tests',
                    'description' => 'Tests', // optional
                    'model' => 'App\Models\Test'
        ]);
        
        $assignTestPermission = Permission::create([
                    'name' => 'Assign Tests',
                    'slug' => 'assign.tests',
                    'description' => 'Tests', // optional
                    'model' => 'App\Models\Test'
        ]);
        
          //Permissions for Positions
        $viewRolePermission = Permission::create([
                    'name' => 'View Positions',
                    'slug' => 'view.positions',
                    'description' => 'Positions', // optional
                    'model' => 'App\Models\Role'
        ]);
        
        $createRolePermission = Permission::create([
                    'name' => 'Create Positions',
                    'slug' => 'create.positions',
                    'description' => 'Positions', // optional
                    'model' => 'App\Models\Role'
        ]);
        
        $editRolePermission = Permission::create([
                    'name' => 'Edit Positions',
                    'slug' => 'edit.positions',
                    'description' => 'Positions', // optional
                    'model' => 'App\Models\Role'
        ]);
        
        $deleteRolePermission = Permission::create([
                    'name' => 'Delete Positions',
                    'slug' => 'delete.positions',
                    'description' => 'Positions', // optional
                    'model' => 'App\Models\Role'
        ]);
        
        $assignRolePermission = Permission::create([
                    'name' => 'Assign Positions',
                    'slug' => 'assign.positions',
                    'description' => 'Positions', // optional
                    'model' => 'App\Models\Role'
        ]);
        
    }

}
