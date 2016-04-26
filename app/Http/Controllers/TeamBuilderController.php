<?php

namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;

use App\Models\Accounts;
use App\Models\TeamMember;
use \View;
use \DB;
use \Input;
use \Redirect;
use \Auth;
use \Validator;
use App\Models\User;
use App\Models\Team;
use \Mail;
use Hash;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TeamBuilderController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assets = ['calendar', 'magicSuggest', 'waiting'];

        $team = DB::table('project')
            ->get();
        if(count($team) > 0){
            foreach($team as $v){
                $v->member = TeamMember::select(DB::raw(
                        'fp_team_member.*,
                        IF(fp_user.name IS NULL, fp_user.username, fp_user.name) as name,
                        fp_user.email'
                    ))
                    ->leftJoin('user', 'user.user_id', '=', 'team_member.user_id')
                    ->where('team_member.project_id', '=', $v->id)
                    ->get();
            }
        }

        return View::make('teamBuilder.default', [
            'assets' => $assets,
            'team' => $team
        ]);
    }

    public function teamBuilderJson(){
        header("Content-type: application/json");

        $t = DB::table('project')
            ->select('project_id', 'project_title')
            ->get();
        $team = array(array('project_id' => 0, 'project_title' => ''));
        $team = array_merge($team, $t);

        return response()->json($team);
    }

    public function teamBuilderUserJson(){
        //header("Content-type: application/json");

        $project_id = isset($_GET['t']) ? $_GET['t'] : '';
        $existing_user = array();
        if($project_id){
            $e = TeamMember::select('user_id')
                ->where('project_id', '=', $project_id)
                ->get();
            $existing_user = array_pluck($e, 'user_id');
        }

        $r = DB::table('user')
            ->select(DB::raw(
                'user_id as id,
                IF(name IS NULL, username, name) as name,
                email,
                user_avatar'
            ))
            ->whereIn('user_id', $existing_user)
            ->get();
        $user = array(array('id' => 0, 'name' => '','email' => '', 'user_avatar' => ''));
        $user = array_merge($user, $r);

        return response()->json($user);
    }

    public function teamBuilderExistingUserJson(){
        header("Content-type: application/json");

        $project_id = isset($_GET['t']) ? $_GET['t'] : '';
        $existing_user = array();
        if($project_id){
            $e = TeamMember::select('user_id')
                ->where('project_id', '=', $project_id)
                ->get();
            $existing_user = array_pluck($e, 'user_id');
        }

        $r = User::select(DB::raw(
                'user_id as id,
                    IF(name IS NULL, username, name) as name,
                    email,
                    user_avatar'
            ))
            ->whereNotIn('user_id', $existing_user)
            ->get();

        return response()->json($r);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $assets = [];
        $project = array();
        $role = array();
        $team = array();
        $company = array();
        $account = array();

        $page = isset($_GET['p']) ? $_GET['p'] : 'member';
        $project_id = isset($_GET['id']) ? $_GET['id'] : '';

        if($page == 'team'){
            $p = DB::table('project')
                ->select('project_id', 'project_title')
                ->get();
            $project = array_pluck($p, 'project_title', 'project_id');
        }
        else if($page == 'member'){
            $r = DB::table('roles')
                ->select('id', 'name')
                ->get();
            $role = array_pluck($r, 'name', 'id');

            $t = DB::table('project')
                ->select('project_id', 'project_title')
                ->where('project_id', '!=', $project_id)
                ->where(DB::raw('(
                    SELECT count(fp_team_member.id)
                    FROM fp_team_member
                    WHERE
                        fp_team_member.project_id = fp_project.project_id
                )'), '!=', 0)
                ->get();
            $team = array_pluck($t, 'project_title', 'project_id');

            $c = DB::table('client')
                ->select('client_id', 'company_name')
                ->get();
            $company = array('' => 'Select Company');
            $company += array_pluck($c, 'company_name', 'client_id');

            $a = DB::table('accounts')
                ->select('id', 'account_name')
                ->get();
            $account = array('' => 'Select Account');
            $account += array_pluck($a, 'account_name', 'id');
        }

        return View::make('teamBuilder.' . $page . '.create', [
            'assets' => $assets,
            'project' => $project,
            'role' => $role,
            'team' => $team,
            'project_id' => $project_id,
            'company' => $company,
            'account' => $account
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $page = isset($_GET['p']) ? $_GET['p'] : 'member';

        if($page == 'team') {
            $validation = Validator::make($request->all(), [
                'title' => 'required',
                'project_id' => 'required'
            ]);

            if ($validation->fails()) {
                return Redirect::to('meeting')
                    ->withInput()
                    ->withErrors($validation->messages());
            }

            $meeting = new Team();
            $meeting->project_id = Input::get('project_id');
            $meeting->user_id = Auth::user()->user_id;
            $meeting->title = Input::get('title');
            $meeting->save();

            return Redirect::to('teamBuilder')
                ->withSuccess("Team added successfully!!");
        }
        else if($page == 'member'){
            $type = isset($_GET['t']) ? $_GET['t'] : '';

            if($type == "existing"){
                $userId = Input::get('user');
                if(count($userId) > 0){
                    foreach($userId as $v){
                        $meeting = new TeamMember();
                        $meeting->created_by = Auth::user()->user_id;
                        $meeting->project_id = Input::get('project_id');
                        $meeting->user_id = $v;
                        $meeting->save();
                    }
                }
            }
            else if($type == "duplicate"){
                $duplicate_id = Input::get('duplicate_id');
                $t = DB::table('team_member')
                    ->select('user_id')
                    ->where('project_id', '=', $duplicate_id)
                    ->get();
                if(count($t) > 0){
                    foreach($t as $v){
                        $team_member = DB::table('team_member')
                            ->where('project_id', '=', Input::get('project_id'))
                            ->where('user_id', '=', $v->user_id)
                            ->first();

                        if (is_null($team_member)) {
                            $meeting = new TeamMember();
                            $meeting->created_by = Auth::user()->user_id;
                            $meeting->project_id = Input::get('project_id');
                            $meeting->user_id = $v->user_id;
                            $meeting->save();
                        }
                    }
                }
            }
            else if($type == "create"){
                $validation = Validator::make($request->all(), [
                    'role_id' => 'required',
                    'name' => 'required',
                    'email' => 'email|required',
                    'username' => 'required',
                    'password' => 'required'
                ]);

                if ($validation->fails()) {
                    return Redirect::to('teamBuilder')
                        ->withInput()
                        ->withErrors($validation->messages());
                }

                $user = new User();
                $user->name = Input::get('name');
                $user->email = Input::get('email');
                $user->phone = Input::get('phone');
                $user->username = Input::get('username');
                $user->password = Hash::make(Input::get('password'));
                $user->client_id = Input::get('company_id') ? Input::get('company_id') : null;
                $user->accounts_id = Input::get('account_id') ? Input::get('account_id') : null;
                $user->save();

                $user->attachRole(Input::get('role_id'));

                $meeting = new TeamMember();
                $meeting->created_by = Auth::user()->user_id;
                $meeting->project_id = Input::get('project_id');
                $meeting->user_id = $user->user_id;
                $meeting->save();

                $role = DB::table('roles')
                    ->where('id', '=', Input::get('role_id'))
                    ->pluck('name');
                $company = DB::table('client')
                    ->where('client_id', '=', Input::get('company_id'))
                    ->pluck('company_name');
                $account = DB::table('accounts')
                    ->where('id', '=', Input::get('account_id'))
                    ->pluck('account_name');

                Mail::send(
                    'teamBuilder.member.mail',
                    [
                        'role' => $role,
                        'name' => Input::get('name'),
                        'email' => Input::get('email'),
                        'username' => Input::get('username'),
                        'password' => Input::get('password'),
                        'phone' => Input::get('phone'),
                        'company' => $company,
                        'account' => $account
                    ],
                    function($message){
                        $message->from('admin@job.tc', 'System Admin');
                        $message->to(Input::get('email'), Input::get('name'))->subject('Registration Notification');
                    }
                );
            }
        }
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
