@extends('layouts.default')
@section('content')
<div class="container"></div>
<div class="row">
    <div class="col-md-5">
        @if (count($projects) > 0)
        @foreach($projects as $project)
        <div id="project-{{$project->project_id}}" class="box box-default">
            <div class="box-container">
                <div class="box-header">
                    <h3 class="box-title">{{$project->project_title}}</h3>
                </div>
                <div class="box-body">
                    <div class="box-content">
                        <ul class="list-group">
                            @foreach($teams as $team)                            
                            @if($team->project_id === $project->project_id)
                            @foreach($team->team_member as $team_members)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-6">{{$team_members->user->name}}</div>
                                    <div class="btn-group pull-right">
                                        <a href="#" class="drag-handle">
                                            <i class="fa fa-arrows"></i>
                                        </a>
                                        <a href="#" class="unassign-member">
                                            <i class="fa fa-times"></i>
                                            <input class="user_id" type="hidden" value="{{$team_members->user->user_id}}"/>
                                            <input class="team_id" type="hidden" value="{{$team_members->team_id}}"/>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                            @endif
                            @endforeach
                        </ul>
                        <!--li class="list-group-item">No Employees assigned to this project.</li-->
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
                            <li id="list-group-item-{{$profile->user->user_id}}" class="list-group-item">
                                <div class="row">
                                    <div class="col-md-6"><a data-toggle="collapse" href="#profile-collapse-{{$profile->id}}">{{$profile->user->name}}</a></div>
                                    <div class="btn-group pull-right">
                                        <a href="#" class="drag-handle">
                                            <i class="fa fa-arrows"></i>
                                        </a>
                                        <a href="#" class="hidden unassign-member">
                                            <i class="fa fa-times"></i>
                                            <input class="user_id" type="hidden" value="{{$profile->user->user_id}}"/>
                                            <input class="team_id" type="hidden" value=""/>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div id="profile-collapse-{{$profile->id}}" class="collapse">
                                        <div class="profile-container">
                                            <ul class="list-group">
                                                <li class="list-group-item"><i class="fa fa-envelope-o" aria-hidden="true"></i>&nbsp;{{$profile->user->email}}</li>
                                                <li class="list-group-item"><i class="fa fa-phone-square" aria-hidden="true"></i>&nbsp;{{$profile->user->phone}}</li>
                                                <li class="list-group-item"><i class="fa fa-skype" aria-hidden="true"></i>&nbsp;{{$profile->user->skype}}</li>
                                                <li class="list-group-item"><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;{{$profile->user->address_1}}</li>
                                                <li class="list-group-item"><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;{{$profile->user->address_2}}</li>
                                                <li class="list-group-item"><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;{{$profile->user->zipcode}}</li>
                                                @foreach($countries as $country)
                                                @if($country->country_id === $profile->user->country_id)
                                                <li class="list-group-item"><i class="fa fa-globe" aria-hidden="true"></i>&nbsp;{{$country->country_name}}</li>
                                                @endif
                                                @endforeach
                                                <li class="list-group-item"><i class="fa fa-facebook-square" aria-hidden="true"></i>&nbsp;{{$profile->user->facebook}}</li>
                                                <li class="list-group-item"><i class="fa fa-linkedin-square" aria-hidden="true"></i>&nbsp;{{$profile->user->linkedin}}</li>
                                            </ul>
                                        </div>                                        
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>  
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="box box-default">
            <div class="box-container">
                <div class="box-header">
                    <h3 class="box-title">Teams</h3>
                </div>
                <div class="box-body">
                    <div class="box-content">
                        <ul class="list-group">
                            @if(count($team_grouping) > 0)
                            @foreach($team_grouping[0]->team_project as $team_projects)
                            <li class="list-group-item">{{$team_projects->team_id}}</li>
                            @endforeach
                            @else
                            <li class="list-group-item">No Teams Available.</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop