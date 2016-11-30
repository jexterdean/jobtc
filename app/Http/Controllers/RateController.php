<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Rate;
use App\Models\PayPeriod;
use App\Models\UserPayPeriod;

class RateController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('forms.addRateForm');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $user_id = $request->input('user_id');
        $company_id = $request->input('company_id');

        $profile_id = Profile::where('user_id', $user_id)->where('company_id', $company_id)->pluck('id');

        $rate_type = $request->input('rate_type');
        $rate_value = $request->input('rate_value');
        $currency = $request->input('currency');
        $pay_period = $request->input('pay_period');
        $payday = $request->input('payday');
        $profile_id = Profile::where('user_id', $user_id)->where('company_id', $company_id)->pluck('id');
        $pay_period_id = PayPeriod::where('period', $pay_period)->pluck('id');

        $rate = new Rate([
            'profile_id' => $profile_id,
            'rate_type' => $rate_type,
            'rate_value' => $rate_value,
            'currency' => $currency,
            'pay_period_id' => $pay_period_id
        ]);

        $rate->save();

        $user_pay_period = new UserPayPeriod([
            'profile_id' => $profile_id,
            'pay_period_id' => $pay_period_id,
            'payday' => $payday
        ]);
        $user_pay_period->save();

        return $rate->id;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        $rate = Rate::with('pay_period')->where('id', $id)->first();

        return view('forms.editRateForm', ['rate' => $rate]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        $user_id = $request->input('user_id');
        $company_id = $request->input('company_id');
        $rate_type = $request->input('rate_type');
        $rate_value = $request->input('rate_value');
        $currency = $request->input('currency');
        $pay_period = $request->input('pay_period');
        $payday = $request->input('payday');

        $profile_id = Profile::where('user_id', $user_id)->where('company_id', $company_id)->pluck('id');
        $pay_period_id = PayPeriod::where('period', $pay_period)->pluck('id');

        $rate = Rate::where('id', $id)->update([
            'rate_type' => $rate_type,
            'rate_value' => $rate_value,
            'currency' => $currency,
            'pay_period_id' => $pay_period_id
        ]);

        $user_pay_period = UserPayPeriod::where('profile_id', $profile_id)->update([
            'profile_id' => $profile_id,
            'pay_period_id' => $pay_period_id,
            'payday' => $payday
        ]);

        return $profile_id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

}
