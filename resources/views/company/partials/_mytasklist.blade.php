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
                    <div id="project-collapse-{{ $project->project_id }}" class="box-content collapse in">
                        @foreach($project->task as $val)
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
                                            @if(Auth::user('user')->user_id === $val->user_id)
                                            <a href="{{ url('task/delete/'.$val->task_id) }}" class="delete-tasklist"><i class="fa fa-times" aria-hidden="true"></i></a>
                                            @endif
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
                        @endforeach
                    </div><!--Box Container-->
                </div>
            </div>
        </div>
    </div>
@endforeach
</div>
@endforeach

