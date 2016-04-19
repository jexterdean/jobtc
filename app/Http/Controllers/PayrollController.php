<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \View;
use \DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assets = [];

        $u = DB::table('user')
            ->select(DB::raw('user_id, IF(name IS NULL, username, name) as name'))
            ->get();
        $user = array_pluck($u, 'name', 'user_id');

        return View::make('payroll.default', [
            'assets' => $assets,
            'user' => $user
        ]);
    }

    public function payrollJson(){
        header("Content-type: application/json");

        $payroll = array();
        $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
        if($user_id) {
            $payroll = DB::table('task_timer')
                ->select(DB::raw(
                    'DATE_FORMAT(fp_task_timer.start_time, "%d %b %Y") as date,
                    fp_task.task_title,
                    CONCAT(
                        DATE_FORMAT(fp_task_timer.start_time, "%h:%i %p"),
                        " - ",
                        DATE_FORMAT(fp_task_timer.end_time, "%h:%i %p")
                    ) as time,
                    CONCAT(
                        ROUND(ROUND(TIMESTAMPDIFF(SECOND, fp_task_timer.start_time, fp_task_timer.end_time)/3600, 2) * fp_user_payroll_setting.hourly_rate, 2),
                        " ", fp_user_payroll_setting.currency
                    ) as amount'
                ))
                ->leftJoin('task', 'task.task_id', '=', 'task_timer.task_id')
                ->leftJoin('user_payroll_setting', 'user_payroll_setting.user_id', '=', 'task_timer.user_id')
                ->where('task_timer.user_id', '=', $user_id)
                ->get();
        }

        return response()->json($payroll);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
