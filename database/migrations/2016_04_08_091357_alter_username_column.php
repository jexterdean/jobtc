<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsernameColumn extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        if (!Schema::hasColumn('user_id')) {
            Schema::table('task', function (Blueprint $table) {
                $table->dropColumn('username');
                $table->integer('user_id');
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
        Schema::table('task', function (Blueprint $table) {

            if (Schema::hasColumn('user_id')) {
                $table->dropColumn('user_id');
                $table->string('username', 50);
            }
        });
    }

}
