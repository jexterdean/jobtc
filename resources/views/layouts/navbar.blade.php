<li>
    <a href="{{ url('dashboard') }}">
        <i class="fa fa-home"></i> <span>{{Lang::get('messages.DashBoard')}}</span>
    </a>
</li>
@role('admin')
<li>
    <a href="{{ url('client') }}">
        <i class="fa fa-users"></i> <span>{{Lang::get('messages.Clients')}}</span>
    </a>
</li>
@endrole
@if(!Auth::user()->is('staff'))
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
<li>
    <a href="{{ url('task') }}">
        <i class="fa fa-tasks"></i> <span>{{Lang::get('messages.Task')}}</span>
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
