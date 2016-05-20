<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyDivisions extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('company_divisions')) {
            Schema::create('company_divisions', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('company_id')->unsigned();
                $table->string('division_name');
                $table->timestamps();
            });
        }

        //Assign Foreign keys
        Schema::table('company_divisions', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('company_divisions');
    }

}
