@extends('layouts.default')
@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">Update Profile</h3>
                </div>
                {!!  Form::open(array('url' => 'updateProfile','files' => 'true', 'role' => 'form', 'class' =>
                'profile-form'))  !!}
                <div class="box-body">
                    <div class="box-content">
                        <div class="form-group">
                            {!!  Form::input('text','name',Auth::user()->name,['class' => 'form-control', 'placeholder' =>
                            'Name']) !!}
                        </div>
                        <div class="form-group">
                            {!!  Form::input('email','email',Auth::user()->email,['class' => 'form-control', 'placeholder'
                            => 'Email']) !!}
                        </div>
                        <div class="form-group">
                            {!!  Form::label('phone','Phone') !!}
                            {!!  Form::input('text','phone',Auth::user()->phone,['class' => 'form-control', 'placeholder'
                            => 'Enter Phone']) !!}
                        </div>
                        <div class="form-group">
                            <label>
                                {!!  Form::checkbox('remove_image', 'Yes', false, ['class' => 'minimal', 'id' => 'minimal']) !!}
                                Remove Profile Picture
                            </label>
                        </div>
                        <div class="form-group">
                            {!!  Form::label('user_avatar','Avatar') !!}
                            {!!  Form::input('file','user_avatar','') !!}
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    {!!  Form::submit('Update',['class' => 'btn btn-edit btn-shadow'])  !!}
                </div>
                {!!  Form::close()  !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">Change Password</h3>
                </div>
                {!!  Form::open(array('url' => 'changePassword', 'role' => 'form', 'class' => 'change-password-form'))  !!}
                <div class="box-body">
                    <div class="box-content">
                        <div class="form-group">
                            {!!  Form::input('password','password','',['class' => 'form-control', 'placeholder' => 'Current Password']) !!}
                        </div>
                        <div class="form-group">
                            {!!  Form::input('password','new_password','',['class' => 'form-control', 'placeholder' =>
                            'New Password']) !!}
                        </div>
                        <div class="form-group">
                            {!!  Form::input('password','new_password_confirmation','',['class' => 'form-control',
                            'placeholder' => 'Confirm Password']) !!}
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    {!!  Form::submit('Change Password',['class' => 'btn btn-edit btn-shadow'])  !!}
                </div>
                {!!  Form::close()  !!}
            </div>
        </div>
    </div>

@stop