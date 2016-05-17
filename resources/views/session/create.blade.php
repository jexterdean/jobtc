@extends('layouts.login')
@section('content')

<!--div class="form-box" id="login-box">
    <div class="header">Sign In</div>
    {!! Form::open(['route' => 'session.store','class' => 'form-class', 'id' => 'login-form']) !!}
    <div class="body bg-gray">

        {!! \App\Helpers\Helper::showMessage() !!}

        <div class="form-group">
            {!!   Form::text('email', '', array('tabindex' => '1', 'class' => 'form-control', 'placeholder'
            => 'Username', 'autocomplete' => 'off', 'required' => true))!!}
        </div>
        <div class="form-group">
            {!!   Form::input('password', 'password', '', array('tabindex' => '2', 'class' => 'form-control',
            'placeholder' => 'Password', 'autocomplete' => 'off', 'required' => true)) !!}
        </div>
        <div class="form-group">
            <input type="checkbox" name="remember"/> Remember me
        </div>
    </div>
    <div class="footer">
        <button type="submit" class="btn bg-olive btn-block">Sign me in</button>
        <p><a href="{{ url('forgotPassword') }}">I forgot my password</a></p>
    </div>
    {!! Form::close() !!}
</div>
<div style="font-family: 'Kaushan Script', cursive;font-weight: 500;text-align:center;">Freelance Plus</div-->

<!-- resources/views/auth/login.blade.php -->
<div class="content-container">
    <div class="main-content-container col-xs-12">
        @if (count($errors->login) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->login->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="login-container row">
            <div class="col-xs-12">
                <div class="space"></div>

                <form action="{{route('session.store')}}" method="post" class="account_form" id="login-form">

                    {!! csrf_field() !!}

                    <div class="input-group">

                        <!--label for="login_email">Email</label><br/-->
                        <span class="input-group-addon" id="email-span"><i class="fa fa-envelope"></i></span>
                        <input type="text" class="form-control text required" aria-describedby="email-span" placeholder="Email" name="email" tabindex="1" id="login_email" value="{{ old('email') }}" />
                    </div>
                    <br />
                    <div class="input-group">
                        <!--label for="login_password">Password</label><br/-->
                        <span class="input-group-addon" id="password-span"><i class="fa fa-lock"></i></span>
                        <input type="password" class="form-control text required" aria-describedby="password-span" placeholder="Password" name="password" tabindex="2" id="login_password" value="" />
                    </div>
                    <div class="input-group">
                        <div class="checkbox">
                            <input type="checkbox" name="remember" tabindex="3" value="forever" checked="checked"/> 
                            <label for="remember">Remember me</label>
                        </div>
                    </div>
                    <div class="input-group">
                        <input type="submit" class="btn btn-primary submit" name="login" tabindex="4" value="Login" />
                        <a href="{{ url('forgotPassword') }}" class="lostpass space" href="" title="Password Lost and Found">Lost your password?</a>
                    </div>
                </form>

            </div>
        </div><!-- end section_header -->
    </div>
</div>
@stop