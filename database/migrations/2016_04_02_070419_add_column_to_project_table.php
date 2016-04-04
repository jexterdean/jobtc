<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project', function (Blueprint $table) {
            //
            $table->string('account', 100);
            $table->text('reverence');
            $table->text('currency', 100);
            $table->text('project_type', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project', function (Blueprint $table) {
            //
            $table->dropColumn('account');
            $table->dropColumn('reverence');
            $table->dropColumn('currency');
            $table->dropColumn('project_type');
        });
    }
}
