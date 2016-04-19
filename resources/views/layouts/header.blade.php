@if (Auth::check())
    <header class="header">
        <nav class="navbar navbar-static-top" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#navbar-main" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Project Manager</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-main">
                <ul class="nav navbar-nav">
                    @include('layouts/navbar')
                </ul>

                <div class="navbar-right">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="glyphicon glyphicon-user"></i>
                            <span>{{ Auth::user()->username }} <i class="caret"></i></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header bg-light-blue">
                                <img onclick="window.location.href = '{{ url('/profile') }}' "
                                     src="{{ \App\Helpers\Helper::getAvatar(Auth::user()->username) }}"
                                      class="img-circle"
                                     alt="User Image"
                                     style="cursor: pointer;"
                                     title="My profile"
                                />
                            </li>

                        </ul>
                    </li>
                </ul>
            </div>
            </div>
        </nav>
    </header>
@endif