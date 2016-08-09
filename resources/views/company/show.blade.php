@extends('layouts.default')
@section('content')
<div class="column">

    <div class="portlet">
        <div class="portlet-header">Projects</div>
        <div class="portlet-content">
            <ul class='list-group'>
                @foreach($projects as $project)
                <li class='list-group-item'>
                    <a target="_blank" href="{{url('project/'.$project->project_id)}}">{{$project->project_title}}</a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="portlet">
        <div class="portlet-header">Jobs</div>
        <div class="portlet-content">
            @foreach($jobs as $job)
            <li class='list-group-item'>
                <a target="_blank" href="{{url('job/'.$job->id)}}">{{$job->title}}</a>
            </li>
            @endforeach
        </div>
    </div>

</div>

<div class="column">
    <div class="portlet">
        <div class="portlet-header">Employees</div>
        <div class="portlet-content">
            @foreach($employees as $employee)
            <li class='list-group-item'>
                <a target="_blank" href="{{url('user/'.$employee->user_id.'/company/'.$employee->company_id)}}">{{$employee->user->name}}</a>
            </li>
            @endforeach
        </div>
    </div>
</div>

<div class="column">
    <div class="portlet">
        <div class="portlet-header">Applicants</div>
        <div class="portlet-content">
            @foreach($applicants as $applicant)
            <li class='list-group-item'>
                <a target="_blank" href="{{url('applicant/'.$applicant->id)}}">{{$applicant->name}}</a>
            </li>
            @endforeach
        </div>
    </div>
</div>
<div class="column">
    <div class="portlet">
        <div class="portlet-header">Comments</div>
        <div class="portlet-content">
            @foreach($comments as $comment)
            <li class='list-group-item'>
                <a target="_blank" data-toggle="tooltip" data-placement="right" title="{{$comment->comment}}" href="{{url('applicant/'.$comment->applicant->id)}}">{{$comment->comment}}</a>
            </li>
            @endforeach
        </div>
    </div>
</div>
@stop