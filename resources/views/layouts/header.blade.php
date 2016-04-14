@if (Auth::check())
        <nav class="navbar navbar-static-top navbar-inverse navbar-border" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Project Manager</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="nav navbar-nav">
                    @include('layouts/navbar')
                </ul>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle">
                                <i class="glyphicon glyphicon-user"></i>
                                <span>{{ Auth::user()->username }} <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{{ url('/profile') }}"><i class="glyphicon glyphicon-user"></i> My Profile</a>
                                </li>
                                <li role="separator" class="divider"></li>
                                <li>
                                    <a href="{{ url('/logout') }}"><i class="glyphicon glyphicon-off"></i> Logout</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
@endif