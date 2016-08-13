<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('meeting')) {
        Schema::create('meeting',function(Blueprint $table){
            $table->increments('id')->unsigned();
            $table->integer('project_id');
            $table->integer('user_id');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('type_id');
            $table->text('description');
            $table->float('estimated_length');
            $table->integer('priority_id');
            $table->json('attendees');
            $table->text('meeting_url')->nullable();

            $table->timestamps();
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
        Schema::drop('meeting');
    }
}
