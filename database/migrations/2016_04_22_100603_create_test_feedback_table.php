<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestFeedbackTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('test_feedback')) {
            Schema::create('test_feedback', function(Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('test_id');
                $table->integer('user_id');
                $table->text('feedback');

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
        Schema::drop('test_feedback');
    }

}
