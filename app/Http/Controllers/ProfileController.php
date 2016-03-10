<?php

namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;

class ProfileController extends BaseController
{

    public function changePassword()
    {
        $user = Auth::user();
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
        }
    }

    public function updateProfile()
    {
        $setting = Setting::find(1);
        $user = Auth::user();
        $rules = array(
            'name' => 'required',
            'email' => 'required|email',
            'user_avatar' => 'image|image_size:<=200|max:10000|mimes:' . $setting->allowed_upload_file
        );


        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
            return Redirect::back()->withErrors($validator);

        if (Input::hasFile('user_avatar') && Input::get('remove_image') != 'Yes') {
            $filename = Input::file('user_avatar')->getClientOriginalName();
            $extension = Input::file('user_avatar')->getClientOriginalExtension();
            $file = Input::file('user_avatar')->move('assets/user/', $user->username . "." . $extension);
            $user->user_avatar = $user->username . "." . $extension;
        }

        if (Input::get('remove_image') == 'Yes') {
            File::delete('assets/user/' . $user->user_avatar);
            $user->user_avatar = null;
        }

        $user->name = Input::get('name');
        $user->email = Input::get('email');
        $user->phone = Input::get('phone');
        $user->save();
        return Redirect::back()->withSuccess("Profile saved!!");

    }

    public function forgotPassword()
    {
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