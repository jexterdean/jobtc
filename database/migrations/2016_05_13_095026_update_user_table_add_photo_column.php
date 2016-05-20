<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserTableAddPhotoColumn extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasColumn('photo')) {
            Schema::table('user', function (Blueprint $table) {
                $table->string('photo')->after('phone');
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
            $table->dropColumn('photo');
        });
    }

}
