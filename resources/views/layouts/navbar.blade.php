<li>
    <a href="{{ url('dashboard') }}">
        <i class="fa fa-home"></i> <span>{{Lang::get('messages.DashBoard')}}</span>
    </a>
</li>
@if(Auth::user('user')->user_type === 1 || Auth::user('user')->user_type === 2 || Auth::user('user')->user_type === 3)
<li>
    <a href="{{ url('client') }}">
        <i class="fa fa-users"></i> <span>{{Lang::get('messages.Clients')}}</span>
    </a>
</li>
@endif
@if(!Auth::user('user')->user_type === 4)
    <li class="dropdown">
        <a href="#" class="dropdown-toggle">
            <i class="fa fa-tablet"></i>
            <span>{{ Lang::get('messages.Billing')}} </span>
            <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <li><a href="{{ url('billing/estimate') }}"><i
                            class="fa fa-file-o"></i> {{Lang::get('messages.Estimate')}}</a></li>
            <li><a href="{{ url('billing/invoice') }}"><i
                            class="fa fa-file-text-o"></i> {{Lang::get('messages.Invoice')}}</a></li>
        </ul>
    </li>
@endif
<li>
    <a href="{{ url('project') }}">
        <i class="fa fa-lightbulb-o"></i> <span>{{Lang::get('messages.Projects')}}</span>
    </a>
</li>
<<<<<<< HEAD
<<<<<<< HEAD
=======
<li>
    <a href="{{ url('task') }}">
        <i class="fa fa-tasks"></i> <span>{{Lang::get('messages.Task')}}</span>
    </a>
</li>
=======
>>>>>>> project_update
@if(!Auth::user('client'))
    <li>
        <a href="{{ url('task') }}">
            <i class="fa fa-tasks"></i> <span>{{Lang::get('messages.Task')}}</span>
        </a>
    </li>
@endif
<<<<<<< HEAD
=======
<li>
    <a href="{{ url('task') }}">
        <i class="fa fa-tasks"></i> <span>{{Lang::get('messages.Task')}}</span>
    </a>
</li>
>>>>>>> 7961e7ff7602b9e3394a2c9c4880dfe48422af76
=======
>>>>>>> 9c35634d6341f4119334b566861bca0dd430be62
>>>>>>> project_update
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
    <a href="{{ url('event') }}">
        <i class="fa fa-calendar"></i> <span>Event</span>
    </a>
</li>
<li>
    <a href="{{ url('message') }}">
        <i class="fa fa-envelope"></i> <span>{{Lang::get('messages.Message')}}</span>
    </a>
</li>

<li>
    <a href="{{ route('links.index') }}">
        <i class="fa fa-globe"></i>
        <span>Links</span>
    </a>
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
    <a href="https://job.tc/dashboard">
        <i class="fa fa-sitemap"></i> <span>Hiring System</span>
    </a>
    <!--a href="http://localhost:8080/dashboard">
        <i class="fa fa-sitemap"></i> <span>Hiring System</span>
    </a-->
</li>

