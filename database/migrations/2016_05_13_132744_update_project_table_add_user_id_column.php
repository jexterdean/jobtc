<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProjectTableAddUserIdColumn extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasColumn('user_id')) {
            Schema::table('project', function (Blueprint $table) {
                $table->integer('user_id')->unsigned()->after('company_id');
            });
        }
        Schema::table('project', function (Blueprint $table) {
            $table->foreign('user_id')->references('user_id')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('project', function (Blueprint $table) {
            $table->integer('user_id')->after('company_id');
        });
    }

}
