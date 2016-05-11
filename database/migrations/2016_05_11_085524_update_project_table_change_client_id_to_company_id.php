<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProjectTableChangeClientIdToCompanyId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project', function (Blueprint $table) {
            $table->dropColumn('client_id');
            $table->bigInteger('company_id')->after('project_id');
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
            $table->dropColumn('company_id');
            $table->bigInteger('client_id')->after('project_id');
        });
    }
}
