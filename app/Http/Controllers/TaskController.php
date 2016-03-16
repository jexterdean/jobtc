<?php

namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

use App\Models\Task;


use View;
use Auth;
use Redirect;
use Validator;
use Input;

class TaskController extends BaseController
{


    /**
     * @return mixed
     */
    public function index()
    {

        if (parent::hasRole('staff')) {
            $task = Task::where('username', '=', Auth::user()->username)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $task = Task::orderBy('created_at', 'desc')
                ->get();
        }


        $assign_username = User::where('client_id', '=', '')
            ->orderBy('name', 'asc')
            ->lists('name', 'username');

        return View::make('task.index', [
            'tasks' => $task,
            'assign_username' => $assign_username
        ]);
    }

    public function show()
    {
    }

    public function create()
    {
    }

    public function edit()
    {
    }

    public function store(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'task_title' => 'required',
            'due_date' => 'required',
            'belongs_to' => 'required',
            'unique_id' => 'required',
            'is_visible' => 'required|in:yes,no'
        ]);

        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }

        $task = new Task;
        $data = Input::all();
//        $data['username'] = Auth::user()->username;
        $data['task_status'] = 'pending';
        $data['due_date'] = date("Y-m-d H:i:s", strtotime($data['due_date']));
        $data['username'] = Input::get('assign_username');

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

    public function update()
    {
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