<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketitTables extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        if (!Schema::hasTable('ticketit_statuses')) {
            Schema::create('ticketit_statuses', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('color');
            });
        }
        if (!Schema::hasTable('ticketit_priorities')) {
            Schema::create('ticketit_priorities', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('color');
            });
        }
        if (!Schema::hasTable('ticketit_categories')) {
            Schema::create('ticketit_categories', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('color');
            });
        }
        if (!Schema::hasTable('ticketit_categories_users')) {
            Schema::create('ticketit_categories_users', function (Blueprint $table) {
                $table->integer('category_id')->unsigned();
                $table->integer('user_id')->unsigned();
            });
        }
        if (!Schema::hasTable('ticketit')) {
            Schema::create('ticketit', function (Blueprint $table) {
                $table->increments('id');
                $table->string('subject');
                $table->longText('content');
                $table->integer('status_id')->unsigned();
                $table->integer('priority_id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->integer('agent_id')->unsigned();
                $table->integer('category_id')->unsigned();
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('ticketit_comments')) {
            Schema::create('ticketit_comments', function (Blueprint $table) {
                $table->increments('id');
                $table->text('content');
                $table->integer('user_id')->unsigned();
                $table->integer('ticket_id')->unsigned();
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('ticketit_audits')) {
            Schema::create('ticketit_audits', function (Blueprint $table) {
                $table->increments('id');
                $table->text('operation');
                $table->integer('user_id')->unsigned();
                $table->integer('ticket_id')->unsigned();
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
        Schema::drop('ticketit_audits');
        Schema::drop('ticketit_comments');
        Schema::drop('ticketit');
        Schema::drop('ticketit_categories_users');
        Schema::drop('ticketit_categories');
        Schema::drop('ticketit_priorities');
        Schema::drop('ticketit_statuses');
    }

}
