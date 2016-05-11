<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->dropColumn('client_id');
            $table->dropColumn('accounts_id');
            $table->dropColumn('user_status_detail');
            $table->dropColumn('user_avatar');
            $table->dropColumn('timezone_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user', function($table){
            $table->string('username');
            $table->bigInteger('client_id');
            $table->bigInteger('accounts_id');
            $table->longText('user_status_detail');
            $table->string('user_avatar');
            $table->bigInteger('timezone_id');
        });
    }
}
