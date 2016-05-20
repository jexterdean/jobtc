<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserTableAddSocialMedia extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasColumn('skype', 'facebook', 'linkedin')) {
            Schema::table('user', function(Blueprint $table) {
                $table->string('skype')->nullable()->after('country_id');
                $table->string('facebook')->nullable()->after('skype');
                $table->string('linkedin')->nullable()->after('facebook');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('user', function(Blueprint $table) {
            $table->dropColumn('skype');
            $table->dropColumn('facebook');
            $table->dropColumn('linkedin');
        });
    }

}
