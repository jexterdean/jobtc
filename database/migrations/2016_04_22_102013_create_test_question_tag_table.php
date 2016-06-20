<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestQuestionTagTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('test_question_tag')) {
            Schema::create('test_question_tag', function(Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('tag');

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('test_question_tag');
    }

}
