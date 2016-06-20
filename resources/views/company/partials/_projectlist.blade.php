<div class="mini-space"></div>
<div class="row">
    <a data-toggle="modal" href="#add_user">
        <button class="btn btn-sm btn-default btn-shadow"><i class="fa fa-plus-circle"></i> Add New User</button>
    </a>
</div>
<div class="modal fade" id="edit_project_form" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>
<div class="mini-space"></div>
<div class="row">
    <div class="col-md-6">
        @if (count($projects) > 0)
        @foreach($projects as $project)
        <div id="project-{{$project->project_id}}" class="box box-default">
            <div class="box-container">
                <div class="box-header">
                    <h3 class="box-title">{{$project->project_title}}</h3>
                </div>
                <div class="box-body">
                    <div class="box-content">
                        <ul class="company-list-group list-group">
                            @foreach($team_companies as $team_company)
                            @if($team_company->project_id === $project->project_id)
                            <li id="company-{{$team_company->company_id}}" class="list-group-item">
                                <div class="row">
                                    <div class="col-md-9">
                                        <i class="pull-left fa fa-chevron-down" aria-hidden="true"></i>
                                        {{$team_company->company->name}}
                                    </div>
                                    <div class="pull-right">
                                        <a href="#" class="drag-handle">
                                            <i class="fa fa-arrows"></i>
                                        </a>
                                        <a href="#" class="unassign-company">
                                            <i class="fa fa-times"></i>
                                            <input class="company_id" type="hidden" value="{{$team_company->company_id}}"/>
                                            <input class="project_id" type="hidden" value="{{$project->project_id}}"/>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            @endif
                            @endforeach
                        </ul>
                        <ul class="taskgroup-list list-group">
                            @foreach($teams as $team)                            
                            @if($team->project_id === $project->project_id)
                            @foreach($team->team_member as $team_members)
                            <li class="list-group-item">
                                <div class="row ">
                                    <div class="col-md-10">
                                        <a class="team-member name" data-toggle="collapse" href="#team-member-collapse-{{$team_members->user->user_id}}-{{$project->project_id}}">
                                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                            @if($team_members->user->photo === '')
                                            <img class="employee-photo" src="{{url('assets/user/default-avatar.jpg')}}" />
                                            @else
                                            <img class="employee-photo" src="{{url($team_members->user->photo)}}"/>
                                            @endif
                                            {{$team_members->user->name}}
                                        </a>
                                    </div>
                                    <div class="pull-right">
                                        <div class="btn-group pull-right">
                                            <a href="#" class="drag-handle">
                                                <i class="fa fa-arrows"></i>
                                            </a>
                                            <a href="#" class="unassign-member">
                                                <i class="fa fa-times"></i>
                                                <input class="user_id" type="hidden" value="{{$team_members->user->user_id}}"/>
                                                <input class="team_id" type="hidden" value="{{$team_members->team_id}}"/>
                                                <input class="project_id" type="hidden" value="{{$project->project_id}}"/>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @if($project->user_id === Auth::user()->user_id)
                                <div class="row">
                                    <div id="team-member-collapse-{{$team_members->user->user_id}}-{{$project->project_id}}" class="collapse">
                                        <div class="task-list-container">
                                            <label class='center-block taskgroup-title'>Available Sub Projects</label>
                                            <ul class="taskgroup-list list-group">
                                                @foreach($project->task as $task)
                                                <li class="list-group-item">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            {{$task->task_title}}
                                                        </div>
                                                        <div class="pull-right">
                                                            @if($project->task_permission
                                                            ->where('user_id',$team_members->user->user_id)
                                                            ->where('task_id',$task->task_id)
                                                            ->where('project_id',$project->project_id)->count() > 0)

                                                            @foreach($project->task_permission
                                                            ->where('user_id',$team_members->user->user_id)
                                                            ->where('task_id',$task->task_id)
                                                            ->where('project_id',$project->project_id)    
                                                            as $permission)
                                                            <div class="btn btn-default btn-shadow bg-green task-permission">
                                                                <i class="fa fa-check" aria-hidden="true"></i>                                                                
                                                                <input class="user_id" type="hidden" value="{{$team_members->user->user_id}}"/>
                                                                <input class="task_id" type="hidden" value="{{$task->task_id}}"/>
                                                                <input class="project_id" type="hidden" value="{{$project->project_id}}"/>
                                                            </div>
                                                            @endforeach
                                                            @else
                                                            <div class="btn btn-default btn-shadow bg-gray task-permission">
                                                                <i class="fa fa-plus" aria-hidden="true"></i>                                                                
                                                                <input class="user_id" type="hidden" value="{{$team_members->user->user_id}}"/>
                                                                <input class="task_id" type="hidden" value="{{$task->task_id}}"/>
                                                                <input class="project_id" type="hidden" value="{{$project->project_id}}"/>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </li>
                            @endforeach
                            @endif
                            @endforeach
                        </ul>
                        <!--li class="list-group-item">No Employees assigned to this project.</li-->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-right">
                                    @if(Auth::user('user')->user_id === $project->user_id)
                                    <a href="{{ route('project.edit',$project->project_id) }}" class="btn-edit btn-shadow btn show_edit_form" data-toggle='modal' data-target='#edit_project_form'><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
                                    <a href="{{ route('project.destroy',$project->project_id) }}" class="btn-delete btn-shadow btn alert_delete"><i class='fa fa-times'></i> Delete</a>
                                    @endif
                                    <input type="hidden" class="project_id" value="{{$project->project_id}}" />
                                </div>
                            </div>
                        </div>
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
                        <ul class="taskgroup-list list-group">
                            <li class="list-group-item">No Projects Available</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    <div class="col-md-3">
        <div class="box box-default">
            <div class="box-container">
                <div class="box-header">
                    <h3 class="box-title">Employees</h3>
                </div>
                <div class="box-body">
                    <div class="box-content">
                        <ul class="taskgroup-list list-group">
                            @foreach($profiles as $profile)
                            <li id="profile-{{$profile->user->user_id}}" class="list-group-item">
                                <div class="row">
                                    <div class="col-md-9">
                                        <a class="profile-toggle" data-toggle="collapse" href="#profile-collapse-{{$profile->user->user_id}}">
                                            <i class="pull-left fa fa-chevron-down" aria-hidden="true">
                                                @if($profile->user->photo === '')
                                                <img class="employee-photo" src="{{url('assets/user/default-avatar.jpg')}}" />
                                                @else
                                                <img class="employee-photo" src="{{url($profile->user->photo)}}"/>
                                                @endif
                                            </i>
                                            <div class="name">{{$profile->user->name}}</div>
                                        </a>
                                    </div>
                                    <div class="pull-right">
                                        <a class="icon icon-btn edit-profile">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>
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
                                    <div id="profile-collapse-{{$profile->user->user_id}}" class="collapse">
                                        <div class="profile-container">
                                            <ul class="list-group">                                                        
                                                <li class="email list-group-item"><i class="fa fa-envelope-o" aria-hidden="true"></i>&nbsp;<a href="mailto:{{$profile->user->email}}">{{$profile->user->email}}</a></li>
                                                <li class="phone list-group-item"><i class="fa fa-phone-square" aria-hidden="true"></i>&nbsp;<a href="tel:{{$profile->user->phone}}">{{$profile->user->phone}}<a></li>
                                                            <li class="skype list-group-item"><i class="fa fa-skype" aria-hidden="true"></i>&nbsp;<a href="skype:{{$profile->user->skype}}">{{$profile->user->skype}}</a></li>
                                                            <li class="address_1 list-group-item"><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;{{$profile->user->address_1}}</li>
                                                            <li class="address_2 list-group-item"><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;{{$profile->user->address_2}}</li>
                                                            <li class="zipcode list-group-item"><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;{{$profile->user->zipcode}}</li>
                                                            <li class="country list-group-item">
                                                                <i class="fa fa-globe" aria-hidden="true"></i>&nbsp;
                                                                @foreach($countries as $country)
                                                                @if($country->country_id === $profile->user->country_id)
                                                                {{$country->country_name}}
                                                                @endif
                                                                @endforeach
                                                            </li>
                                                            <li class="country-dropdown hidden list-group-item">
                                                                <form role="form">
                                                                    <div class="form-group">
                                                                        <label><i class="fa fa-globe" aria-hidden="true"></i></label>
                                                                        &nbsp;
                                                                        <div class="btn-group">
                                                                            <select class="form-control edit-country" name="country_id" aria-describedby="country-addon">
                                                                                @foreach($countries as $country)
                                                                                @if($country->country_id === $profile->user->country_id)
                                                                                <option selected="selected" value="{{$country->country_id}}">{{$country->country_name}}</option>
                                                                                @else
                                                                                <option value="{{$country->country_id}}">{{$country->country_name}}</option>
                                                                                @endif
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </form>    
                                                            </li>
                                                            <li class="facebook list-group-item"><i class="fa fa-facebook-square" aria-hidden="true"></i>&nbsp;{{$profile->user->facebook}}</li>
                                                            <li class="linkedin list-group-item"><i class="fa fa-linkedin-square" aria-hidden="true"></i>&nbsp;{{$profile->user->linkedin}}</li>
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
                                                            <div class="col-md-3">
                                                                <div class="box box-default">
                                                                    <div class="box-container">
                                                                        <div class="box-header">
                                                                            <h3 class="box-title">Companies</h3>
                                                                        </div>
                                                                        <div class="box-body">
                                                                            <div class="box-content">
                                                                                <ul class="company-list-group list-group">
                                                                                    @foreach($user_companies as $user_company)
                                                                                    <li id="company-{{$user_company->id}}" class="list-group-item">
                                                                                        <div class="row">
                                                                                            <div class="col-md-9">
                                                                                                <i class="pull-left fa fa-chevron-down" aria-hidden="true"></i>
                                                                                                {{$user_company->name}}
                                                                                            </div>
                                                                                            <div class="pull-right">
                                                                                                <a href="#" class="drag-handle">
                                                                                                    <i class="fa fa-arrows"></i>
                                                                                                </a>
                                                                                                <a href="#" class="unassign-company hidden">
                                                                                                    <i class="fa fa-times"></i>
                                                                                                    <input class="company_id" type="hidden" value="{{$user_company->id}}"/>
                                                                                                    <input class="project_id" type="hidden" value=""/>
                                                                                                </a>
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
                                                            </div>

