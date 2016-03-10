@extends('layouts.default')
	@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Update Profile</h3>
                </div>
                {{ Form::open(array('url' => 'updateProfile','files' => 'true', 'role' => 'form', 'class' => 'profile-form')) }}
                	<div class="box-body">
                        <div class="form-group">
                            {{ Form::label('name','Name')}}
                            {{ Form::input('text','name',Auth::user()->name,['class' => 'form-control', 'placeholder' => 'Enter Name'])}}
                        </div>
                        <div class="form-group">
                            {{ Form::label('email','Email')}}
                            {{ Form::input('email','email',Auth::user()->email,['class' => 'form-control', 'placeholder' => 'Enter Email'])}}
                        </div>
                        <div class="form-group">
                            {{ Form::label('phone','Phone')}}
                            {{ Form::input('text','phone',Auth::user()->phone,['class' => 'form-control', 'placeholder' => 'Enter Phone'])}}
                        </div>
                        <div class="form-group">
	                        <label>
	                            {{ Form::checkbox('remove_image', 'Yes', false, ['class' => 'minimal', 'id' => 'minimal'])}}
	                            Remove Profile Picture
	                        </label>
                        </div>
                        <div class="form-group">
                            {{ Form::label('user_avatar','Avatar')}}
                            {{ Form::input('file','user_avatar','')}}
                        </div>
                    </div>
                    <div class="box-footer">
                    	{{ Form::submit('Update',['class' => 'btn btn-primary']) }}
                    </div>
                {{ Form::close() }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Change Password</h3>
                </div>
                {{ Form::open(array('url' => 'changePassword', 'role' => 'form', 'class' => 'change-password-form')) }}
                	<div class="box-body">
                        <div class="form-group">
                            {{ Form::label('password','Current Password')}}
                            {{ Form::input('password','password','',['class' => 'form-control', 'placeholder' => 'Enter Current Password'])}}
                        </div>
                        <div class="form-group">
                            {{ Form::label('new_password','New Password')}}
                            {{ Form::input('password','new_password','',['class' => 'form-control', 'placeholder' => 'Enter New Password'])}}
                        </div>
                        <div class="form-group">
                            {{ Form::label('new_password_confirmation','Confirm Password')}}
                            {{ Form::input('password','new_password_confirmation','',['class' => 'form-control', 'placeholder' => 'Enter Confirm Password'])}}
                        </div>
                    </div>
                    <div class="box-footer">
                    	{{ Form::submit('Change Password',['class' => 'btn btn-primary']) }}
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

	@stop