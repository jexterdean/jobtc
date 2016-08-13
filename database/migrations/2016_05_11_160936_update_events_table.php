<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEventsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasColumn('user_id')) {
            Schema::table('events', function(Blueprint $table) {
                $table->dropColumn('username');
                $table->integer('user_id')->after('event_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('events', function(Blueprint $table) {
            $table->dropColumn('user_id');
            $table->string('username')->after('event_id');
        });
    }

}
