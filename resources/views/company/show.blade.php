@extends('layouts.default')
@section('content')
<div class="column">

    <div class="portlet fixed-portlet">
        <div class="portlet-header reduce-portlet-header">Projects <i class="glyphicon glyphicon-arrow-right"></i> Project Dash</div>
        <div class="portlet-content">
            <ul class='list-group'>
                @foreach($projects as $project)
                <li class='list-group-item'>
                    @if(strlen($project->project_title) > 23)
                    <a target="_blank" data-toggle="tooltip" data-placement="top" title="{{$project->project_title}}" href="{{url('project/'.$project->project_id)}}">{{$project->project_title}}</a>
                    @else
                    <a target="_blank" href="{{url('project/'.$project->project_id)}}">{{$project->project_title}}</a>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="portlet fixed-portlet">
        <div class="portlet-header reduce-portlet-header">Jobs <i class="glyphicon glyphicon-arrow-right"></i> Job Dash</div>
        <div class="portlet-content">
            @foreach($jobs as $job)
            <li class='list-group-item'>
                @if(strlen($job->title) > 23)
                <a target="_blank" data-toggle="tooltip" data-placement="top" title="{{$job->title}}" href="{{url('job/'.$job->id)}}">{{$job->title}}</a>
                @else
                <a target="_blank" href="{{url('job/'.$job->id)}}">{{$job->title}}</a>
                @endif
            </li>
            @endforeach
        </div>
    </div>

    <div class="portlet fixed-portlet">
        <div class="portlet-header reduce-portlet-header">Tests <i class="glyphicon glyphicon-arrow-right"></i> Test Dash</div>
        <div class="portlet-content">
            @foreach($tests as $test)
            <li class='list-group-item'>
                @if(strlen($test->title) > 23)
                <a target="_blank" data-toggle="tooltip" data-placement="top" title="{{$test->title}}" href="{{url('quiz/'. $test->id .'?p=review&company_id='.$test->company_id)}}">{{$test->title}}</a>
                @else
                <a target="_blank" href="{{url('quiz/'. $test->id .'?p=review&company_id='.$test->company_id)}}">{{$test->title}}</a>
                @endif
            </li>
            @endforeach
        </div>
    </div>
</div>

<div class="column">
    <div class="portlet fixed-portlet">
        <div class="portlet-header reduce-portlet-header">Employees <i class="glyphicon glyphicon-arrow-right"></i> Employee Dash</div>
        <div class="portlet-content">
            @foreach($employees as $employee)
            <li class='list-group-item'>
                @if(strlen($employee->user->name) > 23)
                <a target="_blank" data-toggle="tooltip" data-placement="top" title="{{$employee->user->name}}" href="{{url('user/'.$employee->user_id.'/company/'.$employee->company_id)}}">{{$employee->user->name}}</a>
                @else
                <a target="_blank" href="{{url('user/'.$employee->user_id.'/company/'.$employee->company_id)}}">{{$employee->user->name}}</a>
                @endif

            </li>
            @endforeach
        </div>
    </div>
    <div class="portlet fixed-portlet">
        <div class="portlet-header reduce-portlet-header">Links</div>
        <div class="portlet-content">
            @foreach($links as $link)
            <li class='list-group-item'>
                {{--*/ $parse_url = parse_url($link->url) /*--}}
                {{--*/ $url = empty($parse_url['scheme']) ? 'http://' . $link->url :  $link->url /*--}}
                @if(strlen($link->title) > 23)
                <a target="_blank" data-toggle="tooltip" data-placement="top" title="{{$link->title}}" href="{{$url}}">{{$link->title}}</a>
                @else
                <a target="_blank" href="{{$url}}">{{$link->title}}</a>
                @endif
            </li>
            @endforeach
        </div>
    </div>
    <div class="portlet fixed-portlet">
        <div class="portlet-header reduce-portlet-header">Videos</div>
        <div class="portlet-content">
        </div>
    </div>
</div>

<div class="column">
    <div class="portlet fixed-portlet">
        <div class="portlet-header reduce-portlet-header">Applicants <i class="glyphicon glyphicon-arrow-right"></i> Applicant Dash</div>
        <div class="portlet-content">
            @foreach($applicants as $applicant)
            <li class='list-group-item'>
                @if(strlen($applicant->name) > 23)
                <a target="_blank" data-toggle="tooltip" data-placement="top" title="{{$applicant->name}}" href="{{url('applicant/'.$applicant->id)}}">{{$applicant->name}}</a>
                @else
                <a target="_blank" href="{{url('applicant/'.$applicant->id)}}">{{$applicant->name}}</a>
                @endif
            </li>
            @endforeach
        </div>
    </div>
    <div class="portlet fixed-portlet">
        <div class="portlet-header reduce-portlet-header">Briefcase Items <i class="glyphicon glyphicon-arrow-right"></i> Item Dash</div>
        <div class="portlet-content">
            @foreach($items as $item)
            <li class='list-group-item'>
                @if(strlen($item->checklist_header) > 23)
                <a target="_blank" data-toggle="tooltip" data-placement="top" title="{{$item->checklist_header}}" href="{{url('taskitem/'.$item->id)}}">{{$item->checklist_header}}</a>
                @else
                <a target="_blank" href="{{url('taskitem/'.$item->id)}}">{{$item->checklist_header}}</a>
                @endif
            </li>
            @endforeach
        </div>
    </div>
    @include('common.note',['note' => $note, 'belongs_to' => 'company', 'unique_id' => $company_id])
</div>
<div class="column">
    <div class="portlet fixed-portlet">
        <div class="portlet-header reduce-portlet-header">Comments</div>
        <div class="portlet-content">
            @foreach($comments as $comment)
            <li class='list-group-item'>
                <a target="_blank" data-toggle="tooltip" data-placement="top" title="{{'<strong>' . $comment->applicant->name . '</strong><br/><strong>JOB:</strong> ' . $comment->applicant->job->title}}" href="{{url('applicant/'.$comment->applicant->id)}}">{{$comment->comment}}</a>
            </li>
            @endforeach
        </div>
    </div>
    <div class="portlet fixed-portlet">
        <div class="portlet-header reduce-portlet-header">Briefcase <i class="glyphicon glyphicon-arrow-right"></i> Briefcase Dash</div>
        <div class="portlet-content">
            @foreach($briefcases as $briefcase)
            <li class='list-group-item'>
                @if(strlen($briefcase->task_title) > 23)
                <a target="_blank" data-toggle="tooltip" data-placement="top" title="{{$briefcase->task_title}}" href="{{url('briefcase/'.$briefcase->task_id)}}">{{$briefcase->task_title}}</a>
                @else
                <a target="_blank" href="{{url('briefcase/'.$briefcase->task_id)}}">{{$briefcase->task_title}}</a>
                @endif
            </li>
            @endforeach
        </div>
    </div>
</div>
@stop