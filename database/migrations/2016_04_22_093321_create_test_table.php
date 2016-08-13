<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('test')) {
            Schema::create('test', function(Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('author_id');
                $table->string('title', 50);
                $table->text('description');
                $table->time('length');
                $table->float('version');
                $table->float('average_score');
                $table->string('test_photo');
                $table->string('start_message');
                $table->string('completion_message');

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
        Schema::drop('test');
    }

}
