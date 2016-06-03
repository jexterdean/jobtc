@foreach($projects->chunk(2) as $chunk)
<div class="row">
@foreach($chunk as $project)
    <div class="col-md-6">
        <div  class="box box-default">
            <div class="box-container">
                <div class="box-header" id="project-{{$project->project_id}}" data-toggle="collapse" data-target="#project-collapse-{{ $project->project_id }}">
                    <h3 class="box-title">{{$project->project_title}}</h3>
                </div>
                <div class="box-body">
                    <div id="project-collapse-{{ $project->project_id }}" class="box-content collapse">
                        @foreach($project->task as $val)
                        @if($task_permissions->contains('task_id',$val->task_id) || $project->user_id === Auth::user('user')->user_id )
                        <div id="collapse-container-{{ $val->task_id }}" class="panel task-list">
                            <div class="panel-heading task-header" data-target="#collapse-{{ $val->task_id }}" role="tab" id="headingOne" data-toggle="collapse" aria-expanded="true" aria-controls="collapseOne">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <h4 class="panel-title task-list-header">{{ $val->task_title }}</h4>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="btn-group pull-right">
                                            <a href="{{ url('task/' . $val->task_id .'/edit') }}" data-toggle='modal' data-target='#ajax1' class="edit-tasklist show_edit_form"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href="#" class="drag-handle move-tasklist"><i class="fa fa-arrows" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="collapse-{{ $val->task_id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                <div class="panel-body">
                                    <div class="panel-content">
                                        <div class="load-task-assign" data-url="{{ url('task/' . $val->task_id ) }}" style="margin-top: -10px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div><!--Box Container-->
                </div>
            </div>
        </div>
    </div>
@endforeach
</div>
@endforeach

