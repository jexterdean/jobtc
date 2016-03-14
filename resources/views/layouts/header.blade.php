@if (Auth::check())
    <header class="header">
        <a href="{{ url('/') }}" class="logo">
            Tom's PM App
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
            <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="navbar-right">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="glyphicon glyphicon-user"></i>
                            <span>{{ Auth::user()->username }} <i class="caret"></i></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header bg-light-blue">
                                <img src="{{ \App\Helpers\Helper::getAvatar(Auth::user()->username) }}" class="img-circle"
                                     alt="User Image"/>
                                <p>
                                    {{ Auth::user()->name }}
                                </p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="{{ url('profile') }}" class="btn btn-default btn-flat">Profile</a>
                                    <a href="{{ url('docs') }}" class="btn btn-default btn-flat">Help</a>
                                    <a href="{{ url('about') }}" class="btn btn-default btn-flat">About</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{ url('logout') }}" class="btn btn-default btn-flat">Log Off</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
@endif