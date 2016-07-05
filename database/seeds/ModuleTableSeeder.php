<?php

use Illuminate\Database\Seeder;
use App\Models\Module;

class ModuleTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        //For Projects Module
        $project_module_count = Module::where('name', 'Projects')->count();
        if ($project_module_count === 0) {
            $project_module = new Module();
            $project_module->name = 'Projects';
            $project_module->save();
        }
        //For Jobs Module
        $jobs_module_count = Module::where('name', 'Jobs')->count();
        if ($jobs_module_count === 0) {
            $jobs_module = new Module();
            $jobs_module->name = 'Jobs';
            $jobs_module->save();
        }

        //For Employees Module
        $employees_module_count = Module::where('name', 'Employees')->count();
        if ($employees_module_count === 0) {
            $employees_module = new Module();
            $employees_module->name = 'Employees';
            $employees_module->save();
        }

        //For Tests Module
        $tests_module_count = Module::where('name', 'Tests')->count();
        if ($tests_module_count === 0) {
            $tests_module = new Module();
            $tests_module->name = 'Tests';
            $tests_module->save();
        }

        //For Role Module
        $roles_module_count = Module::where('name', 'Positions')->count();
        if ($roles_module_count === 0) {
            $positions_module = new Module();
            $positions_module->name = 'Positions';
            $positions_module->save();
        }
    }

}
