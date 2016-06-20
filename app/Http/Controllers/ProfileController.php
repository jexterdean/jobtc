<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Country;
use Hash;
use \DB;
use \Auth;
use \View;
use \Validator;
use \Input;
use \Redirect;

class ProfileController extends BaseController {

    public function index() {

        $countries_option = Country::orderBy('country_name', 'asc')->get();

        $assets = ['profiles'];

        return view('user.profile', ['assets' => $assets, 'countries' => $countries_option]);
    }

    public function changePassword(Request $request) {
        /*$user = Auth::user();
        $rules = array(
            'password' => 'required|alphaNum|between:5,16',
            'new_password' => 'required|alphaNum|between:5,16|confirmed'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
            return Redirect::back()->withErrors($validator);
        else {
            if (!Hash::check(Input::get('password'), $user->password))
                return Redirect::back()->withErrors('Your old password does not match!!');
            else {
                $user->password = Hash::make(Input::get('new_password'));
                $user->save();
                return Redirect::back()->withSuccess("Password have been changed!!");
            }
        }*/
        $user_id = Auth::user('user')->user_id;
        
        $user = User::where('user_id', $user_id);
        
        $user->update([
                'password' => bcrypt($request->input('password'))
            ]);
        
        return "Profile Updated";
        
    }
    
    public function checkPassword(Request $request) {
        
        $user_id = Auth::user('user')->user_id;
        $current_password = $request->input('password');
        
        $user_password = User::where('user_id', $user_id)->first();
        
        if (Hash::check($current_password, $user_password->password)) {
            return "true";
        } else {
            return "false";
        }
    }

    public function updateProfile(Request $request) {

        $user_id = $request->input('user_id');

        $user = User::where('user_id', $user_id);

        $password = $request->input('password');

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photo_save = $photo->move('assets/user/', $photo->getClientOriginalName());
            $photo_path = $photo_save->getPathname();
        } else {
            $photo_path = User::where('user_id', $user_id)->pluck('photo');
        }

        if ($password !== '') {

            $user->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'phone' => $request->input('phone'),
                'photo' => $photo_path,
                'address_1' => $request->input('address_1'),
                'address_2' => $request->input('address_2'),
                'zipcode' => $request->input('zipcode'),
                'country_id' => $request->input('country_id'),
                'skype' => $request->input('skype'),
                'facebook' => $request->input('facebook'),
                'linkedin' => $request->input('linkedin'),
            ]);
        } else {

            $user->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'photo' => $photo_path,
                'address_1' => $request->input('address_1'),
                'address_2' => $request->input('address_2'),
                'zipcode' => $request->input('zipcode'),
                'country_id' => $request->input('country_id'),
                'skype' => $request->input('skype'),
                'facebook' => $request->input('facebook'),
                'linkedin' => $request->input('linkedin'),
            ]);
        }

        return $photo_path;

    }

    public function updateMyProfile(Request $request) {
        $user_id = Auth::user()->user_id;

        $user = User::where('user_id', $user_id);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photo_save = $photo->move('assets/user/', $photo->getClientOriginalName());
            $photo_path = $photo_save->getPathname();
        } else {
            $photo_path = User::where('user_id', $user_id)->pluck('photo');
        }

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'photo' => $photo_path,
            'address_1' => $request->input('address_1'),
            'address_2' => $request->input('address_2'),
            'zipcode' => $request->input('zipcode'),
            'country_id' => $request->input('country_id'),
            'skype' => $request->input('skype'),
            'facebook' => $request->input('facebook'),
            'linkedin' => $request->input('linkedin'),
        ]);
        
        return $photo_path;
    }

    public function forgotPassword() {
        $user = User::where('email', '=', Input::get('email'))->where('username', '=', Input::get('username'))->first();

        $rules = array(
            'username' => 'required',
            'email' => 'required|email'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
            return Redirect::back()->withErrors($validator);
        elseif (!$user)
            return Redirect::back()->withErrors('Username or email-id is wrong!!');
        else {
            $new_password = rand(1000000, 999999);
            $user->password = Hash::make($new_password);
            $user->save();
            //Mail::send('user.forgetPassword', array('username' => Input::get('username') , 'password' => $new_password), function($message){
            //	$message->to(Input::get('email'), 'Forget Password')->subject('Forget Password!');
            //});
            return Redirect::back()->withSuccess('Password sent to your mail!!');
        }
    }

}

?>