<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Models\Country;
use App\Models\Company;
use App\Models\Profile;
use App\Models\Comment;
use App\Models\Video;
use App\Models\Tag;
use App\Models\PermissionUser;
use App\Models\PermissionRole;
use App\Models\Permission;
use Bican\Roles\Models\Role;
use App\Models\Module;
use Illuminate\Support\Facades\Storage;
use DB;
use Illuminate\Http\Request;
use Validator;
use Input;
use Redirect;
use View;
use Hash;
use Auth;

class UserController extends BaseController {

    public function index() {
        /* $user = DB::table('user')
          ->join('profiles','profiles.user_id','=','user.user_id')
          ->join('roles','profiles.role_id','=','roles.id')
          ->join('companies','profiles.company_id','=','companies.id')
          ->select('user.user_id','user.user_status', 'user.name','user.email','roles.id as role_id','roles.name as role','companies.name as company_name')
          ->get(); */

        //Get countries for dropdown       
        $countries = Country::orderBy('country_name', 'asc')
                ->lists('country_name', 'country_id')
                ->toArray();

        //The profiles already contain all user, role and company information (it's fields belong to all 3 tables)
        $profiles = Profile::all();

        $role = Role::orderBy('name', 'asc')
                ->lists('name', 'id');

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

    public function show($user_id, $company_id) {

        $logged_in_user = Auth::user('user')->user_id;

        $profile = Profile::with('user')->where('user_id', $user_id)->where('company_id', $company_id)->first();

        $countries = Country::where('country_id', $profile->user->country_id)->first();

        $role = Role::where('id', $profile->role_id)->first();

        $user_info = User::with('profile')->where('user_id', $logged_in_user)->first();

        $videos = Video::with(['tags' => function($query) {
                        $query->where('tag_type', 'video')->first();
                    }])->where('unique_id', $user_id)->where('user_type', 'employee')->orderBy('id', 'desc')->get();

        $user_tags = Tag::where('unique_id', $user_id)
                ->where('tag_type', 'employee')
                ->first();

        $comments = Comment::with('user')
                        ->where('belongs_to', 'employee')
                        ->where('unique_id', $user_id)
                        ->orderBy('comment_id', 'desc')->get();

        $assets = ['users', 'real-time'];

        return view('user.show', [
            'profile' => $profile,
            'country' => $countries,
            'role' => $role,
            'user_info' => $user_info,
            'user_tags' => $user_tags,
            'videos' => $videos,
            'comments' => $comments,
            'assets' => $assets,
            'count' => 0]);
    }

    public function create() {
        return View::make('user.create');
    }

    public function edit($id) {
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

    public function store(Request $request) {

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
            $photo_save = $photo->move('assets/user/', $photo->getClientOriginalName());
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

    public function update(Request $request, $id) {
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

    public function destroy() {
        
    }

    public function delete($user_id) {
        $user = User::find($user_id);

        $user->delete();

        return Redirect::to('user')->withSuccess("User deleted successfully!!");
    }

    public function getRegisterForm() {

        $companies = Company::all();
        $countries = Country::all();

        return view('user.register', ['companies' => $companies, 'countries' => $countries]);
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

        /* if ($request->hasFile('photo')) {
          $photo = $request->file('photo');
          $photo_save = $photo->move('assets/user/' , $photo->getClientOriginalName());
          $photo_path = $photo_save->getPathname();
          } else {
          $photo_path = "assets/user/default-avatar.jpg";
          } */

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


        $new_user_role = Role::where('company_id', 0)
                ->where('level', 2)
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

    public function editEmployeeForm(Request $request, $company_id,$user_id) {
        
        $profile = Profile::with('user')
                ->where('user_id', $user_id)
                ->where('company_id', $company_id)
                ->first();
        
        $positions = Role::where('company_id',$company_id)->get();
        
        $countries_option = Country::orderBy('country_name', 'asc')->get();

        return view('forms.editEmployeeForm', [
            'profile' => $profile,
            'positions' => $positions,
            'countries' => $countries_option
        ]);
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
        $user_role = Role::where('company_id', $company_id)
                ->where('level', 2)
                ->first();


//Set the newly registered user to current company
        $profile = new Profile;
        $profile->user_id = $user->user_id;
        $profile->company_id = $company_id;
        $profile->role_id = $user_role->id;
        $profile->save();

        $user->attachRole($user_role->id);

        $countries_option = Country::orderBy('country_name', 'asc')->get();

        return view('user.partials._newemployee', [
            'profile' => $profile,
            'countries' => $countries_option,
            'company_id' => $company_id
        ]);
    }

    public function editEmployee(Request $request) {

        $user_id = $request->input('user_id');
        $company_id = $request->input('company_id');
        
        $user = User::where('user_id', $user_id);
        $profile = Profile::where('user_id',$user_id)
                ->where('company_id',$company_id);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photo_save = $photo->move('assets/user/', $photo->getClientOriginalName());
            $photo_path = $photo_save->getPathname();
        } else {
            $photo_path = User::where('user_id', $user_id)->pluck('photo');
        }

        if ($request->hasFile('resume')) {
            $resume = $request->file('resume');
            $resume_save = $resume->move('assets/user/resumes', $resume->getClientOriginalName());
            $resume_path = $resume_save->getPathname();
        } else {
            $resume_path = User::where('user_id', $user_id)->pluck('resume');
        }

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'phone' => $request->input('phone'),
            'photo' => $photo_path,
            'resume' => $resume_path,
            'address_1' => $request->input('address_1'),
            'address_2' => $request->input('address_2'),
            'zipcode' => $request->input('zipcode'),
            'country_id' => $request->input('country_id'),
            'skype' => $request->input('skype'),
            'facebook' => $request->input('facebook'),
            'linkedin' => $request->input('linkedin'),
        ]);
        
        $profile->update([
           'role_id' =>  $request->input('role_id')
        ]);

        return $photo_path;
    }

    public function removeEmployeeFromCompany(Request $request) {
        $user_id = $request->input('user_id');
        $company_id = $request->input('company_id');

//Delete profile for this company
        $profile = Profile::where('user_id', $user_id)->where('company_id', $company_id);
        $profile->delete();

//Add a profile with company id 0 for this user 
//if this user doesn't have any other profile with other companies
        $profile_count = Profile::where('user_id', $user_id)->count();

        if ($profile_count === 0) {
            $personal_user_role = Role::where('company_id', 0)->first();

            $personal_profile = new Profile();
            $personal_profile->user_id = $user_id;
            $personal_profile->company_id = 0;
            $personal_profile->role_id = $personal_user_role->id;
            $personal_profile->save();
        }


        return $user_id;
    }

    public function saveEmployeeNotes(Request $request) {
        $employee_id = $request->input('employee_id');
        $notes = $request->input('notes');

        $employee = User::where('user_id', $employee_id);
        $employee->update([
            'notes' => $notes
        ]);

        return "true";
    }

    public function editEmployeePermissionsForm(Request $request, $company_id,$user_id) {

        //$user_id = Auth::user('user')->user_id;

        $modules = Module::all();
        $permissions = Permission::all();
        
        $user_profile_role = Profile::where('user_id', $user_id)
                ->where('company_id', $company_id)
                ->first();

        $permissions_list = [];

        $permission_role = PermissionRole::with('permission')
                ->where('company_id', $company_id)
                ->where('role_id', $user_profile_role->role_id)
                ->get();

        $permission_user = PermissionUser::with('permission')
                ->where('company_id', $company_id)
                ->where('user_id', $user_id)
                ->get();
        
        foreach ($permission_role as $role) {
            array_push($permissions_list, $role->permission_id);
        }

        $position = Role::where('id', $user_profile_role->role_id)->first();
        
        $module_role_permissions = Permission::whereIn('id', $permissions_list)->get();
        
        $assets = ['companies', 'real-time'];

        return view('forms.editEmployeePermissionsForm', [
            'position' => $position,
            'permissions' => $permissions,
            'permission_role' => $permission_role,
            'permission_user' => $permission_user,
            'modules' => $modules,
            'module_role_permissions' => $module_role_permissions,
            'assets' => $assets,
            'user_id' => intval($user_id),
            'company_id' => intval($company_id)
        ]);
    }

    public function getEmployees(Request $request, $id) {

        $user_id = Auth::user('user')->user_id;

        $profiles = Profile::with('role')->where('company_id', $id)->get();

        $countries_option = Country::orderBy('country_name', 'asc')->get();

        $user_profile_role = Profile::where('user_id', $user_id)
                ->where('company_id', $id)
                ->first();

        $permissions_list = [];

        $permissions_role = PermissionRole::with('permission')
                ->where('company_id', $id)
                ->where('role_id', $user_profile_role->role_id)
                ->get();

        foreach ($permissions_role as $role) {
            array_push($permissions_list, $role->permission_id);
        }

        $module_permissions = Permission::whereIn('id', $permissions_list)->get();

        $assets = ['companies', 'real-time'];

        return view('user.employees', [
            'profiles' => $profiles,
            'countries' => $countries_option,
            'module_permissions' => $module_permissions,
            'assets' => $assets,
            'company_id' => $id,
        ]);
    }

}

?>
