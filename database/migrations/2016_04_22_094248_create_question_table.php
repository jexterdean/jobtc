<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question', function(Blueprint $table){
            $table->increments('id')->unsigned();
            $table->integer('test_id');
            $table->integer('question_type_id');
            $table->string('question');
            $table->text('question_choices');
            $table->string('question_answer', 50);
            $table->time('length');
            $table->string('question_photo');
            $table->integer('score');
            $table->float('order');

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
        Schema::drop('question');
    }
}
