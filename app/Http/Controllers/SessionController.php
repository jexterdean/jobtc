<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Validator;
use \Auth;
use \View;
use \Form;
use \Input;
use \Redirect;

class SessionController extends BaseController {

    public function create(Request $request) {
        if (Auth::check('user') || Auth::viaRemember('user')) {
            return Redirect::to('dashboard');
        }
        return View::make('session.create');
    }

    public function store(Request $request) {
        /* if (Auth::attempt(Input::only('username', 'password'), Input::get('remember'))) {
          if (Auth::user()->user_status != 'Active') {
          $name = Auth::user()->name;
          Auth::logout();
          return Redirect::to('login')->withErrors("$name you are not allowed to login!!");
          } else
          return Redirect::intended('dashboard');
          } else
          return Redirect::back()->withErrors("Wrong username or password!!");
         */

        $email = $request->input('username');
        $pass = $request->input('password');
        $remember = $request->input('remember');


        if ($remember === 0) {
            $remember = false;
        } else {
            $remember = true;
        }

        $validator = Validator::make($request->all(), [
                    'username' => 'required|email',
                    'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->intended('dashboard')->withErrors($validator, 'login')->withInput();
        } else {

            if (Auth::attempt("user", ['email' => $email, 'password' => $pass], $remember)) {
                return Redirect::intended('dashboard');
            } elseif (Auth::attempt("client", ['email' => $email, 'password' => $pass], $remember)) {
                return redirect()->intended('dashboard')->withErrors($validator, 'login')->withInput();
            } else {
                Auth::logout('user');
                return Redirect::to('login')->withErrors("You are not allowed to login!!");
            }

        }
    }

    public function destroy() {
        if (Auth::check('user')) {
            $name = Auth::user('user')->first_name . ' ' . Auth::user('user')->last_name;
            Auth::logout('user');
        } else {
            $name = Auth::user('client')->contact_person;
            Auth::logout('client');
        }
        return Redirect::to('login')->withSuccess("$name you are logged out!!");
    }

}
