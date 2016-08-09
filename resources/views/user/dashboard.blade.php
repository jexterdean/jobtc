@extends('layouts.default')
@section('content')
<div id="my_projects">
    <div class="project_container">
        @foreach($projects->chunk(2) as $chunk)
        <div class="project-row row">
            @foreach($chunk as $index => $project)
            <div class="col-md-6">
                <div  class="box box-default">
                    <div class="box-container">
                        <div class="box-header toggle-subprojects" id="project-{{$project->project_id}}" data-toggle="collapse" data-target="#project-collapse-{{ $project->project_id }}">
                            <h3 class="box-title">{{$project->project_title}}</h3>
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
    <div class="mini-space"></div>
    <div class="project_tab_options">
        <a href="#" id="add-project" class="btn btn-shadow btn-default add-project">
            <i class="fa fa-plus"></i> 
            <strong>New Project</strong>
        </a>
        <input class="company_id" type="hidden" value="{{$company_id}}"/>
    </div>
    <div class="mini-space"></div>

    <div class="modal fade edit-modal" id="edit_project_form" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            </div>
        </div>
    </div>
</div>
@stop
