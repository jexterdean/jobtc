@if (Auth::check())
    <header class="header">
        <a href="{{ url('/') }}" class="logo">
        </a>
        <nav class="navbar navbar-static-top" role="navigation">

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
        </nav>
    </header>
@endif