<div class="mini-space"></div>
<ul id="projects_tabs" class="nav nav-tabs">
    <li class="my_projects_tab active"><a data-toggle="pill" href="#my_projects">My Projects</a></li>
    <li class="shared_projects_tab"><a data-toggle="pill" href="#shared_projects">Shared Projects</a></li>
</ul>
<div class="tab-content">
    <div id="my_projects" class="tab-pane fade in active">
        @foreach($projects->chunk(2) as $chunk)
        <div class="row">
            @foreach($chunk as $project)
            <div class="col-md-6">
                <div  class="box box-default">
                    <div class="box-container">
                        <div class="box-header toggle-subprojects" id="project-{{$project->project_id}}" data-toggle="collapse" data-target="#project-collapse-{{ $project->project_id }}">
                            <h3 class="box-title">{{$project->project_title}}</h3>
                            <div class="pull-right">
                                @if(intval($project->company_id) !== intval($company_id))
                                <div class="row">
                                    <label>Shared by {{$project->company->name}}</label>
                                </div>
                                @endif
                                @if(Auth::user('user')->user_id !== $project->user_id)
                                <div class="row">
                                    <label>Shared by {{$project->user->name}}</label>
                                </div>
                                @endif
                            </div>
                            <input class="project_id" type="hidden" value="{{$project->project_id}}"/>
                            <input class="company_id" type="hidden" value="{{$project->company_id}}"/>
                        </div>
                        <div class="box-body">
                            <div id="project-collapse-{{ $project->project_id }}" class="box-content collapse">

                            </div><!--Box Container-->
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
    <div id="shared_projects" class="tab-pane fade">
        @foreach($shared_projects->chunk(2) as $chunk)
        <div class="row">
            @foreach($chunk as $project)
            @if($project->user_id !== Auth::user('user')->user_id || intval($project->company_id) !== intval($company_id))
            <div class="col-md-6">
                <div  class="box box-default">
                    <div class="box-container">
                        <div class="box-header toggle-subprojects" id="project-{{$project->project_id}}" data-toggle="collapse" data-target="#project-collapse-{{ $project->project_id }}">
                            <h3 class="box-title">{{$project->project_title}}</h3>
                            <div class="pull-right">
                                @if(intval($project->company_id) !== intval($company_id))
                                <div class="row">
                                    <label>Shared by {{$project->company->name}}</label>
                                </div>
                                @endif
                                @if(Auth::user('user')->user_id !== $project->user_id)
                                <div class="row">
                                    <label>Shared by {{$project->user->name}}</label>
                                </div>
                                @endif
                            </div>
                            <input class="project_id" type="hidden" value="{{$project->project_id}}"/>
                            <input class="company_id" type="hidden" value="{{$project->company_id}}"/>
                        </div>
                        <div class="box-body">
                            <div id="project-collapse-{{ $project->project_id }}" class="box-content collapse">

                            </div><!--Box Container-->
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
        @endforeach
    </div>
</div>




