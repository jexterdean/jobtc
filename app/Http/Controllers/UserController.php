<?php
Class UserController extends BaseController{

	public function index(){
		$user = DB::table('fp_user')
			->join('fp_assigned_roles','fp_assigned_roles.user_id','=','fp_user.user_id')
			->where('fp_user.user_id','!=','1')
			->get();

		$role = Role::orderBy('name', 'asc')
			->lists('name','id');

		$client_options = Client::orderBy('company_name', 'asc')
			->lists('company_name','client_id');

		$assets = ['table','select2'];

		return View::make('user.index',[
				'users'=>$user, 
				'clients'=>$client_options, 
				'roles' => $role,
				'assets' => $assets
				]);
	}

	public function show($username){

		$user = User::whereUsername($username)
			->first();

		return View::make('user.show',['user'=>$user]);
	}

	public function create(){
		return View::make('user.create');
	}

	public function edit($id){
		$user = DB::table('fp_user')
			->join('fp_assigned_roles','fp_assigned_roles.user_id','=','fp_user.user_id')
			->where('fp_user.user_id','!=','1')
			->where('fp_user.user_id','=',$id)
			->first();

		$role = Role::orderBy('name', 'asc')->lists('name','id');

		$client_options = Client::orderBy('company_name', 'asc')
			->lists('company_name','client_id');

		if($user)
		return View::make('user.edit',[
				'user'=>$user, 
				'clients'=>$client_options, 
				'roles' => $role
				]);
		else
			return Redirect::to('user')->withErrors('Wrong user id to edit!!');
	}

	public function store(){

		$role = Role::orderBy('name', 'asc')->lists('name','id');

		$validation = Validator::make(Input::all(),[
				'username'=>'required|unique:fp_user',
				'password'=>'required',
				'email'=>'required',
				'name'=>'required',
				'role_id'=>'required'
				]);

		if($validation->fails()){
			return Redirect::to('user')->withErrors($validation->messages());
		}

		if($role[Input::get('role_id')] != 'Client' && Input::get('client_id') != '')
			return Redirect::to('user')->withErrors('Only clients can have company!!');
		if($role[Input::get('role_id')] == 'Client' && Input::get('client_id') == '')
			return Redirect::to('user')->withErrors('A client should have a company!!');

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

	public function update($id){
		$user = User::find($id);
		$role = Role::orderBy('name', 'asc')->lists('name','id');
		
		$validation = Validator::make(Input::all(),[
				'email'=>'required',
				'name'=>'required',
				'role_id'=>'required'
				]);
		
		if($validation->fails()){
			return Redirect::to('user')->withErrors($validation->messages());
		}
		if($role[Input::get('role_id')] != 'Client' && Input::get('client_id') != '')
			return Redirect::to('user')->withErrors('Only clients can have company!!');
		if($role[Input::get('role_id')] == 'Client' && Input::get('client_id') == '')
			return Redirect::to('user')->withErrors('A client should have a company!!');

		$user->client_id = Input::get('client_id');
		$user->name = Input::get('name');

		if(Input::get('user_status') != 'Ban'){
			$user->user_status = 'Active';
			$user->user_status_detail = '';
		}
		else{
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

	public function destroy(){
	}

	public function delete($user_id){
		$user = User::find($user_id);

		if(!$user || !Entrust::hasRole('Admin'))
			return Redirect::to('user')->withErrors('This is not a valid link!!');
	}
}
?>