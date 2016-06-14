@extends('layouts.default')
@section('content')
<ul id="company_tabs" class="nav nav-tabs">
    <li class="projects_tab active"><a data-toggle="pill" href="#my_tasks">Projects</a></li>
    @if(Auth::user('user')->level() === 1)
    <li><a class="assign_projects_tab" data-toggle="pill" href="#assign_projects">Assign Projects</a></li>
    <li><a class="assign_tests_tab" data-toggle="pill" href="#assign_tests">Assign Tests</a></li>
    <li><a class="assign_authority_levels_tab" data-toggle="pill" href="#assign_authority_levels">Assign Authority Levels</a></li>
    <li><a class="share_jobs_tab" data-toggle="pill" href="#share_jobs">Share Jobs</a></li>
    @endif
</ul>
<div class="tab-content">
    <div id="my_tasks" class="tab-pane fade in active">
        @include('company.partials._mytasklist')
    </div>
    @if(Auth::user('user')->level() === 1)
    <div id="assign_projects" class="tab-pane fade in">
        <!--Load the content with AJAX when the user clicks on tab-->
    </div>

    <div id="assign_tests" class="tab-pane fade in">
        <!--Load the content with AJAX when the user clicks on tab-->
    </div>
    
    <div id="assign_authority_levels" class="tab-pane fade in">
        <!--Load the content with AJAX when the user clicks on tab-->
    </div>
    
    <div id="share_jobs" class="tab-pane fade in">
        <!--Load the content with AJAX when the user clicks on tab-->
    </div>
    
    @endif
</div>
@stop