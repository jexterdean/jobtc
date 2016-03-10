@extends('layouts.login')
@section('content')

    <div class="form-box" id="login-box">
        <div class="header">Sign In</div>
            {{ Form::open(['route' => 'session.store','class' => 'form-class', 'id' => 'login-form'])}}
            <div class="body bg-gray">
            
                {{ Helper::showMessage() }}
            
                <div class="form-group">
                    {{ Form::text('username', '', array('tabindex' => '1', 'class' => 'form-control', 'placeholder' => 'Username', 'autocomplete' => 'off', 'required' => true)) }}
                </div>
                <div class="form-group">
                    {{ Form::input('password', 'password', '', array('tabindex' => '2', 'class' => 'form-control', 'placeholder' => 'Password', 'autocomplete' => 'off', 'required' => true)) }}
                </div>          
                <div class="form-group">
                    <input type="checkbox" name="remember"/> Remember me
                </div>
            </div>
            <div class="footer">                                                               
                <button type="submit" class="btn bg-olive btn-block">Sign me in</button> 
                <p><a href="{{ url('forgotPassword') }}">I forgot my password</a></p>
            </div>
            {{ Form::close() }}
    </div>
    <div style="font-family: 'Kaushan Script', cursive;font-weight: 500;text-align:center;">Freelance Plus</div>
@stop