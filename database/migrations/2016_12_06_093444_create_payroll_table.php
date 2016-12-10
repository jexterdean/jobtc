<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('payroll', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_pay_period_id');
            $table->string('payment_method')->default('paypal');
            $table->string('next_due');
            $table->string('status')->default('Unpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('payroll');
    }

}
