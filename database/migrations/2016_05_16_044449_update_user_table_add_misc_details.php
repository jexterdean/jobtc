<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserTableAddMiscDetails extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasColumn('address_1', 'address_2', 'zipcode', 'country_id')) {
            Schema::table('user', function (Blueprint $table) {
                $table->string('address_1')->after('photo');
                $table->string('address_2')->nullable()->after('address_1');
                $table->string('zipcode')->after('address_2');
                $table->integer('country_id')->unsigned()->after('zipcode');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn('address_1');
            $table->dropColumn('address_2');
            $table->dropColumn('zipcode');
            $table->dropColumn('country_id');
        });
    }

}
