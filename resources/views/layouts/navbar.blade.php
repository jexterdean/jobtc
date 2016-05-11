<li>
    <a href="{{ url('dashboard') }}">
        <i class="fa fa-home"></i> <span>Dash</span>
    </a>
</li>
@role('admin')
<li>
    <a href="{{ url('company') }}">
        <i class="fa fa-users"></i> <span>Companies</span>
    </a>
</li>
@endrole
<li class="dropdown">
    <a href="#" class="dropdown-toggle">
        <i class="fa fa-lightbulb-o"></i>
        <span> {{Lang::get('messages.Projects')}} </span>
        <span class="caret"></span>
    </a>
    <ul class="dropdown-menu">
        <li>
            <a href="#add_project" data-toggle="modal"><i class="fa fa-plus" aria-hidden="true"></i> <span>New Project</span></a>
        </li>
        <li class="divider"></li>
    <?php $project = \App\Helpers\Helper::getProjectLinks();?>
    @if(count($project) > 0)
        @foreach($project as $val)
            <li>
                <a href="{{ url('project/' . $val->project_id) }}">
                    <i class="fa fa-list-alt" aria-hidden="true"></i> <span>{{ $val->project_title }}</span>
                </a>
            </li>
        @endforeach
    @endif
    </ul>
</li>
<li>
    <a href="{{ url('teamBuilder') }}">
        <i class="fa fa-users"></i> <span>{{Lang::get('Team')}}</span>
    </a>
</li>
<li class="dropdown">
    <a href="#" class="dropdown-toggle">
        <i class="fa fa-laptop"></i>
        <span> Issue </span>
        <span class="caret"></span></a>
    <ul class="dropdown-menu">
        <li>
            <a href="{{ url('bug') }}">
                <i class="fa fa-bug"></i> <span>{{Lang::get('messages.Bugs')}}</span>
            </a>
        </li>
        <li>
            <a href="{{ url('ticket') }}">
                <i class="fa fa-ticket"></i> <span>{{Lang::get('messages.Tickets')}}</span>
            </a>
        </li>
    </ul>
</li>


<li>
    <a href="{{ url('meeting') }}">
        <i class="fa fa-calendar"></i> <span>{{Lang::get('Meetings')}}</span>
    </a>
</li>
<li>
    <a href="{{ url('message') }}">
        <i class="fa fa-envelope"></i> <span>{{Lang::get('messages.Message')}}</span>
    </a>
</li>
<li>
    <a href="{{ url('payroll') }}">
        <i class="fa fa-credit-card"></i> <span>{{Lang::get('Payroll')}}</span>
    </a>
</li>
<li>
    <a href="{{ url('quiz') }}">
        <i class="fa fa-university"></i> <span>{{Lang::get('Quiz')}}</span>
    </a>
</li>

<li>
    <a href="{{ route('links.index') }}">
        <i class="fa fa-globe"></i>
        <span>Links</span></a>
</li>

@role('admin')
<li class="dropdown">
    <a href="#" class="dropdown-toggle">
        <i class="fa fa-laptop"></i>
        <span>{{Lang::get('messages.Setting')}}</span>
        <span class="caret"></span></a>
    <ul class="dropdown-menu">
        <li><a href="{{ url('user') }}"><i class="fa fa-user"></i> {{Lang::get('messages.User')}}</a>
        </li>
        <li><a href="{{ url('setting') }}"><i
                        class="fa fa-wrench"></i> {{Lang::get('messages.General Setting')}}</a></li>
        <li><a href="{{ url('template') }}"><i
                        class="fa fa-folder-o"></i> {{Lang::get('messages.Email Templates')}}</a></li>
    </ul>
</li>
@endrole
<li>
    <a href="{{ route('css.index') }}" >
        <i class="fa fa-globe"></i>
        <span>CSS</span></a>
</li>
<li class="dropdown">
    <a href="#" class="dropdown-toggle">
        <i class="glyphicon glyphicon-user"></i>
        <span>{{ Auth::user()->name }} <i class="caret"></i></span>
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
