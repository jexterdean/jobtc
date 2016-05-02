<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewnoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newnote', function (Blueprint $table) {
            $table->increments('note_id');
            $table->string('belongs_to', 20);
            $table->integer('unique_id');
            $table->text('note_content');
            $table->string('username', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('newnote');
    }
}
