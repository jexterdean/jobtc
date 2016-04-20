<?php

use Illuminate\Database\Seeder;

class PayPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pay_period')->insert(array(
            array('period' => 'Monthly'),
            array('period' => 'Biweekly')
        ));
    }
}
