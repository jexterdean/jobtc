<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \View;
use \DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskChecklist;
use App\Models\Timer;
use App\Models\Rate;

class PayrollController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
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

    public function payrollJson() {
        header("Content-type: application/json");

        $result = array();
        $company_id = isset($_GET['company_id']) ? $_GET['company_id'] : '';
        if ($company_id) {
            $result = DB::table('employees')
                    ->where('company_id', '=', $company_id)
                    ->get();

            if (count($result) > 0) {
                foreach ($result as $v) {
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
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        $employees = Profile::with('user', 'rate')->where('company_id', $id)->get();

        $employee_ids = [];
        $project_ids = [];
        foreach ($employees as $employee) {
            array_push($employee_ids, $employee->user->user_id);
        }

        //Get the task checklists for today(Filter will start at today's day)
        $date_today = date('Y-m-d');

        $task_checklists = Timer::with(['task_checklist' => function($task_checklist_query) {
                        $task_checklist_query->with(['task' => function($task_query) {
                                $task_query->with('project')->get();
                            }])->get();
                    }])->whereIn('user_id', $employee_ids)->whereBetween('created_at', [$date_today . ' 00:00:00', $date_today . ' 23:59:59'])->get();


                foreach ($task_checklists as $task_checklist) {
                    array_push($project_ids, $task_checklist->task_checklist->task->project->project_id);
                }

                $projects = Project::whereIn('project_id', $project_ids)->where('company_id', $id)->get();

                $total_time_per_project = Timer::select(DB::raw("SEC_TO_TIME( SUM( TIME_TO_SEC( total_time ) ) ) AS timeSum, (SUM(TIME_TO_SEC( total_time )) / 3600) as hours, DATE_FORMAT(created_at,'%Y-%m-%d') as day , user_id, project_id"))->whereBetween('created_at', [$date_today . ' 00:00:00', $date_today . ' 23:59:59'])->groupBy('project_id')->groupBy('user_id')->get();

                $total_time = Timer::select(DB::raw("SEC_TO_TIME( SUM( TIME_TO_SEC( total_time ) ) ) AS timeSum, (SUM(TIME_TO_SEC( total_time )) / 3600) as hours , user_id"))->whereBetween('created_at', [$date_today . ' 00:00:00', $date_today . ' 23:59:59'])->groupBy('user_id')->get();

                $assets = ['select', 'payroll', 'assets'];

                return view('payroll.show', [
                    'assets' => $assets,
                    'employees' => $employees,
                    'task_checklists' => $task_checklists,
                    'projects' => $projects,
                    'total_time_per_project' => $total_time_per_project,
                    'total_time' => $total_time,
                    'company_id' => $id
                ]);
            }

            /**
             * Show the form for editing the specified resource.
             *
             * @param  int  $id
             * @return \Illuminate\Http\Response
             */
            public function edit($id) {
                //
            }

            /**
             * Update the specified resource in storage.
             *
             * @param  \Illuminate\Http\Request  $request
             * @param  int  $id
             * @return \Illuminate\Http\Response
             */
            public function update(Request $request, $id) {
                //
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

            public function filter(Request $request, $company_id, $filter, $date) {

                $employees = Profile::with('user', 'rate')->where('company_id', $company_id)->get();

                $employee_ids = [];
                $project_ids = [];
                foreach ($employees as $employee) {
                    array_push($employee_ids, $employee->user->user_id);
                }

                if ($filter === 'day') {

                    $task_checklists = Timer::with(['task_checklist' => function($task_checklist_query) {
                                    $task_checklist_query->with(['task' => function($task_query) {
                                            $task_query->with('project')->get();
                                        }])->get();
                                }])->whereIn('user_id', $employee_ids)->whereBetween('created_at', [$date . ' 00:00:00', $date . ' 23:59:59'])->get();


                            foreach ($task_checklists as $task_checklist) {
                                array_push($project_ids, $task_checklist->task_checklist->task->project->project_id);
                            }

                            $projects = Project::whereIn('project_id', $project_ids)->where('company_id', $company_id)->get();

                            $total_time_per_project = Timer::select(DB::raw("SEC_TO_TIME( SUM( TIME_TO_SEC( total_time ) ) ) AS timeSum, (SUM(TIME_TO_SEC( total_time )) / 3600) as hours, DATE_FORMAT(created_at,'%Y-%m-%d') as day , user_id, project_id"))->whereBetween('created_at', [$date . ' 00:00:00', $date . ' 23:59:59'])->groupBy('project_id')->groupBy('user_id')->get();

                            $total_time = Timer::select(DB::raw("SEC_TO_TIME( SUM( TIME_TO_SEC( total_time ) ) ) AS timeSum, (SUM(TIME_TO_SEC( total_time )) / 3600) as hours , user_id"))->whereBetween('created_at', [$date . ' 00:00:00', $date . ' 23:59:59'])->groupBy('user_id')->get();

                            return view('payroll.filter', [
                                'employees' => $employees,
                                'task_checklists' => $task_checklists,
                                'projects' => $projects,
                                'total_time_per_project' => $total_time_per_project,
                                'total_time' => $total_time,
                                'company_id' => $company_id
                            ]);
                        } elseif ($filter === 'week') {
                            
                        } else if ($filter === 'month') {
                            
                            $date_array = explode("-",$date);
                            $month = $date_array[0];
                            $year = $date_array[1];
                            
                              $task_checklists = Timer::with(['task_checklist' => function($task_checklist_query) {
                                    $task_checklist_query->with(['task' => function($task_query) {
                                            $task_query->with('project')->get();
                                        }])->get();
                                }])->whereIn('user_id', $employee_ids)->whereMonth('created_at','=', $month)->whereYear('created_at','=',$year)->get();


                            foreach ($task_checklists as $task_checklist) {
                                array_push($project_ids, $task_checklist->task_checklist->task->project->project_id);
                            }

                            $projects = Project::whereIn('project_id', $project_ids)->where('company_id', $company_id)->get();

                            $total_time_per_project = Timer::select(DB::raw("SEC_TO_TIME( SUM( TIME_TO_SEC( total_time ) ) ) AS timeSum, (SUM(TIME_TO_SEC( total_time )) / 3600) as hours, DATE_FORMAT(created_at,'%Y-%m-%d') as day , user_id, project_id"))->whereMonth('created_at','=', $month)->whereYear('created_at','=',$year)->groupBy('project_id')->groupBy('user_id')->get();

                            $total_time = Timer::select(DB::raw("SEC_TO_TIME( SUM( TIME_TO_SEC( total_time ) ) ) AS timeSum, (SUM(TIME_TO_SEC( total_time )) / 3600) as hours , user_id"))->whereMonth('created_at','=', $month)->whereYear('created_at','=',$year)->groupBy('user_id')->get();

                            return view('payroll.filter', [
                                'employees' => $employees,
                                'task_checklists' => $task_checklists,
                                'projects' => $projects,
                                'total_time_per_project' => $total_time_per_project,
                                'total_time' => $total_time,
                                'company_id' => $company_id
                            ]);
                            
                            
                            
                        } else if ($filter === 'year') {
                            
                              $task_checklists = Timer::with(['task_checklist' => function($task_checklist_query) {
                                    $task_checklist_query->with(['task' => function($task_query) {
                                            $task_query->with('project')->get();
                                        }])->get();
                                }])->whereIn('user_id', $employee_ids)->whereYear('created_at','=',$date)->get();


                            foreach ($task_checklists as $task_checklist) {
                                array_push($project_ids, $task_checklist->task_checklist->task->project->project_id);
                            }

                            $projects = Project::whereIn('project_id', $project_ids)->where('company_id', $company_id)->get();

                            $total_time_per_project = Timer::select(DB::raw("SEC_TO_TIME( SUM( TIME_TO_SEC( total_time ) ) ) AS timeSum, (SUM(TIME_TO_SEC( total_time )) / 3600) as hours, DATE_FORMAT(created_at,'%Y-%m-%d') as day , user_id, project_id"))->whereYear('created_at','=',$date)->groupBy('project_id')->groupBy('user_id')->get();

                            $total_time = Timer::select(DB::raw("SEC_TO_TIME( SUM( TIME_TO_SEC( total_time ) ) ) AS timeSum, (SUM(TIME_TO_SEC( total_time )) / 3600) as hours , user_id"))->whereYear('created_at','=',$date)->groupBy('user_id')->get();

                            return view('payroll.filter', [
                                'employees' => $employees,
                                'task_checklists' => $task_checklists,
                                'projects' => $projects,
                                'total_time_per_project' => $total_time_per_project,
                                'total_time' => $total_time,
                                'company_id' => $company_id
                            ]);
                            
                        }
                    }

                }
                