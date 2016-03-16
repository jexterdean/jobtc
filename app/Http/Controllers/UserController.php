<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;

use App\Models\User;
use App\Models\Client;
use Bican\Roles\Models\Role;

use DB;
use Illuminate\Http\Request;
use Validator;
use Input;
use Redirect;
use View;
use Hash;

class UserController extends BaseController
{

    public function index()
    {
        $user = DB::table('user')
            ->select('user.user_id','user.user_status', 'user.name','user.email','user.username',
                'role_user.role_id','user.client_id')
            ->join('role_user', 'role_user.user_id', '=', 'user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.level', '<>', '1')
            ->get();

        $role = Role::orderBy('name', 'asc')
            ->lists('name', 'id')
            ->toArray();

        $client_options = Client::orderBy('company_name', 'asc')
            ->lists('company_name', 'client_id')
            ->toArray();

        $assets = ['table', 'select2'];

        return View::make('user.index', [
            'users' => $user,
            'clients' => $client_options,
            'roles' => $role,
            'assets' => $assets
        ]);
    }

    public function show($username)
    {

        $user = User::whereUsername($username)
            ->first();

        return View::make('user.show', ['user' => $user]);
    }

    public function create()
    {
        return View::make('user.create');
    }

    public function edit($id)
    {
        $user = DB::table('user')
//            ->join('assigned_roles', 'assigned_roles.user_id', '=', 'user.user_id')
            ->join('role_user', 'role_user.user_id', '=', 'user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.level', '<>', '1')
            ->where('user.user_id', '=', $id)
            ->first();

        $role = Role::orderBy('name', 'asc')->lists('name', 'id');

        $client_options = Client::orderBy('company_name', 'asc')
            ->lists('company_name', 'client_id')->toArray();

        if ($user) {

            return View::make('user.edit', [
                'user' => $user,
                'clients' => $client_options,
                'roles' => $role
            ]);
        }

        return Redirect::to('user')->withErrors('Wrong user id to edit!!');
    }

    public function store(Request $request)
    {

        $role = Role::orderBy('name', 'asc')->lists('name', 'id');

        $validation = Validator::make(Input::all(), [
            'username' => 'required|unique:user',
            'password' => 'required',
            'email' => 'required',
            'name' => 'required',
            'role_id' => 'required'
        ]);

        if ($validation->fails()) {
            return Redirect::to('user')->withErrors($validation->messages());
        }

        $clientRole = Role::where('id', Input::get('role_id'))->first();
        if ($clientRole && $clientRole->slug === 'client') {

            // no company id the return.
            if (!Input::get('client_id')) {

                return Redirect::to('user')->withInput($request->except('password'))
                    ->withErrors('A client should have a company!!');
            }

        }

        $user = new User;
        $user->client_id = Input::get('client_id');
        $user->name = Input::get('name');
        $user->username = Input::get('username');
        $user->password = Hash::make(Input::get('password'));
        $user->email = Input::get('email');
        $user->phone = Input::get('phone');
        $user->user_status = 'Active';
        $user->save();

        $user->attachRole(Input::get('role_id'));

        return Redirect::to('user')->withSuccess("User added successfully!!");
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $validation = Validator::make(Input::all(), [
            'email' => 'required',
            'name' => 'required',
            'role_id' => 'required'
        ]);

        if ($validation->fails()) {
            return Redirect::to('user')->withErrors($validation->messages());
        }

        $clientRole = Role::where('id', Input::get('role_id'))->first();
        if ($clientRole && $clientRole->slug === 'client') {

            // no company id the return.
            if (!Input::get('client_id')) {

                print_r(Input::get('client_id'));
                die();
                return Redirect::to('user')->withInput($request->except('password'))
                    ->withErrors('A client should have a company!!');
            }

        }

        $user->client_id = Input::get('client_id');
        $user->name = Input::get('name');

        if (Input::get('user_status') != 'Ban') {
            $user->user_status = 'Active';
            $user->user_status_detail = '';
        } else {
            $user->user_status = 'Ban';
            $user->user_status_detail = Input::get('user_status_detail');
        }


        $user->email = Input::get('email');
        $user->phone = Input::get('phone');
        $user->save();
        $user->detachRole($user->role_id);
        $user->attachRole(Input::get('role_id'));

        return Redirect::to('user')->withSuccess("User updated successfully!!");
    }

    public function destroy()
    {
    }

    public function delete($user_id)
    {
        $user = User::find($user_id);

        if (!$user || !Entrust::hasRole('Admin'))
            return Redirect::to('user')->withErrors('This is not a valid link!!');
    }
}

?>