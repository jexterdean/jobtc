@extends('layouts.default')
@section('content')
<div class="container"></div>
<div class="row">
    <div class="col-md-5">
        @if (count($projects) > 0)
        @foreach($projects as $project)
        <div class="box box-default">
            <div class="box-container">
                <div class="box-header">
                    <h3 class="box-title">{{$project->project_title}}</h3>
                </div>
                <div class="box-body">
                    <div class="box-content">
                        <ul class="list-group">
                            @foreach($teams as $team)                            
                            @if($team->project_id === $project->project_id)
                            <li class="list-group-item">{{$team->id}}</li>
                            @endif
                            @endforeach
                            <!--li class="list-group-item">No Employees assigned to this project.</li-->
                        </ul>
                        <input type="hidden" class="project_id" value="{{$project->project_id}}"/>
                    </div>
                </div>
            </div>
            
        </div>
        @endforeach
        @else
        <div class="box box-default">
            <div class="box-container">
                <div class="box-header">
                    <h3 class="box-title">&nbsp;</h3>
                </div>
                <div class="box-body">
                    <div class="box-content">
                        <ul class="list-group">
                            <li class="list-group-item">No Projects Available</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    <div class="col-md-5">
        <div class="box box-default">
            <div class="box-container">
                <div class="box-header">
                    <h3 class="box-title">Employees</h3>
                </div>
                <div class="box-body">
                    <div class="box-content">
                        <ul class="list-group">
                            @foreach($profiles as $profile)
                            <li id="list-group-item-{{$profile->user->user_id}}" class="list-group-item"><a href="#" class="drag-handle icon icon-btn move-tasklist"><i class="fa fa-arrows"></i></a>{{$profile->user->name}}</li>
                            @endforeach
                        </ul>
                    </div>  
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        
    </div>
</div>
@stop