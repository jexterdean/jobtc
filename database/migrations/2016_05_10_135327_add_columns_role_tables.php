<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsRoleTables extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasColumn('company_id', 'company_division_id')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->bigInteger('company_id')->after('id');
                $table->bigInteger('company_division_id')->after('company_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('roles', function(Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('company_division_id');
        });
    }

}
