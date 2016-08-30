<div class="row">
    <div class="col-sm-6">
        <div id="dl-menu" class="dl-menuwrapper">
            <button type="button" class="btn">GO</button>
            <ul class="dl-menu">
                @if(Auth::check())
                {{--*/ $companies = \App\Helpers\Helper::getCompanyLinks() /*--}}
                <li>
                    <a href="#add_company" data-toggle="modal"><i class="fa fa-plus" aria-hidden="true"></i> <span>New Company</span></a>
                </li>
                <li class="divider"></li>
                @if(count($companies) > 0)
                @foreach($companies as $company)
                {{--*/ $module_permissions = \App\Helpers\Helper::getPermissions($company->company->id) /*--}}
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
                        @if($module_permissions->where('slug','view.projects')->count() === 1)
                        <li>
                            <a href="#">
                                <i class="fa fa-folder-open"></i>
                                <span> Projects </span>
                            </a>
                            <ul class="dl-submenu">
                                <li class="dl-back"><a href="#">back</a></li>
                                @if($module_permissions->where('slug','create.projects')->count() === 1)
                                <li>
                                    <a href="#add_project" data-toggle="modal"><i class="fa fa-plus" aria-hidden="true"></i> <span>New Project</span></a>
                                </li>
                                @endif
                                <li class="divider"></li>
                                <li>
                                    <a href="{{url('company/'.$company->company->id.'/projects')}}">
                                        <i class="fa fa-folder-open"></i>
                                        <span> All Projects </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-folder-open"></i>
                                        <span> My Projects </span>
                                    </a>
                                    <ul class="dl-submenu">
                                        <li class="dl-back"><a href="#">back</a></li>
                                        {{--*/ $my_projects = \App\Helpers\Helper::getMyProjects($company->company->id) /*--}}
                                        @if(count($my_projects) > 0)
                                        @foreach($my_projects as $val)
                                        <li class="{{ count($val->task) > 0 ? 'dropdown' : '' }}">
                                            <a href="{{ url('project/' . $val->project_id ) }}">
                                                <i class="fa fa-briefcase" aria-hidden="true"></i> <span>{{ $val->project_title }}</span>
                                            </a>
                                            @if(count($val->task) > 0)
                                            <ul class="dropdown-menu">
                                                @foreach($val->task as $briefcase)
                                                <li class="dropdown">
                                                    <a href="{{ url('briefcase/' .$briefcase->task_id) }}"><i class="fa fa-bars" aria-hidden="true"></i> {{ $briefcase->task_title }}</a>
                                                    @if(count($briefcase->task_list_items) > 0)
                                                    <ul class="dropdown-menu">
                                                        @foreach($briefcase->task_list_items as $task_list_item)
                                                        <li class="dropdown">
                                                            <a href="{{url('taskitem/'.$task_list_item->id)}}">
                                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                                                {{$task_list_item->checklist_header}}
                                                            </a>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                    @endif
                                                </li>
                                                @endforeach
                                            </ul>
                                            @endif
                                        </li>
                                        @endforeach
                                        @endif
                                    </ul>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-folder-open"></i>
                                        <span> Shared Projects </span>
                                    </a>
                                    <ul class="dl-submenu">
                                        <li class="dl-back"><a href="#">back</a></li>
                                        {{--*/ $shared_projects = \App\Helpers\Helper::getSharedProjects($company->company->id) /*--}}
                                        @if(count($shared_projects) > 0)
                                        @foreach($shared_projects as $val)
                                        {{--*/ $task_permissions = \App\Helpers\Helper::getBriefcasePermission($val->project_id) /*--}}
                                        <li class="{{ count($val->task) > 0 ? 'dropdown' : '' }}">
                                            <a href="{{ url('project/' . $val->project_id ) }}">
                                                <i class="fa fa-briefcase" aria-hidden="true"></i> <span>{{ $val->project_title }}</span>
                                            </a>
                                            @if(count($val->task) > 0)
                                            <ul class="dropdown-menu">
                                                @foreach($val->task as $briefcase)
                                                @if($task_permissions->contains('task_id',$briefcase->task_id))
                                                <li class="dropdown">
                                                    <a href="{{ url('briefcase/' .$briefcase->task_id) }}"><i class="fa fa-bars" aria-hidden="true"></i> {{ $briefcase->task_title }}</a>
                                                    @if(count($briefcase->task_list_items) > 0)
                                                    <ul class="dropdown-menu">
                                                        @foreach($briefcase->task_list_items as $task_list_item)
                                                        
                                                        <li class="dropdown">
                                                            <a href="{{url('taskitem/'.$task_list_item->id)}}">
                                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                                                {{$task_list_item->checklist_header}}
                                                            </a>
                                                        </li>
                                                        
                                                        @endforeach
                                                    </ul>
                                                    @endif
                                                </li>
                                                @endif
                                                @endforeach
                                            </ul>
                                            @endif
                                        </li>
                                        
                                        @endforeach
                                        @endif
                                    </ul>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-folder-open"></i>
                                        <span> Subordinate Projects </span>
                                    </a>
                                    <ul class="dl-submenu">
                                        <li class="dl-back"><a href="#">back</a></li>
                                        {{--*/ $subordinate_projects = \App\Helpers\Helper::getSubordinateProjects($company->company->id) /*--}}
                                        @if(count($subordinate_projects) > 0)
                                        @foreach($subordinate_projects as $val)
                                        <li class="{{ count($val->task) > 0 ? 'dropdown' : '' }}">
                                            <a href="{{ url('project/' . $val->project_id ) }}">
                                                <i class="fa fa-briefcase" aria-hidden="true"></i> <span>{{ $val->project_title }}</span>
                                            </a>
                                            @if(count($val->task) > 0)
                                            <ul class="dropdown-menu">
                                                @foreach($val->task as $briefcase)
                                                <li class="dropdown">
                                                    <a href="{{ url('briefcase/' .$briefcase->task_id) }}"><i class="fa fa-bars" aria-hidden="true"></i> {{ $briefcase->task_title }}</a>
                                                    @if(count($briefcase->task_list_items) > 0)
                                                    <ul class="dropdown-menu">
                                                        @foreach($briefcase->task_list_items as $task_list_item)
                                                        <li class="dropdown">
                                                            <a href="{{url('taskitem/'.$task_list_item->id)}}">
                                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                                                {{$task_list_item->checklist_header}}
                                                            </a>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                    @endif
                                                </li>
                                                @endforeach
                                            </ul>
                                            @endif
                                        </li>
                                        @endforeach
                                        @endif
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if(Auth::check('user'))
                        @if($module_permissions->where('slug','view.jobs')->count() === 1)
                        <li>
                            <a href="#">
                                <i class="fa fa-clipboard" aria-hidden="true"></i>
                                <span>Jobs</span>
                            </a>
                            <ul class="dl-submenu">
                                <li class="dl-back"><a href="#">back</a></li>
                                @if($module_permissions->where('slug','create.jobs')->count() === 1)
                                <li>
                                    <a href="#add_job" data-toggle="modal"><i class="fa fa-plus" aria-hidden="true"></i> <span>New Job</span></a>
                                </li>
                                @endif
                                <li class="divider"></li>
                                <li>
                                    <a href="{{url('company/'.$company->company->id.'/jobs')}}">
                                        <i class="fa fa-clipboard" aria-hidden="true"></i>
                                        <span>All Jobs</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-clipboard" aria-hidden="true"></i>
                                        <span>My Jobs</span>
                                    </a>
                                    <ul class="dl-submenu">
                                        <li class="dl-back"><a href="#">back</a></li>
                                        {{--*/ $my_jobs = \App\Helpers\Helper::getMyJobs($company->company->id) /*--}}
                                        @if(count($my_jobs) > 0)
                                        @foreach($my_jobs as $job)
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
                                            @endif
                                        </li>
                                        @endforeach
                                        @endif
                                    </ul>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-clipboard" aria-hidden="true"></i>
                                        <span>Shared Jobs</span>
                                    </a>
                                    <ul class="dl-submenu">
                                        <li class="dl-back"><a href="#">back</a></li>
                                        {{--*/ $shared_jobs = \App\Helpers\Helper::getSharedJobs($company->company->id) /*--}}
                                        @if(count($shared_jobs) > 0)
                                        @foreach($shared_jobs as $job)
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
                                            @endif
                                        </li>
                                        @endforeach
                                        @endif
                                    </ul>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-clipboard" aria-hidden="true"></i>
                                        <span>Subordinate Jobs</span>
                                    </a>
                                    <ul class="dl-submenu">
                                        <li class="dl-back"><a href="#">back</a></li>
                                        {{--*/ $subordinate_jobs = \App\Helpers\Helper::getSubordinateJobs($company->company->id) /*--}}
                                        @if(count($subordinate_jobs) > 0)
                                        @foreach($subordinate_jobs as $job)
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
                                            @endif
                                        </li>
                                        @endforeach
                                        @endif
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        @endif <!--Permissions-->
                        @endif <!--Auth Check-->
                        @if($module_permissions->where('slug','view.tests')->count() === 1)
                        <li>
                            <a href="{{ url('quizPerCompany/' . $company->company->id) }}">
                                <i class="glyphicon glyphicon-education"></i> 
                                <span>Test</span>
                            </a>
                        </li>
                        @endif
                        @if($module_permissions->where('slug','view.tickets')->count() === 1)
                        <li>
                            <a href="#">
                                <i class="fa fa-envelope"></i>
                                <span>Tickets</span>
                            </a>
                            <ul class="dl-submenu">
                                <li class="dl-back"><a href="#">back</a></li>
                                @if($module_permissions->where('slug','create.tickets')->count() === 1)
                                <li>
                                    <a href="#add_ticket" data-toggle="modal">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        <span>New Ticket</span>
                                    </a>
                                </li>
                                @endif
                                <li class="divider"></li>
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
                                    <a href="{{ url('tickets-admin?c=complete') }}" data-toggle="modal">
                                        <i class="glyphicon glyphicon-thumbs-up"></i>
                                        <span>Resolved Tickets</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if($module_permissions->where('slug','view.employees')->count() === 1)
                        <li>
                            <a href="{{url('/employees/'.$company->company->id)}}">
                                <i class="fa fa-users" aria-hidden="true"></i>
                                <span>Employees</span>
                            </a>
                        </li>
                        @endif
                        @if(Auth::user('user')->level() === 1 || $module_permissions->where('slug','view.employees')->count() === 1)
                        <li>
                            <a href="{{url('/positions/'.$company->company->id)}}">
                                <i class="fa fa-flag" aria-hidden="true"></i>
                                <span>Positions</span>
                            </a>
                        </li>
                        @endif
                        @if(
                        $module_permissions->where('slug','assign.projects')->count() === 1 || 
                        $module_permissions->where('slug','assign.jobs')->count() === 1 || 
                        $module_permissions->where('slug','assign.tests')->count() === 1 || 
                        $module_permissions->where('slug','assign.positions')->count() === 1
                        )
                        <li>
                            <a href="#">
                                <i class="fa fa-share-alt" aria-hidden="true"></i>
                                <span>Assign</span>
                            </a>
                            <ul class="dl-submenu">
                                <li class="dl-back"><a href="#">back</a></li>
                                @if($module_permissions->where('slug','assign.projects')->count() === 1)
                                <li>
                                    <a href="{{url('/assignProjects/'.$company->company->id)}}">
                                        <i class="fa fa-folder-open"></i>
                                        <span>Projects</span>
                                    </a>
                                </li>
                                @endif
                                @if($module_permissions->where('slug','assign.jobs')->count() === 1)
                                <li>
                                    <a href="{{url('/assignJobs/'.$company->company->id)}}">
                                        <i class="fa fa-clipboard" aria-hidden="true"></i>
                                        <span>Jobs</span>
                                    </a>
                                </li>
                                @endif
                                @if($module_permissions->where('slug','assign.tests')->count() === 1)
                                <li>
                                    <a href="{{url('/assignTests/'.$company->company->id)}}">
                                        <i class="glyphicon glyphicon-education"></i> 
                                        <span>Tests</span>
                                    </a>
                                </li>
                                @endif
                                @if($module_permissions->where('slug','assign.positions')->count() === 1)
                                <li>
                                    <a href="{{url('/assignAuthorityLevels/'.$company->company->id)}}">
                                        <i class="fa fa-users" aria-hidden="true"></i>
                                        <span>Authority Levels</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        <li>
                            <a href="{{ url('companyLinks/'.$company->company->id) }}"><i class="fa fa-globe" aria-hidden="true"></i> Links</a>
                        </li>
                    </ul>
                </li>
                @endforeach
                @endif
                @endif
                <li class="divider"></li>
                <li>
                    <a href="https://laravel.software/jangouts/dist/#/rooms/1234?user={{Auth::user('user')->name}}"><i class="fa fa-bars" aria-hidden="true"></i> Meeting Room</a>
                </li>
                <li>
                    <a target="_blank" href="{{ url('/dashboard') }}"><i class="fa fa-bars" aria-hidden="true"></i> My Dashboard</a>
                </li>
                <li>
                    <a href="{{ url('/profile') }}"><i class="glyphicon glyphicon-user"></i> My Profile</a>
                </li>
                <li>
                    <a href="{{ url('/logout') }}"><i class="glyphicon glyphicon-off"></i> Logout</a>
                </li>
            </ul>
        </div>
        {{--*/ $breadcrumbs = \App\Helpers\Helper::getBreadcrumbs() /*--}}
        <div class="breadcrumb_container">
            <ul class="breadcrumb">
                @foreach($breadcrumbs as $title => $url)
                <li><a href="{{url($url)}}" title="{{$title}}" data-toggle="tooltip" data-placement="right">{{$title}}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="col-sm-6">
        {{--*/$modules = \App\Helpers\Helper::getSearchModules()/*--}}
        <div class="search-container">
            <div class="input-group">
                <span class="input-group-btn">
                    <select class="module-selector btn">
                        @foreach($modules as $module)
                        <option value="{{strtolower(str_singular($module->name))}}">{{$module->name}}</option>
                        @endforeach
                    </select>
                </span>
                <input id="search-field" name="search" type="text" class="form-control">
            </div>
        </div>
    </div>
</div>

