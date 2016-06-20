<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskChecklistPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('task_check_list_permissions')) {
         Schema::create('task_check_list_permissions',function(Blueprint $table){
            $table->increments('id')->unsigned();
            $table->integer('task_id');
            $table->integer('user_id');
            $table->integer('project_id');
            $table->timestamps();
        });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('task_checklist_permissions');
    }
}
