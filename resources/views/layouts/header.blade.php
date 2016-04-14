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
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span>{{ Auth::user('user')->email}} <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-header bg-light-blue">
                                    <img onclick="window.location.href = '{{ url('/profile') }}' "
                                         src="{{ \App\Helpers\Helper::getAvatar(Auth::user('user')->email) }}"
                                          class="img-circle"
                                         alt="User Image"
                                         style="cursor: pointer;"
                                         title="My profile"
                                    />
                                </li>
                                <li><a class="btn btn-link" href="https://job.tc/laravel-pm/logout">Log out</a></li>
                                <!--li><a class="btn btn-link" href="/logout">Log out</a></li-->
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
@endif