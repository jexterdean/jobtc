@extends('layouts.default')
@section('content')
<ul id="company_tabs" class="nav nav-tabs">
    <li class="active"><a data-toggle="pill" href="#my_tasks">My Tasks</a></li>
    <li><a data-toggle="pill" href="#assign_projects">Assign Projects</a></li>
    <li><a data-toggle="pill" href="#assign_tests">Assign Tests</a></li>
    <li><a data-toggle="pill" href="#assign_authority_levels">Assign Authority Levels</a></li>
</ul>
<div class="tab-content">
    <div id="my_tasks" class="tab-pane fade in active">
        @include('company.partials._mytasklist')
    </div>
    <div id="assign_projects" class="tab-pane fade in">
        @include('company.partials._projectlist')
    </div><!--assign projects tab content-->

    <div id="assign_tests" class="tab-pane fade in">
        @include('company.partials._joblist')
    </div>
    
    <div id="assign_authority_levels" class="tab-pane fade in">
        @include('company.partials._rolelist')
    </div>
</div>
@stop