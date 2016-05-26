<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnChecklistHeader extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasColumn('checklist_header')) {
            Schema::table('task_check_list', function (Blueprint $table) {
                $table->string('checklist_header')->after('task_id');
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
            $table->dropColumn('checklist_header');
        });
    }

}
