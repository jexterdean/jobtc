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
                        <div class="media">
                            <div class="media-left">
                                <a href="#">
                                    <img class="profile-img" src="{{Auth::user()->photo}}"/>
                                </a>
                            </div>
                            <div class="media-body">
                                <br />
                                <br />
                                <br />
                                <br />
                                {!!  Form::input('file','photo','') !!}
                            </div>
                        </div>
                    </div>
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
                        => 'Phone']) !!}
                    </div>

                    <div class="form-group">
                        {!!  Form::label('address_1','Address 1') !!}
                        {!!  Form::input('text','address_1',Auth::user()->address_1,['class' => 'form-control', 'placeholder'
                        => 'Address 1']) !!}
                    </div>
                    <div class="form-group">
                        {!!  Form::label('address_2','Address 2') !!}
                        {!!  Form::input('text','address_2',Auth::user()->address_2,['class' => 'form-control', 'placeholder'
                        => 'Address 2']) !!}
                    </div>
                    <div class="form-group">
                        {!!  Form::label('zipcode','Zipcode') !!}
                        {!!  Form::input('text','zipcode',Auth::user()->zipcode,['class' => 'form-control', 'placeholder'
                        => 'Enter Phone']) !!}
                    </div>
                    <div class="form-group">
                        {!!  Form::label('country','Country') !!}
                        <select name="country_id" class='form-control input-xlarge select2me' placeholder="Select Country">
                            @foreach($countries as $country)
                            @if($country->country_id == Auth::user()->country_id)
                            <option selected="selected" value='{{$country->country_id}}'>{{$country->country_name}}</option>
                            @else
                            <option value='{{$country->country_id}}'>{{$country->country_name}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        {!!  Form::label('skype','Skype') !!}
                        {!!  Form::input('text','skype',Auth::user()->skype,['class' => 'form-control', 'placeholder'
                        => 'Skype']) !!}
                    </div>
                    <div class="form-group">
                        {!!  Form::label('facebook','Facebook') !!}
                        {!!  Form::input('text','facebook',Auth::user()->facebook,['class' => 'form-control', 'placeholder'
                        => 'Facebook']) !!}
                    </div>
                    <div class="form-group">
                        {!!  Form::label('linkedin','Linkedin') !!}
                        {!!  Form::input('text','linkedin',Auth::user()->linkedin,['class' => 'form-control', 'placeholder'
                        => 'Linkedin']) !!}
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-md-6">
                        {!!  Form::submit('Update',['class' => 'btn btn-edit btn-shadow update-profile'])  !!}
                    </div>
                    <div class="pull-right btn bg-green update-progress"></div>
                </div>
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
                        {!!  Form::input('password','password','',['id' =>'current_password', 'class' => 'form-control', 'placeholder' => 'Current Password']) !!}
                    </div>
                    <div class="form-group">
                        {!!  Form::input('password','new_password','',['id' => 'new_password', 'class' => 'form-control', 'placeholder' =>
                        'New Password']) !!}
                    </div>
                    <div class="form-group">
                        {!!  Form::input('password','new_password_confirmation','',['id' => 'new_password_confirmation','class' => 'form-control',
                        'placeholder' => 'Confirm Password']) !!}
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-md-6">
                        {!!  Form::submit('Change Password',['disabled' => 'disabled','class' => 'btn btn-edit btn-shadow change-password'])  !!}
                    </div>
                    <div class="pull-right btn bg-green update-password"></div>
                </div>
                {!!  Form::close()  !!}
            </div>
        </div>
    </div>
    @stop