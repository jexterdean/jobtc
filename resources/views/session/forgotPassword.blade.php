@extends('layouts.login')
@section('content')

    <div class="form-box" id="login-box">
        <div class="header">Forgot Password</div>
            {{ Form::open(['url' => 'forgotPassword','class' => 'form-class', 'id' => 'forgotPassword-form'])}}
            <div class="body bg-gray">
            
                {{ Helper::showMessage() }}
            
                <div class="form-group">
                    {{ Form::text('username', '', array('tabindex' => '1', 'class' => 'form-control', 'placeholder' => 'Username', 'autocomplete' => 'off', 'required' => true)) }}
                </div>
                <div class="form-group">
                    {{ Form::text('email', '', array('tabindex' => '2', 'class' => 'form-control', 'placeholder' => 'Email', 'autocomplete' => 'off', 'required' => true)) }}
                </div>
            </div>
            <div class="footer">                                                               
                <button type="submit" class="btn bg-olive btn-block">Get my Password</button> 
                <p><a href="{{ url('/') }}">Sign In</a></p>
            </div>
        </form>
    </div>
@stop