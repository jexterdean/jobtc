<div id="dl-menu" class="dl-menuwrapper">
    <button>GO</button>
    <ul class="dl-menu">
        @if(Auth::check())
        <?php 
        $companies = \App\Helpers\Helper::getCompanyLinks(); 
        $module_permissions = \App\Helpers\Helper::getPermissions();
        ?>
        
        <li>
            <a href="#add_company" data-toggle="modal"><i class="fa fa-plus" aria-hidden="true"></i> <span>New Company</span></a>
        </li>
        <li class="divider"></li>
        @if(count($companies) > 0)
        @foreach($companies as $company)
        <li class="dropdown">
            <a href="{{ url('company/' . $company->company->id) }}">
                <i class="fa fa-institution" aria-hidden="true"></i> <span>{{ $company->company->name }}</span>
            </a>
            <ul class="dl-submenu">
                <li class="dl-back"><a href="#">back</a></li>
                <li>
                    <a href="{{ url('company/' . $company->company->id) }}">
                        <!--i class="fa fa-briefcase" aria-hidden="true"></i> <span>{{ $company->company->name }}</span-->
                        <i class="fa fa-briefcase" aria-hidden="true"></i> <span>Dashboard</span>
                    </a>
                </li>
                @if(Auth::user('user')->can('view.projects') && $module_permissions->where('slug','view.projects')->count() > 0)
                <li>
                    <a href="#">
                        <i class="fa fa-folder-open"></i>
                        <span> Projects </span>
                    </a>
                    <ul class="dl-submenu">
                        <li class="dl-back"><a href="#">back</a></li>
                        @if(Auth::user('user')->can('create.projects'))
                        <li>
                            <a href="#add_project" data-toggle="modal"><i class="fa fa-plus" aria-hidden="true"></i> <span>New Project</span></a>
                        </li>
                        @endif
                        <li class="divider"></li>
                        <?php $project = $company->projects; ?>
                        @if(count($project) > 0)
                        @foreach($project as $val)
                        <li class="{{ count($val->briefcase) > 0 ? 'dropdown' : '' }}">
                            <a href="{{ url('project/' . $val->project_id ) }}">
                                <i class="fa fa-briefcase" aria-hidden="true"></i> <span>{{ $val->project_title }}</span>
                            </a>
                        </li>
                        @endforeach
                        @endif
                    </ul>
                </li>
                @endif
                @if(Auth::check('user'))
                <?php $jobs = $company->jobs; ?>
                @if(Auth::user('user')->can('view.jobs'))
                <li>
                    <a href="#">
                        <i class="fa fa-clipboard" aria-hidden="true"></i>
                        <span>Jobs</span>
                    </a>
                    <ul class="dl-submenu">
                        <li class="dl-back"><a href="#">back</a></li>
                        @if(Auth::user('user')->can('create.jobs'))
                        <li>
                            <a href="#add_job" data-toggle="modal"><i class="fa fa-plus" aria-hidden="true"></i> <span>New Job</span></a>
                        </li>
                        @endif
                        <li class="divider"></li>
                        @if(count($jobs) > 0)
                        @foreach($jobs as $job)

                        <li class="{{ count($job->applicants) > 0 ? 'dropdown' : '' }}">
                            <a href="{{ url('job/' . $job->id) }}" class="dropdown-toggle">
                                <i class="fa fa-clipboard" aria-hidden="true"></i> <span>{{ $job->title }}</span>
                            </a>
                            @if(count($job->applicants) > 0)
                            <ul class="dropdown-menu">
                                @foreach($job->applicants as $applicants)
                                <li>
                                    <a href="{{ url('a/' . $applicants->id) }}"><i class="glyphicon glyphicon-user" aria-hidden="true"></i> {{ $applicants->name }}</a>
                                </li>
                                @endforeach
                            </ul>

                        </li>
                        @endif
                        @endforeach
                        @endif
                    </ul>
                </li>
                @endif
                @endif

                <li>
                    <a href="{{ url('quiz') }}">
                        <i class="glyphicon glyphicon-education"></i> 
                        <span>Test</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fa fa-envelope"></i>
                        <span>Tickets</span>
                    </a>
                    <ul class="dl-submenu">
                        <li class="dl-back"><a href="#">back</a></li>
                        <li>
                            @if(Auth::user()->ticketit_admin)
                            <a href="{{ url('tickets-admin') }}">
                                <i class="glyphicon glyphicon-th"></i>
                                <span>Ticket Dashboard</span>
                            </a>
                            @elseif(Auth::user()->ticketit_agent)
                            <a href="{{ url('tickets') }}">
                                <i class="glyphicon glyphicon-th"></i>
                                <span>{{Lang::get('Ticket')}}</span>
                            </a>
                            @endif
                        </li>
                        <li>
                            <a href="#add_ticket" data-toggle="modal">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                <span>New Ticket</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('tickets-admin?c=complete') }}" data-toggle="modal">
                                <i class="glyphicon glyphicon-thumbs-up"></i>
                                <span>Resolved Tickets</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{url('/employees/'.$company->company->id)}}">
                        <i class="fa fa-users" aria-hidden="true"></i>
                        <span>Employees</span>
                    </a>
                </li>
                <li>
                    <a href="{{url('/positions/'.$company->company->id)}}">
                        <i class="fa fa-flag" aria-hidden="true"></i>
                        <span>Positions</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fa fa-share-alt" aria-hidden="true"></i>
                        <span>Assign</span>
                    </a>
                    <ul class="dl-submenu">
                        <li class="dl-back"><a href="#">back</a></li>
                        <li>
                            <a href="{{url('/assignProjects/'.$company->company->id)}}">
                                <i class="fa fa-folder-open"></i>
                                <span>Projects</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{url('/assignJobs/'.$company->company->id)}}">
                                <i class="fa fa-clipboard" aria-hidden="true"></i>
                                <span>Jobs</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{url('/assignTests/'.$company->company->id)}}">
                                <i class="glyphicon glyphicon-education"></i> 
                                <span>Tests</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{url('/assignAuthorityLevels/'.$company->company->id)}}">
                                <i class="fa fa-users" aria-hidden="true"></i>
                                <span>Authority Levels</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
        @endforeach
        @endif
        @endif
        <li class="divider"></li>
        <li>
            <a href="{{ url('/profile') }}"><i class="glyphicon glyphicon-user"></i> My Profile</a>
        </li>
        <li>
            <a href="{{ url('/logout') }}"><i class="glyphicon glyphicon-off"></i> Logout</a>
        </li>
    </ul>
</div>