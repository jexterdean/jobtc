<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTeamMemberTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasColumn('project_id', 'team_id')) {
            Schema::table('team_member', function(Blueprint $table) {
                $table->renameColumn('project_id', 'team_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('team_member', function(Blueprint $table) {
            $table->renameColumn('team_id', 'project_id');
        });
    }

}
