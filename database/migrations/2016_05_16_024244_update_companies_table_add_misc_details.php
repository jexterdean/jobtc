<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCompaniesTableAddMiscDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            //$table->integer('user_id')->unsigned()->after('company_id');
            $table->string('number_of_employees')->after('phone')->default('1');
            $table->string('address_2')->nullable()->after('address');
            $table->string('province')->nullable()->after('address_2');
            $table->string('zipcode')->nullable()->after('province');
            $table->string('website')->nullable()->after('zipcode');
        });
        
        Schema::table('companies',function(Blueprint $table) {
           $table->renameColumn('address','address_1');
           $table->renameColumn('country','country_id');
           
        });
        
        Schema::table('companies',function(Blueprint $table) {
           $table->integer('country_id')->change(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->renameColumn('address_1','address');
            $table->renameColumn('country_id','country');
            $table->dropColumn('number_of_employees');
            $table->dropColumn('address_2');
            $table->dropColumn('province');
            $table->dropColumn('zipcode');
            $table->dropColumn('website');
        });
        
        Schema::table('companies',function(Blueprint $table) {
           $table->string('country')->change(); 
        });
    }
}
