<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPayrollSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_payroll_setting', function(Blueprint $table){
            $table->increments('id')->unsigned();
            $table->integer('user_id');
            $table->string('currency', 10);
            $table->float('hourly_rate');
            $table->string('paypal_email');
            $table->integer('pay_period_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_payroll_setting');
    }
}
