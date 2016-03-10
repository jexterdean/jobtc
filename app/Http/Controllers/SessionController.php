<?php

Class SessionController extends BaseController{
	public function create(){
		if(Auth::check()) return Redirect::to('dashboard');
		
		return View::make('session.create');
	}

	public function store(){
		if(Auth::attempt(Input::only('username','password'), Input::get('remember') )){
			if(Auth::user()->user_status != 'Active'){
				$name=Auth::user()->name;
				Auth::logout();
				return Redirect::to('login')->withErrors("$name you are not allowed to login!!");
			} 
			else
			return Redirect::intended('dashboard');
		}
		else
			return Redirect::back()->withErrors("Wrong username or password!!");
	}

	public function destroy(){
		$name=Auth::user()->name;
		Auth::logout();
		return Redirect::to('login')->withSuccess("$name you are logged out!!");
	}
}