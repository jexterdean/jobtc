<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaskIdColumn extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasColumn('task_id')) {
            Schema::table('links', function(Blueprint $table) {
                $table->integer('task_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //
        Schema::table('links', function(Blueprint $table) {
            $table->dropColumn('task_id');
        });
    }

}
