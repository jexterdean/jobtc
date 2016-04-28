<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Bican\Roles\Exceptions\RoleDeniedException;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskTimer;
use App\Models\TaskChecklist;
use App\Models\Link;
use App\Models\LinkCategory;
use View;
use Auth;
use Redirect;
use Validator;
use Input;
use \DB;

class TaskController extends BaseController {

    function __construct() {
        //Only staff and admin can access
        if (parent::hasRole('client')) {
            throw new RoleDeniedException('Client or Admin');
        }
    }

    /**
     * @return mixed
     */
    public function index() {

        $user_type = Auth::user('user')->user_type;
        //if (parent::hasRole('staff')) {
        if ($user_type === 4) {

            $tasks = Task::where('username', '=', Auth::user('user')->email)
                    ->orderBy('created_at', 'desc')
                    ->get();
        } else {
            /*$tasks = Task::orderBy('created_at', 'desc')
                    ->join('user', 'task.user_id', '=', 'users.id')
                    ->select(
                            'task.*', 'users.first_name', 'user.email'
                    )
                    ->get();*/
            $tasks = Task::where('user_id', '=', Auth::user('user')->user_id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        }

        $belongsTo = 'task';

        $assign_username = User::orderBy('name')
            ->lists('name', 'user_id');

        $assets = ['calendar', 'table'];

        return View::make('task.index', [
                    'assets' => $assets,
                    'tasks' => $tasks,
                    'belongs_to' => $belongsTo,
                    'isClient' => parent::hasRole('client'),
                    'assign_username' => $assign_username
        ]);
    }

    public function show($id) {
        //
        $task = [];
        $user_type = 1;//Auth::user('user')->user_type;
        //if (parent::userHasRole('Admin')) {
        if ($user_type === 1 || $user_type === 2 || $user_type === 3) { 
            $task = Task::find($id);
        } elseif (parent::userHasRole('Client')) {
            $task = DB::table('task')
                    //->join('user', 'user.client_id', '=', 'task.client_id')
                    ->where('user_id', '=', Auth::user('user')->user_id)
                    ->where('task_id', '=', $id)
                    ->first();
        //} elseif (parent::userHasRole('Staff')) {
        } elseif ($user_type === 4) {
            $task = DB::table('task')
                    ->join('assigned_user', 'assigned_user.unique_id', '=', 'project.project_id')
                    ->where('belongs_to', '=', 'project')
                    ->where('user_id', '=', Auth::user('user')->user_id)
                    ->where('project_id', '=', $id)
                    ->first();
        }
        $task_timer = DB::table('task_timer')
                ->leftJoin('user', 'task_timer.user_id', '=', 'user.user_id')
                ->leftJoin('task', 'task_timer.task_id', '=', 'task.task_id')
                ->select(
                        'task_timer.*', 'user.name', 'user.username', 'task.task_title', DB::raw(
                                'FORMAT(TIMESTAMPDIFF(SECOND, fp_task_timer.start_time, fp_task_timer.end_time) / 3600, 2) as time'
                        ), DB::raw(
                                'TIMESTAMPDIFF(SECOND, fp_task_timer.start_time, now()) as _time'
                        )
                )
                ->where('task_timer.task_id', '=', $id)
                ->orderBy('start_time', 'desc')
                ->get();

        $current_time = DB::table('task_timer')
                ->select(
                        DB::raw(
                                'TIMESTAMPDIFF(SECOND, fp_task_timer.start_time, now()) as _time, id'
                        )
                )
                ->where('task_timer.task_id', '=', $id)
                ->where('task_timer.end_time', '=', '0000-00-00 00:00:00')
                ->first();

        $checkList = TaskChecklist::where('task_id', '=', $id)->get();
        $total_checklist = TaskChecklist::where('task_id', '=', $id)->count();
        $finish_checklist = TaskChecklist::where('is_finished', '=', 1)->where('task_id', '=', $id)->count();
        $percentage = $total_checklist > 0 ? ($finish_checklist / $total_checklist) * 100 : 0;
        $links = Link::select('links.id', 'title', 'url', 'descriptions', 'tags', 'comments', 'link_categories.name as category_name')
                ->leftJoin('link_categories', 'link_categories.id', '=', 'links.category_id')
                ->where('task_id', '=', $id)
                ->get();

        $categories = LinkCategory::all()
                ->lists('name', 'id')
                ->toArray();


        $assets = ['calendar'];

        return view('task.show', [
            'task' => $task,
            'assets' => $assets,
            'task_timer' => $task_timer,
            'checkList' => $checkList,
            'current_time' => $current_time,
            'percentage' => number_format($percentage, 2),
            'links' => $links,
            'categories' => $categories
        ]);
    }

    public function create() {
        
    }

    public function edit($id) {
        $task = Task::find($id);

        $assign_username = User::orderBy('name')
            ->lists('name', 'user_id');

        if(count($task) > 0){
            return view('task.edit', [
                'task' => $task,
                'isClient' => parent::hasRole('client'),
                'assign_username' => $assign_username
            ]);
        }
    }

    public function store(Request $request) {

        $validation = Validator::make($request->all(), [
                    'task_title' => 'required',
                    'belongs_to' => 'required',
                    'unique_id' => 'required'
        ]);

        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }

        $task = new Task;
        $data = Input::all();
        $data['task_status'] = 'pending';
        $data['due_date'] = date("Y-m-d H:i:s", strtotime($data['due_date']));

        $data['user_id'] = Input::get('user_id', 'Open');
        
        $task->fill($data);
        $task->save();

        return Redirect::back()->withSuccess('Successfully added!!');
    }

    public function updateTaskStatus() {

        $task = Task::find(Input::get('task_id'));
        $validation = Validator::make(Input::all(), [
                    'task_id' => 'required',
                    'task_status' => 'required|in:pending,progress,completed'
        ]);

        if ($validation->fails()) {
            return Redirect::back()->withErrors($validation->messages());
        } elseif (!$task) {
            return Redirect::back()->withErrors('Wrong URL!!');
        }

        $task->task_status = Input::get('task_status');
        $task->save();

        return Redirect::back()->withSuccess('Saved!!');
    }

    public function update(Request $request, $id) {

        $task = Task::find($id);

        $data = $request->all();
        $data['due_date'] = date("Y-m-d H:i:s", strtotime($data['due_date']));

        $task->update($data);

        return Redirect::back()->withSuccess('Updated successfully!!');
    }

    public function destroy($task_id) {
        $task = Task::find($task_id);
        if (!$task) {
            return Redirect::back()->withErrors('This is not a valid link!!');
        }
        $task->delete($task_id);

        return Redirect::back()->withSuccess('Deleted successfully!!');
    }

    public function taskTimer(Request $request, $id) {
        $input = $request->except(['is_finished']);
        $taskTimer = new TaskTimer($input);
        $taskTimer->save();

        $data['table'] = DB::table('task_timer')
                ->leftJoin('user', 'task_timer.user_id', '=', 'user.user_id')
                ->leftJoin('task', 'task_timer.task_id', '=', 'task.task_id')
                ->select(
                        'task_timer.*', 'user.name', 'user.username', 'task.task_title', DB::raw(
                                'FORMAT(TIMESTAMPDIFF(SECOND, fp_task_timer.start_time, fp_task_timer.end_time) / 3600, 2) as time'
                        )
                )
                ->where('task_timer.task_id', '=', $id)
                ->orderBy('start_time', 'desc')
                ->get();

        $data['return_task_timer'] = $taskTimer->id;
        return json_encode($data);
    }

    public function updateTaskTimer(Request $request, $id) {
        $taskTimer = TaskTimer::find($id);
        $taskTimer->update($request->all());

        $data['table'] = DB::table('task_timer')
                ->leftJoin('user', 'task_timer.user_id', '=', 'user.user_id')
                ->leftJoin('task', 'task_timer.task_id', '=', 'task.task_id')
                ->select(
                        'task_timer.*', 'user.name', 'user.username', 'task.task_title', DB::raw(
                                'FORMAT(TIMESTAMPDIFF(SECOND, fp_task_timer.start_time, fp_task_timer.end_time) / 3600, 2) as time'
                        )
                )
                ->where('task_timer.task_id', '=', $taskTimer->task_id)
                ->orderBy('start_time', 'desc')
                ->get();

        return json_encode($data);
    }

    public function deleteTaskTimer($id) {
        $task = TaskTimer::find($id);
        $task->delete($id);

        return Redirect::back()->withSuccess('Deleted successfully!!');
    }

    public function getChecklist(Request $request) {
        $tasklist = TaskChecklist::where('task_id', $request->task_id)->get();
        
        return json_encode($tasklist);
    }
    
    public function checkList(Request $request) {
        $taskCheckList = new TaskChecklist($request->all());
        $taskCheckList->save();
        $data = TaskChecklist::where('task_id', '=', $request->task_id)->get();

        return json_encode($data);
    }

    public function updateCheckList(Request $request, $id){
        $taskCheckList = TaskChecklist::find($id);
        $data = $request->all();
        $data['is_finished'] = Input::get('is_finished') != 0 ? 1 : 0;

        $taskCheckList->update($data);

        return json_encode($taskCheckList);
    }

    public function deleteCheckList($id){
        $checkList = TaskChecklist::find($id);
        $checkList->delete($id);

        return Redirect::back()->withSuccess('Deleted successfully!!');
    }
}

?>