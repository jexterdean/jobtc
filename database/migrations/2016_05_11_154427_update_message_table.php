<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('message',function(Blueprint $table){
            $table->dropColumn('to_username');
            $table->dropColumn('from_username');
            $table->integer('to_user_id')->before('message_id');
            $table->integer('from_user_id')->before('to_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('message',function(Blueprint $table){
            $table->dropColumn('to_user_id');
            $table->dropColumn('from_user_id');
            $table->string('to_username')->before('message_id');
            $table->string('from_username')->before('to_username');
        });
    }
}
