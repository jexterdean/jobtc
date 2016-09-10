<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoRoomsTable extends Migration
{
  /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('video_rooms', function(Blueprint $table) {
            $table->increments('id');
            $table->string('room_name');
            $table->string('room_type');
            $table->string('streams');
            $table->string('rec_dir');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('video_rooms');
    }
}
