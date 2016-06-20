<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Job.tc</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        {{--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>--}}
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet"
              type="text/css"/>
        <link href="{{ url('assets/css/AdminLTE.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{url('assets/css/page/register.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{url('assets/css/bootstrap.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{url('assets/custom.css')}}" rel="stylesheet" type="text/css"/>
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <style>
        </style>
    </head>
    <body class="login-body">

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
                <div class="login-container">
                    <div class="col-xs-12">
                        <div class="space"></div>

                        <form action="{{url('register')}}" method="post" class="account_form" id="register-form">

                            {!! csrf_field() !!}

                            <div class="input-group">
                                <span class="input-group-addon" id="name-span"><i class="fa fa-user" aria-hidden="true"></i></span>
                                <input type="text" class="form-control text required" aria-describedby="name-span" placeholder="Name" name="name" tabindex="1" id="login_name" value="{{ old('name') }}" />
                            </div>
                            <br />
                            <div class="input-group">
                                <span class="input-group-addon" id="email-span"><i class="fa fa-envelope"></i></span>
                                <input type="text" class="form-control text required" aria-describedby="email-span" placeholder="Email" name="email" tabindex="1" id="login_email" value="{{ old('email') }}" />
                            </div>
                            <br />
                            <div class="input-group">
                                <span class="input-group-addon" id="password-span"><i class="fa fa-lock"></i></span>
                                <input type="password" class="form-control text required" aria-describedby="password-span" placeholder="Password" name="password" tabindex="2" id="login_password" value="" />
                            </div>
                            <br/>
                            <!--div class="input-group">
                                <span class="input-group-addon" id="photo-span"><i class="fa fa-picture-o" aria-hidden="true"></i></span>
                                <input type="file" class="form-control text" aria-describedby="photo-span" placeholder="Photo" name="photo" tabindex="2" id="login_photo" value="" />
                            </div-->
                            <div class="input-group">
                                <span class="input-group-addon" id="address-1-span"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                                <input type="text" class="form-control text" aria-describedby="address-1-span" placeholder="Address 1" name="address_1" tabindex="2" id="login_address_1" value="" />
                            </div>
                            <br/>
                            <div class="input-group">
                                <span class="input-group-addon" id="address-2-span"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                                <input type="text" class="form-control text" aria-describedby="address-2-span" placeholder="Address 2" name="address_2" tabindex="2" id="login_address_2" value="" />
                            </div>
                            <br/>
                            <div class="input-group">
                                <span class="input-group-addon" id="zipcode-span"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                                <input type="text" class="form-control text" aria-describedby="zipcode-span" placeholder="Zipcode" name="zipcode" tabindex="2" id="login_zipcode" value="" />
                            </div>
                            <br/>
                            <div class="form-group">
                                <select name="country_id" class="form-control" id="country">
                                    <option for="country">Country:</option>
                                    @foreach($countries as $country)
                                    
                                    <option value="{{$country->country_id}}">{{$country->country_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br/>
                            <div class="input-group">
                                <span class="input-group-addon" id="phone-span"><i class="fa fa-phone-square" aria-hidden="true"></i></span>
                                <input type="text" class="form-control text" aria-describedby="phone-span" placeholder="Phone Number" name="phone" tabindex="2" id="login_phone" value="" />
                            </div>
                            <br/>
                            <!--div class="input-group">
                                <span class="input-group-addon" id="skype-span"><i class="fa fa-skype" aria-hidden="true"></i></span>
                                <input type="text" class="form-control text" aria-describedby="skype-span" placeholder="Skype" name="skype" tabindex="2" id="login_skype" value="" />
                            </div>
                            <br />
                            <div class="input-group">
                                <span class="input-group-addon" id="facebook-span"><i class="fa fa-facebook-square" aria-hidden="true"></i></span>
                                <input type="text" class="form-control text" aria-describedby="facebook-span" placeholder="Facebook" name="facebook" tabindex="2" id="login_facebook" value="" />
                            </div>
                            <br />
                            <div class="input-group">
                                <span class="input-group-addon" id="linkedin-span"><i class="fa fa-linkedin-square" aria-hidden="true"></i></span>
                                <input type="text" class="form-control text" aria-describedby="linkedin-span" placeholder="Linkedin" name="linkedin" tabindex="2" id="login_linkedin" value="" />
                            </div>
                            <br />
                            <div class="form-group">
                                <label for="company">Select Company:</label>
                                <select name="company" class="form-control" id="company">
                                    @foreach($companies as $company)
                                    <option value="{{$company->id}}">{{$company->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br /-->
                            <div class="input-group">
                                <input type="submit" class="btn btn-edit btn-shadow submit" name="register" tabindex="4" value="Register" />
                            </div>
                        </form>

                    </div>
                </div><!-- end section_header -->
            </div>
        </div>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
        <script>
$(document).ready(function ($) {
    $("#login-form").validate();
    $("#install-form").validate();
    $("#forgotPassword-form").validate();
    setTimeout(function () {
        $('.alert').fadeTo(2000, 500).slideUp(500, function () {
            $(this).alert('close');
        });
    }, 1000);
});
        </script>
    </body>
</html>