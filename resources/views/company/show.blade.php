@extends('layouts.default')
@section('content')
<ul id="company_tabs" class="nav nav-tabs">
    <li class="active"><a data-toggle="pill" href="#assign_projects">Assign Projects</a></li>
    <li><a data-toggle="pill" href="#assign_tests">Assign Tests</a></li>
</ul>

<div class="tab-content">
    <div id="assign_projects" class="tab-pane fade in active">
        @include('company.partials._projectlist')
    </div><!--assign projects tab content-->

    <div id="assign_tests" class="tab-pane fade in">
        @include('company.partials._joblist')
    </div>
</div>

@stop