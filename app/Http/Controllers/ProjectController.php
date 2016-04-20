<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Requests;
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_type = Auth::user('user')->user_type;
        
        //if ( parent::userHasRole('admin'))
        if ($user_type === 1 || $user_type === 2 || $user_type === 3) { 
            $projects = Project::all();
        } elseif (parent::userHasRole('Client')) {
            $projects = DB::table('project')
                ->join('user', 'user.id', '=', 'project.client_id')
                ->where('user_id', '=', Auth::user('user')->id)
                ->get();
        } elseif ($user_type === 4) {
            $projects = DB::table('project')
                ->join('assigned_user', 'assigned_user.unique_id', '=', 'project.project_id')
                ->where('belongs_to', '=', 'project')
                ->where('username', '=', Auth::user('user')->email)
                ->get();
        }

        $user = User::orderBy('first_name', 'asc')->lists('first_name', 'email');

        $client_options = Client::orderBy('company_name', 'asc')
            ->lists('company_name', 'id')
            ->toArray();

        $assets = ['table', 'datepicker'];

        return view('project.index', [
            'projects' => $projects,
            'clients' => $client_options,
            'users' => $user,
            'assets' => $assets
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('project.create');
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
        $validation = Validator::make($request->all(), [
            'project_title' => 'required|unique:project',
            'client_id' => 'required',
            'start_date' => 'required|date_format:"d-m-Y"',
            'deadline' => 'required|date_format:"d-m-Y"|after:start_date',
            'rate_type' => 'required',
            'rate_value' => 'required|numeric'
        ]);

        if ($validation->fails()) {
            return redirect()->to('project')->withInput()->withErrors($validation->messages());
        }

        $project = new Project();
        $project->project_title = $request->get('project_title');
        $project->account = $request->get('account');
        $project->reverence = Input::get('reverence');
        $project->currency = $request->get('currency');
        $project->project_type = $request->get('project_type');
        $project->client_id = $request->get('client_id');
        $project->start_date = date("Y-m-d H:i:s", strtotime(Input::get('start_date')));
        $project->deadline = date("Y-m-d H:i:s", strtotime(Input::get('deadline')));
        $project->project_description = Input::get('project_description');
        $project->rate_type = Input::get('rate_type');
        $project->rate_value = Input::get('rate_value');
        $project->save();

        $update_project_ref = Project::find($project->project_id);
        $update_project_ref->ref_no = $project->project_id;
        $update_project_ref->save();

        return redirect()->to('project')->withSuccess("Project added successfully !!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_type = Auth::user('user')->user_type;
        
        //if ( parent::userHasRole('admin'))
        if ($user_type === 1 || $user_type === 2 || $user_type === 3) { 
        //if (parent::userHasRole('Admin')){
            $project = Project::find($id);
        }
        elseif ($user_type === 5) {
            $project = DB::table('project')
                ->join('user', 'user.id', '=', 'project.client_id')
                ->where('user_id', '=', Auth::user('user')->id)
                ->where('project_id', '=', $id)
                ->first();
        } elseif ($user_type === 4) {
            $project = DB::table('project')
                ->join('assigned_user', 'assigned_user.unique_id', '=', 'project.project_id')
                ->where('belongs_to', '=', 'project')
                ->where('username', '=', Auth::user('email')->email)
                ->where('project_id', '=', $id)
                ->first();
        }

        if (!$project)
            return redirect()->to('project')->withErrors('This is not a valid link!!');

        $assignedUser = AssignedUser::where('belongs_to', '=', 'project')
            ->where('unique_id', '=', $id)
            ->get();

        $assign_username = AssignedUser::where('belongs_to', '=', 'project')
            ->where('unique_id', '=', $id)
            ->lists('username', 'username')
            ->toArray();

        $user = User::orderBy('first_name', 'asc')
            ->lists('first_name', 'email')
            ->toArray();

        $client_options = Client::orderBy('company_name', 'asc')
            ->lists('company_name', 'id');

        $note = Note::where('belongs_to', '=', 'project')
            ->where('unique_id', '=', $id)
            ->where('username', '=', Auth::user('user')->email)
            ->first();

        $comment = DB::table('comment')
            ->where('belongs_to', '=', 'project')
            ->where('unique_id', '=', $id)
            ->join('user', 'comment.username', '=', 'user.email')
            ->orderBy('comment.created_at', 'desc')
            ->get();

        $attachment = Attachment::where('belongs_to', '=', 'project')
            ->where('unique_id', '=', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        if (!parent::userHasRole('Staff')) {
            $task = Task::where('project_id', '=', $id)
                ->leftJoin('user', 'task.user_id', '=', 'user.user_id')
                ->orderBy('created_at', 'desc')
                ->select('task.*','user.name')
                ->get();
        } else {

            $task = Task::where('belongs_to', '=', 'project')
                ->where('unique_id', '=', $id)
                ->where('user_id', '=', Auth::user('user')->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $timer = Timer::where('project_id', '=', $id)
            ->orderBy('start_time', 'desc')
            ->get();

        $timer_check = Timer::where('project_id', '=', $id)
            ->where('end_time', '=', null)
            ->first();

        $progress_option = [];
        for ($i = 0; $i <= 100; $i++)
            $progress_option[] = $i . " %";

        $assets = ['datepicker'];

        return view('project.show', [
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $project = Project::find($id);
        $client_options = Client::orderBy('company_name', 'asc')
            ->lists('company_name', 'id');

        $user = User::orderBy('first_name', 'asc')
            ->lists('first_name', 'email');

        return view('project.edit', [
            'project' => $project,
            'clients' => $client_options,
            'users' => $user
        ]);
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
        $project = Project::find($id);

        $validation = Validator::make($request->all(), [
            'project_title' => 'required|unique:project,project_title',
            'client_id' => 'required',
            'start_date' => 'required|date_format:"d-m-Y"',
            'deadline' => 'required|date_format:"d-m-Y"|after:start_date',
            'rate_type' => 'required',
            'rate_value' => 'required|numeric'
        ]);

        if ($validation->fails()) {
            return redirect()->to('project')->withErrors($validation->messages());
        }

        $project->project_title = Input::get('project_title');
        $project->account = Input::get('account');
        $project->reverence = Input::get('reverence');
        $project->currency = Input::get('currency');
        $project->project_type = Input::get('project_type');
        $project->client_id = Input::get('client_id');
        $project->start_date = date("Y-m-d H:i:s", strtotime(Input::get('start_date')));
        $project->deadline = date("Y-m-d H:i:s", strtotime(Input::get('deadline')));
        $project->project_description = Input::get('project_description');
        $project->rate_type = Input::get('rate_type');
        $project->rate_value = Input::get('rate_value');
        $project->save();

        return redirect()->to('project')->withSuccess("Project updated successfully!!");
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
        $project = Project::find($id);

        if (!$project || !parent::userHasRole('Admin'))
            return redirect()->to('project')->withErrors('This is not a valid link!!');

        DB::table('assigned_user')
            ->where('belongs_to', '=', 'project')
            ->where('unique_id', '=', $id)->delete();

        $attachments = DB::table('attachment')
            ->where('belongs_to', '=', 'project')
            ->where('unique_id', '=', $id)->get();

        foreach ($attachments as $attachment)
            File::delete('assets/attachment_files/' . $attachment->file);

        DB::table('attachment')
            ->where('belongs_to', '=', 'project')
            ->where('unique_id', '=', $id)->delete();

        DB::table('comment')
            ->where('belongs_to', '=', 'project')
            ->where('unique_id', '=', $id)->delete();

        DB::table('notes')
            ->where('belongs_to', '=', 'project')
            ->where('unique_id', '=', $id)->delete();

        DB::table('task')
            ->where('belongs_to', '=', 'project')
            ->where('unique_id', '=', $id)->delete();

        DB::table('timer')
            ->where('project_id', '=', $id)->delete();

        $project->delete();

        return redirect()->to('project')->withSuccess('Delete Successfully!!!');
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
            return redirect()->to('project')->withErrors($validation->messages());
        } elseif (!$project) {
            return redirect()->back()->withErrors('Wrong URL!!');
        } elseif ($timer_check) {
            return redirect()->back()->withErrors('Timer already started!!');
        }

        $timer = new Timer;
        $data = Input::all();
        $data['username'] = Auth::user('user')->email;
        $data['start_time'] = date("Y-m-d H:i:s", time());
        $timer->fill($data);
        $timer->save();

        return redirect()->back()->withSuccess('Successfully started!!');
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
            return redirect()->to('project')->withErrors($validation->messages());
        } elseif (!$project) {
            return redirect()->back()->withErrors('Wrong URL!!');
        } elseif (!$timer_check) {
            return redirect()->back()->withErrors('Timer already ended!!');
        } elseif (!$timer) {
            return redirect()->back()->withErrors('Wrong URL!!');
        }

        $data = Input::all();
        $data['end_time'] = date("Y-m-d H:i:s", time());
        $timer->fill($data);
        $timer->save();

        return redirect()->back()->withSuccess('Successfully ended!!');
    }

    public function deleteTimer()
    {
        $timer = Timer::find(Input::get('timer_id'));

        if (!$timer || !parent::userHasRole('Admin'))
            return redirect()->back()->withErrors('Wrong URL!!');

        $timer->delete(Input::get('timer_id'));
        return redirect()->back()->withSuccess('Deleted successfully!!');
    }

    public function updateProgress()
    {

        $project = Project::find(Input::get('project_id'));

        $validation = Validator::make(Input::all(), [
            'project_id' => 'required',
            'project_progress' => 'required|integer|max:100|min:0'
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->messages());
        } elseif (!$project) {
            return redirect()->back()->withErrors('Wrong URL!!');
        }

        $project->project_progress = Input::get('project_progress');
        $project->save();

        return redirect()->back()->withSuccess('Saved!!');

    }
}
