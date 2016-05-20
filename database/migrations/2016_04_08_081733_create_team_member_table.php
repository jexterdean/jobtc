<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamMemberTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('team_member')) {
            Schema::create('team_member', function(Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('created_by');
                $table->integer('project_id');
                $table->integer('user_id');

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('team_member');
    }

}
