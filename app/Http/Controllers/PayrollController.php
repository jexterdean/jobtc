<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \View;
use \DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use App\Models\TaskChecklist;
use App\Models\Timer;



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

        $c = DB::table('companies')
            ->select('id', 'name')
            ->get();
        $company = array_pluck($c, 'name', 'id');

        return View::make('payroll.default', [
            'assets' => $assets,
            'company' => $company
        ]);
    }

    public function payrollJson(){
        header("Content-type: application/json");

        $result = array();
        $company_id = isset($_GET['company_id']) ? $_GET['company_id'] : '';
        if($company_id) {
            $result = DB::table('employees')
                ->where('company_id', '=', $company_id)
                ->get();

            if(count($result) > 0){
                foreach($result as $v){
                    $v->payroll = DB::table('task_timer')
                        ->select(DB::raw(
                            'fp_task.task_title,
                            CONCAT(
                                DATE_FORMAT(fp_task_timer.start_time, "%a %d"),
                                ", ",
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
                        ->where('task_timer.user_id', '=', $v->user_id)
                        ->get();
                }
            }
        }

        return response()->json($result);
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
        
        $employees = Profile::with('user')->where('company_id',$id)->get();
        
        $employee_ids = [];
        foreach($employees as $employee) {
            array_push($employee_ids,$employee->user->user_id);
        }
        
        $tasks = Timer::with('task_checklist')->whereIn('user_id',$employee_ids)->get();
        
        $total_time = Timer::select(DB::raw("SEC_TO_TIME( SUM( TIME_TO_SEC( total_time ) ) ) AS timeSum, user_id"))->groupBy('user_id')->get();
        
        $assets = ['assets'];
        
        return view('payroll.show',[
            'assets' => $assets,
            'employees' => $employees,
            'tasks' => $tasks,
            'total_time' => $total_time
        ]);
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
