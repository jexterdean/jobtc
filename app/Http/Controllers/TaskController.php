<?php

namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use Bican\Roles\Exceptions\RoleDeniedException;
use Illuminate\Http\Request;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskTimer;

use View;
use Auth;
use Redirect;
use Validator;
use Input;
use \DB;

class TaskController extends BaseController
{

    function __construct()
    {
        //Only staff and admin can access
        if(parent::hasRole('client')){
            throw  new RoleDeniedException('Client or Admin');
        }
    }

    /**
     * @return mixed
     */
    public function index()
    {

        if (parent::hasRole('staff')) {
            $tasks = Task::where('username', '=', Auth::user()->username)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $tasks = Task::orderBy('created_at', 'desc')
                ->get();
        }

        $belongsTo = 'task';


        $assign_username = User::orderBy('name')
            ->lists('id', 'name');

        $assets = ['calendar','table'];

        return View::make('task.index', [
            'assets' => $assets,
            'tasks' => $tasks,
            'belongs_to'=> $belongsTo,
            'isClient'=> parent::hasRole('client'),
            'assign_username' => $assign_username
        ]);
    }

    public function show($id)
    {
        //
        $task = [];
        if (parent::userHasRole('Admin')){
            $task = Task::find($id);
        }
        elseif (parent::userHasRole('Client')) {
            $task = DB::table('task')
                ->join('user', 'user.client_id', '=', 'task.client_id')
                ->where('user_id', '=', Auth::user()->user_id)
                ->where('task_id', '=', $id)
                ->first();
        } elseif (parent::userHasRole('Staff')) {
            $task = DB::table('task')
                ->join('assigned_user', 'assigned_user.unique_id', '=', 'project.project_id')
                ->where('belongs_to', '=', 'project')
                ->where('username', '=', Auth::user()->username)
                ->where('project_id', '=', $id)
                ->first();
        }
        $task_timer = DB::table('task_timer')
            ->leftJoin('user', 'task_timer.user_id', '=', 'user.user_id')
            ->leftJoin('task', 'task_timer.task_id', '=', 'task.task_id')
            ->select('task_timer.*', 'user.name', 'user.username', 'task.task_title')
            ->where('task_timer.task_id', '=', $id)
            ->get();
        $assets = ['calendar'];

        return view('task.show', [
            'task'=> $task,
            'assets' => $assets,
            'task_timer' => $task_timer
        ]);
    }

    public function create()
    {
    }

    public function edit($id)
    {
        $task = Task::find($id);

        $assign_username = User::orderBy('name')
            ->lists('id', 'name');

        if(count($task) > 0){

            return view('task.edit', [
                'task'=> $task,
                'isClient'=> parent::hasRole('client'),
                'assign_username'=>$assign_username
            ]);
        }

    }

    public function store(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'task_title' => 'required',
            'belongs_to' => 'required',
            'unique_id' => 'required',
            'is_visible' => 'required|in:yes,no'
        ]);

        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }

        $task = new Task;
        $data = Input::all();
        $data['task_status'] = 'pending';
        $data['due_date'] = date("Y-m-d H:i:s", strtotime($data['due_date']));
        $data['username'] = Input::get('username','Open');

        $task->fill($data);
        $task->save();

        return Redirect::back()->withSuccess('Successfully added!!');
    }

    public function updateTaskStatus()
    {

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

    public function update(Request $request, $id)
    {

        $task = Task::find($id);

        $data = $request->all();
        $data['due_date'] =date("Y-m-d H:i:s", strtotime($data['due_date']));

        $task->update($data);

        return redirect()->route('task.index');
    }

    public function destroy($task_id)
    {
        $task = Task::find($task_id);
        if (!$task) {
            return Redirect::back()->withErrors('This is not a valid link!!');
        }
        $task->delete($task_id);

        return Redirect::back()->withSuccess('Deleted successfully!!');
    }

    public function taskTimer(Request $request,$id){
        $taskTimer = new TaskTimer($request->all());
        $taskTimer->save();

        $data = DB::table('task_timer')
            ->leftJoin('user', 'task_timer.user_id', '=', 'user.user_id')
            ->leftJoin('task', 'task_timer.task_id', '=', 'task.task_id')
            ->select('task_timer.*', 'user.name', 'user.username', 'task.task_title')
            ->where('task_timer.task_id', '=', $id)
            ->get();

        return json_encode($data);
    }

    public function updateTaskTimer(Request $request,$id){
        $taskTimer = new TaskTimer($request->all());
        $taskTimer->save();

        $data = DB::table('task_timer')
            ->leftJoin('user', 'task_timer.user_id', '=', 'user.user_id')
            ->leftJoin('task', 'task_timer.task_id', '=', 'task.task_id')
            ->select('task_timer.*', 'user.name', 'user.username', 'task.task_title')
            ->where('task_timer.task_id', '=', $id)
            ->get();

        return json_encode($data);
    }

    public function deleteTaskTimer($id)
    {
        $task = TaskTimer::find($id);
        $task->delete($id);

        return Redirect::back()->withSuccess('Deleted successfully!!');
    }
}

?>