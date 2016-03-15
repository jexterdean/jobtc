<?php

namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\Client;
use App\Models\AssignedUser;
use App\Models\Note;
use App\Models\Attachment;
use App\Models\Task;
use App\Models\Timer;

use \DB;
use \Auth;
use \View;
use \Validator;
use \Input;
use \Redirect;

class ProjectController extends BaseController
{

    public function index(Request $request)
    {

        if ( parent::userHasRole('admin'))
            $projects = Project::all();
        elseif (parent::userHasRole('Client')) {
            $projects = DB::table('project')
                ->join('user', 'user.client_id', '=', 'project.client_id')
                ->where('user_id', '=', Auth::user()->user_id)
                ->get();
        } elseif (parent::userHasRole('Staff')) {
            $projects = DB::table('project')
                ->join('assigned_user', 'assigned_user.unique_id', '=', 'project.project_id')
                ->where('belongs_to', '=', 'project')
                ->where('username', '=', Auth::user()->username)
                ->get();
        }

        $user = User::where('client_id', '=', '')
            ->orderBy('name', 'asc')
            ->lists('name', 'username');

        $client_options = Client::orderBy('company_name', 'asc')
            ->lists('company_name', 'client_id')
        ->toArray();

        $assets = ['table', 'datepicker'];

        return View::make('project.index', [
            'projects' => $projects,
            'clients' => $client_options,
            'users' => $user,
            'assets' => $assets
        ]);
    }

    public function show($project_id)
    {

        if (parent::userHasRole('Admin')){
            $project = Project::find($project_id);
        }
        elseif (parent::userHasRole('Client')) {
            $project = DB::table('project')
                ->join('user', 'user.client_id', '=', 'project.client_id')
                ->where('user_id', '=', Auth::user()->user_id)
                ->where('project_id', '=', $project_id)
                ->first();
        } elseif (parent::userHasRole('Staff')) {
            $project = DB::table('project')
                ->join('assigned_user', 'assigned_user.unique_id', '=', 'project.project_id')
                ->where('belongs_to', '=', 'project')
                ->where('username', '=', Auth::user()->username)
                ->where('project_id', '=', $project_id)
                ->first();
        }

        if (!$project)
            return Redirect::to('project')->withErrors('This is not a valid link!!');

        $assignedUser = AssignedUser::where('belongs_to', '=', 'project')
            ->where('unique_id', '=', $project_id)
            ->get();

        $assign_username = AssignedUser::where('belongs_to', '=', 'project')
            ->where('unique_id', '=', $project_id)
            ->lists('username', 'username')
            ->toArray();

        $user = User::where('client_id', '=', '')
            ->orderBy('name', 'asc')
            ->lists('name', 'username')
            ->toArray();

        $client_options = Client::orderBy('company_name', 'asc')
            ->lists('company_name', 'client_id');

        $note = Note::where('belongs_to', '=', 'project')
            ->where('unique_id', '=', $project_id)
            ->where('username', '=', Auth::user()->username)
            ->first();

        $comment = DB::table('comment')
            ->where('belongs_to', '=', 'project')
            ->where('unique_id', '=', $project_id)
            ->join('user', 'comment.username', '=', 'user.username')
            ->orderBy('comment.created_at', 'desc')
            ->get();

        $attachment = Attachment::where('belongs_to', '=', 'project')
            ->where('unique_id', '=', $project_id)
            ->orderBy('created_at', 'desc')
            ->get();

        if (!parent::userHasRole('Staff')) {
            $task = Task::where('belongs_to', '=', 'project')
                ->where('unique_id', '=', $project_id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $task = Task::where('belongs_to', '=', 'project')
                ->where('unique_id', '=', $project_id)
                ->where('assign_username', '=', Auth::user()->username)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $timer = Timer::where('project_id', '=', $project_id)
            ->orderBy('start_time', 'desc')
            ->get();

        $timer_check = Timer::where('project_id', '=', $project_id)
            ->where('end_time', '=', null)
            ->first();

        $progress_option = [];
        for ($i = 0; $i <= 100; $i++)
            $progress_option[] = $i . " %";

        $assets = ['datepicker'];

        return View::make('project.show', [
            'project' => $project,
            'clients' => $client_options,
            'note' => $note,
            'users' => $user,
            'comments' => $comment,
            'attachments' => $attachment,
            'timers' => $timer,
            'timer_check' => $timer_check,
            'progress_option' => $progress_option,
            'tasks' => $task,
            'assignedUsers' => $assignedUser,
            'assign_username' => $assign_username,
            'assets' => $assets
        ]);
    }

    public function create()
    {
        return View::make('project.create');
    }

    public function edit(Request $request,$id)
    {
        $project = Project::find($id);

        $client_options = Client::orderBy('company_name', 'asc')
            ->lists('company_name', 'client_id');

        $user = User::where('client_id', '=', '')
            ->orderBy('name', 'asc')
            ->lists('name', 'username');

        return View::make('project.edit', [
            'project' => $project,
            'clients' => $client_options,
            'users' => $user
        ]);
    }

    public function store(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'project_title' => 'required|unique:project',
            'client_id' => 'required',
            'ref_no' => 'required|unique:project',
            'start_date' => 'required|date_format:"d-m-Y"',
            'deadline' => 'required|date_format:"d-m-Y"|after:start_date',
            'rate_type' => 'required',
            'rate_value' => 'required|numeric'
        ]);

        if ($validation->fails()) {
            return redirect()->to('project')->withInput()->withErrors($validation->messages());
        }

        $project = new Project;
        $project->project_title = $request->get('project_title');
        $project->client_id = $request->get('client_id');
        $project->ref_no = $request->get('ref_no');
        $project->start_date = date("Y-m-d H:i:s", strtotime(Input::get('start_date')));
        $project->deadline = date("Y-m-d H:i:s", strtotime(Input::get('deadline')));
        $project->project_description = Input::get('project_description');
        $project->rate_type = Input::get('rate_type');
        $project->rate_value = Input::get('rate_value');
        $project->save();

        return redirect()->to('project')->withSuccess("Project added successfully !!");
    }

    public function update($project_id)
    {
        $project = Project::find($project_id);

        $validation = Validator::make(Input::all(), [
            'project_title' => 'required|unique:project,project_title,' . $project_id . ',project_id',
            'client_id' => 'required',
            'ref_no' => 'required|unique:project,ref_no,' . $project_id . ',project_id',
            'start_date' => 'required|date_format:"d-m-Y"',
            'deadline' => 'required|date_format:"d-m-Y"|after:start_date',
            'rate_type' => 'required',
            'rate_value' => 'required|numeric'
        ]);

        if ($validation->fails()) {
            return Redirect::to('project')->withErrors($validation->messages());
        }

        $project->project_title = Input::get('project_title');
        $project->client_id = Input::get('client_id');
        $project->ref_no = Input::get('ref_no');
        $project->start_date = date("Y-m-d H:i:s", strtotime(Input::get('start_date')));
        $project->deadline = date("Y-m-d H:i:s", strtotime(Input::get('deadline')));
        $project->project_description = Input::get('project_description');
        $project->rate_type = Input::get('rate_type');
        $project->rate_value = Input::get('rate_value');
        $project->save();

        return Redirect::to('project')->withSuccess("Project updated successfully!!");
    }

    public function startTimer()
    {

        $project = Project::find(Input::get('project_id'));

        $timer_check = Timer::where('project_id', '=', Input::get('project_id'))
            ->where('end_time', '=', null)
            ->first();

        $validation = Validator::make(Input::all(), [
            'project_id' => 'required'
        ]);

        if ($validation->fails()) {
            return Redirect::to('project')->withErrors($validation->messages());
        } elseif (!$project) {
            return Redirect::back()->withErrors('Wrong URL!!');
        } elseif ($timer_check) {
            return Redirect::back()->withErrors('Timer already started!!');
        }

        $timer = new Timer;
        $data = Input::all();
        $data['username'] = Auth::user()->username;
        $data['start_time'] = date("Y-m-d H:i:s", time());
        $timer->fill($data);
        $timer->save();

        return Redirect::back()->withSuccess('Successfully started!!');
    }

    public function endTimer()
    {

        $project = Project::find(Input::get('project_id'));

        $timer_check = Timer::where('project_id', '=', Input::get('project_id'))
            ->where('end_time', '=', null)
            ->first();

        $validation = Validator::make(Input::all(), [
            'project_id' => 'required'
        ]);

        $timer = Timer::find(Input::get('timer_id'));

        if ($validation->fails()) {
            return Redirect::to('project')->withErrors($validation->messages());
        } elseif (!$project) {
            return Redirect::back()->withErrors('Wrong URL!!');
        } elseif (!$timer_check) {
            return Redirect::back()->withErrors('Timer already ended!!');
        } elseif (!$timer) {
            return Redirect::back()->withErrors('Wrong URL!!');
        }

        $data = Input::all();
        $data['end_time'] = date("Y-m-d H:i:s", time());
        $timer->fill($data);
        $timer->save();

        return Redirect::back()->withSuccess('Successfully ended!!');
    }

    public function deleteTimer()
    {
        $timer = Timer::find(Input::get('timer_id'));

        if (!$timer || !parent::userHasRole('Admin'))
            return Redirect::back()->withErrors('Wrong URL!!');

        $timer->delete(Input::get('timer_id'));
        return Redirect::back()->withSuccess('Deleted successfully!!');
    }

    public function updateProgress()
    {

        $project = Project::find(Input::get('project_id'));

        $validation = Validator::make(Input::all(), [
            'project_id' => 'required',
            'project_progress' => 'required|integer|max:100|min:0'
        ]);

        if ($validation->fails()) {
            return Redirect::back()->withErrors($validation->messages());
        } elseif (!$project) {
            return Redirect::back()->withErrors('Wrong URL!!');
        }

        $project->project_progress = Input::get('project_progress');
        $project->save();

        return Redirect::back()->withSuccess('Saved!!');

    }

    public function destroy()
    {
    }

    public function delete($project_id)
    {
        $project = Project::find($project_id);

        if (!$project || !parent::userHasRole('Admin'))
            return Redirect::to('project')->withErrors('This is not a valid link!!');

        DB::table('assigned_user')
            ->where('belongs_to', '=', 'project')
            ->where('unique_id', '=', $project_id)->delete();

        $attachments = DB::table('attachment')
            ->where('belongs_to', '=', 'project')
            ->where('unique_id', '=', $project_id)->get();

        foreach ($attachments as $attachment)
            File::delete('assets/attachment_files/' . $attachment->file);

        DB::table('attachment')
            ->where('belongs_to', '=', 'project')
            ->where('unique_id', '=', $project_id)->delete();

        DB::table('comment')
            ->where('belongs_to', '=', 'project')
            ->where('unique_id', '=', $project_id)->delete();

        DB::table('notes')
            ->where('belongs_to', '=', 'project')
            ->where('unique_id', '=', $project_id)->delete();

        DB::table('task')
            ->where('belongs_to', '=', 'project')
            ->where('unique_id', '=', $project_id)->delete();

        DB::table('timer')
            ->where('project_id', '=', $project_id)->delete();

        $project->delete();

        return Redirect::to('project')->withSuccess('Delete Successfully!!!');
    }
}

?>