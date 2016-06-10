<div class="modal fade edit-modal" id="ajax" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>
@if($projects->task->count() > 0)
@foreach($projects->task as $val)
@if($task_permissions->contains('task_id',$val->task_id) || $projects->user_id === Auth::user('user')->user_id )
<div id="collapse-container-{{ $val->task_id }}" class="panel task-list">
    <div class="panel-heading task-header" data-target="#collapse-{{ $val->task_id }}" role="tab" id="headingOne" data-toggle="collapse" aria-expanded="true" aria-controls="collapseOne">
        <div class="row">
            <div class="col-xs-6">
                <h4 class="panel-title task-list-header">{{ $val->task_title }}</h4>
            </div>
            <div class="col-xs-6">
                <div class="btn-group pull-right">
                    <a href="{{ url('task/' . $val->task_id .'/edit') }}" data-toggle='modal' data-target='.edit-modal' class="edit-tasklist show_edit_form"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="#" class="drag-handle move-tasklist"><i class="fa fa-arrows" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
            </div>
        </div>
    </div>
    <div id="collapse-{{ $val->task_id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
        <div class="panel-body">
            <div class="panel-content">
                <div id="load-task-assign-{{ $val->task_id }}" class="load-task-assign" data-url="{{ url('task/' . $val->task_id ) }}" style="margin-top: -10px;"></div>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach
@else
<div>No Subprojects available.</div>
@endif
<br />
<div class="project-options row">
    <div class="col-md-12">
        <div class="pull-right">
            <a href="#" class="btn-edit btn-shadow btn edit-project" data-toggle="modal" data-target="#edit_project_form">
                <i class="fa fa-pencil" aria-hidden="true"></i> 
                Edit
            </a>
            <a href="#" class="btn-delete btn-shadow btn delete-project">
                <i class="fa fa-times"></i> 
                Delete
            </a>
        </div>
    </div>
</div>