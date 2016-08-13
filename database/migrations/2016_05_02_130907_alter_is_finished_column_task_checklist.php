<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterIsFinishedColumnTaskChecklist extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasColumn('status')) {
            Schema::table('task_check_list', function (Blueprint $table) {
                $table->dropColumn('is_finished');
                $table->string('status')->default('Default');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('task_check_list', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->tinyInteger('is_finished');
        });
    }

}
