<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLinksTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('links')) {
            Schema::create('links', function(Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->string('title');
                $table->text('url');
                $table->text('descriptions');
                $table->string('tags');
                $table->text('comments');
                $table->integer('category_id')->unsigned();
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
        Schema::drop('links');
    }

}
