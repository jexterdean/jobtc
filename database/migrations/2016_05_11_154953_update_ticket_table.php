<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('user_id')) {
        Schema::table('ticket',function(Blueprint $table){
            $table->dropColumn('username');
            $table->integer('user_id')->after('ticket_status');
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
        Schema::table('ticket',function(Blueprint $table){
            $table->dropColumn('user_id');
            $table->string('username')->after('ticket_status');
        });
    }
}
