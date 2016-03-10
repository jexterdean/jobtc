<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;

class AssignedController extends BaseController
{

    public function index()
    {
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

    public function store()
    {
        $validation = Validator::make(Input::all(), [
            'username' => 'required|unique:fp_assigned_user,username,null,username,belongs_to,' . Input::get('belongs_to') . ',unique_id,' . Input::get('unique_id')
        ]);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }

        $assignedUser = new Assigned_User;
        $data = Input::all();
        $assignedUser->fill($data);
        $assignedUser->save();
        return Redirect::back()->withSuccess('Assigned to selected user!!');
    }

    public function update()
    {
    }

    public function destroy($id)
    {

        if (!Entrust::hasRole('Admin'))
            return Redirect::back()->withErrors('You dont have permission of this operation!!');

        $assignedUser = Assigned_User::find($id);
        if (!$assignedUser) {
            return Redirect::back() > withErrors('This is not a valid link!!');
        }

        $task = Task::where('unique_id', '=', $assignedUser->unique_id)
            ->where('belongs_to', '=', $assignedUser->belongs_to)
            ->where('assign_username', '=', $assignedUser->username)
            ->get();

        if (count($task)) {
            return Redirect::back()->withErrors('This user has already assigned a task!!');
        }

        $assignedUser->delete($id);
        return Redirect::back()->withSuccess('Deleted successfully!!');
    }
}

?>