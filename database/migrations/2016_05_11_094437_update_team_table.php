<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTeamTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (Schema::hasColumn('user_id')) {
            Schema::table('team', function(Blueprint $table) {
                $table->renameColumn('author_id', 'user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('team', function(Blueprint $table) {
            $table->renameColumn('user_id', 'author_id');
        });
    }

}
