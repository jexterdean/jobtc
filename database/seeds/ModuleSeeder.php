<?php

use Illuminate\Database\Seeder;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //For Projects Module
        $project_module = new Module();
        $project_module->name = 'Projects';
        $project_module->save();
        
        //For Jobs Module
        $jobs_module = new Module();
        $jobs_module->name = 'Jobs';
        $jobs_module->save();
        
        //For Employees Module
        $employees_module = new Module();
        $employees_module->name = 'Employees';
        $employees_module->save();
        
        //For Tests Module
        $tests_module = new Module();
        $tests_module->name = 'Tests';
        $tests_module->save();
        
        //For Role Module
        $positions_module = new Module();
        $positions_module->name = 'Positions';
        $positions_module->save();
    }
}
