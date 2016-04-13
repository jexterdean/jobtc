<?php

namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use Bican\Roles\Exceptions\RoleDeniedException;
use Illuminate\Http\Request;

use App\Models\Task;
use App\Models\User;

use View;
use Auth;
use Redirect;
use Validator;
use Input;

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

        $tasks = [];
        if (parent::hasRole('staff')) {
            $tasks = Task::where('email', '=', Auth::user('user')->email)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $tasks = Task::orderBy('created_at', 'desc')
                ->get();
        }

        $belongsTo = 'task';


        $assign_username = User::orderBy('first_name', 'asc')
            ->lists('email', 'email');

        $assets = ['calendar','table'];

        return View::make('task.index', [
            'assets' => $assets,
            'tasks' => $tasks,
            'belongs_to'=> $belongsTo,
            'isClient'=> parent::hasRole('client'),
            'assign_username' => $assign_username
        ]);
    }

    public function show()
    {
    }

    public function create()
    {
    }

    public function edit(Request $request, $id)
    {
        $task = Task::find($id);

        $assign_username = User::orderBy('first_name', 'asc')
            ->lists('email', 'email');
        if($task){

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
}

?>