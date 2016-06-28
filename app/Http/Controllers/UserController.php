<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;

use App\Models\User;
use App\Models\Country;
use App\Models\Company;
use App\Models\Profile;
use Bican\Roles\Models\Role;

use Illuminate\Support\Facades\Storage;
use DB;
use Illuminate\Http\Request;
use Validator;
use Input;
use Redirect;
use View;
use Hash;
use Auth;

class UserController extends BaseController
{

    public function index()
    {
        /*$user = DB::table('user')
                ->join('profiles','profiles.user_id','=','user.user_id')
                ->join('roles','profiles.role_id','=','roles.id')
                ->join('companies','profiles.company_id','=','companies.id')
                ->select('user.user_id','user.user_status', 'user.name','user.email','roles.id as role_id','roles.name as role','companies.name as company_name')
                ->get();*/

         //Get countries for dropdown       
         $countries = Country::orderBy('country_name', 'asc')
                ->lists('country_name', 'country_id')
                ->toArray();
        
        //The profiles already contain all user, role and company information (it's fields belong to all 3 tables)
        $profiles = Profile::all();
        
        $role = Role::orderBy('name', 'asc')
            ->lists('name','id');

        $client_options = Company::orderBy('name', 'asc')
            ->lists('name', 'id');

        $assets = ['table', 'select2'];

        return View::make('user.index', [
            'profiles' => $profiles,
            'companies' => $client_options,
            'countries' => $countries,
            'roles' => $role,
            'assets' => $assets
        ]);
    }

    public function show($user_id)
    {

        $user = User::where('user_id',$user_id)->first();

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

        $client_options = Company::orderBy('company_name', 'asc')
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

        $validation = Validator::make($request->all(), [
            'password' => 'required',
            'email' => 'required',
            'name' => 'required',
            'role_id' => 'required'
        ]);

        if ($validation->fails()) {
            return Redirect::to('user')->withErrors($validation->messages());
        }

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photo_save = $photo->move('assets/user/' , $photo->getClientOriginalName());
            $photo_path = $photo_save->getPathname();
        } else {
            $photo_path = "assets/user/avatar.png";
        }
        
        $ticketit_admin = $request->input('ticketit_admin');
        $ticketit_agent = $request->input('ticketit_agent');
        
        
        if ($ticketit_admin === NULL) {
            $ticketit_admin = 0;
        } 
        
        if ($ticketit_agent === NULL) {
            $ticketit_agent = 0;
        } 
        
        $user = new User;
        $user->name = $request->input('name');
        $user->password = bcrypt($request->input('password')); 
        $user->email = $request->input('email'); 
        $user->phone = $request->input('phone'); 
        $user->photo = $photo_path;
        $user->address_1 = $request->input('address_1'); 
        $user->address_2 = $request->input('address_2'); 
        $user->zipcode = $request->input('zipcode'); 
        $user->country_id = $request->input('country_id'); 
        $user->skype = $request->input('skype'); 
        $user->facebook = $request->input('facebook'); 
        $user->linkedin = $request->input('linkedin');
        $user->ticketit_admin = $ticketit_admin;
        $user->ticketit_agent = $ticketit_agent;
        $user->user_status = 'Active';
        
        $user->save();

        $profile = new Profile;
        $profile->user_id = $user->user_id;
        $profile->company_id = $request->input('company_id');
        $profile->role_id = $request->input('role_id');
        $profile->save();
        
        $user->attachRole($request->input('role_id'));

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

        $user->delete();
        
        return Redirect::to('user')->withSuccess("User deleted successfully!!");
    }
    
    public function getRegisterForm() {
        
        $companies = Company::all();
        $countries = Country::all();
        
        return view('user.register',['companies' => $companies,'countries' => $countries]);
    }
    
    public function register(Request $request) {
        
        $validation = Validator::make($request->all(), [
            'password' => 'required',
            'email' => 'required',
            'name' => 'required'
        ]);

        if ($validation->fails()) {
            return Redirect::to('register')->withErrors($validation->messages());
        }

        /*if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photo_save = $photo->move('assets/user/' , $photo->getClientOriginalName());
            $photo_path = $photo_save->getPathname();
        } else {
            $photo_path = "assets/user/default-avatar.jpg";
        }*/
        
        //Get the Client Role for the company
        //$client_role = Role::where('company_id',1)->where('level',2)->first();
        //$client_role = Role::where('company_id',6)->where('level',2)->first();
        
        
        $user = new User;
        $user->name = $request->input('name');
        $user->password = bcrypt($request->input('password')); 
        $user->email = $request->input('email'); 
        $user->phone = $request->input('phone'); 
        $user->photo = '';
        $user->address_1 = $request->input('address_1'); 
        $user->address_2 = $request->input('address_2'); 
        $user->zipcode = $request->input('zipcode'); 
        $user->country_id = $request->input('country_id'); 
        $user->skype = '';
        $user->facebook = '';
        $user->linkedin = '';
        $user->ticketit_admin = 0;
        $user->ticketit_agent = 0;
        $user->user_status = 'Active';        
        $user->save();

        
        $new_user_role = Role::where('company_id',0)
                ->where('level',2)
                ->first();
        
        //Set the newly registered user to company id 0(No Company)
        $profile = new Profile;
        $profile->user_id = $user->user_id;
        $profile->company_id = 0;
        $profile->role_id = $new_user_role->id;
        $profile->save();
        
        $user->attachRole($new_user_role->id);

        Auth::loginUsingId("user", $user->user_id);
        
        return redirect()->route('company', [$profile->company_id]);
        
    }
    
    public function addEmployeeForm(Request $request) {
        return view('forms.addEmployeeForm');
    }
    
    public function addEmployee(Request $request) {
        
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $company_id = $request->input('company_id');
        
        $user = new User;
        $user->name = $name;
        $user->password = bcrypt($password); 
        $user->email = $email; 
        $user->ticketit_admin = 0;
        $user->ticketit_agent = 0;
        $user->user_status = 'Active';        
        $user->save();

        //Assign it as a staff user first
        $user_role = Role::where('company_id',$company_id)
                ->where('level',2)
                ->first();
        
        
        //Set the newly registered user to current company
        $profile = new Profile;
        $profile->user_id = $user->user_id;
        $profile->company_id = $company_id;
        $profile->role_id = $user_role->id;
        $profile->save();
        
        $user->attachRole($user_role->id);
        
        return view('user.partials._newemployee',[
            'profile' => $profile,
            'company_id' => $company_id
        ]);
    }
}

?>
