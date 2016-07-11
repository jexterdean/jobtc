@extends('layouts.default')
@section('content')
<div class="modal fade" id="add_attachment" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 class="modal-title">Add Attachments</h4>
            </div>
            {!!  Form::open(['files' => 'true', 'method' => 'POST','route' => ['attachment.store'],'class' => 'attachment-form'])  !!}
            <div class="modal-body">
                {!!  Form::hidden('belongs_to','project')  !!}
                {!!  Form::hidden('unique_id', $project->project_id)  !!}
                <div class="form-group">
                    {!!  Form::input('text','attachment_title','',['class' => 'form-control', 'placeholder' => 'Title', 'tabindex' => '1']) !!}
                </div>
                <div class="form-group">
                    {!!  Form::textarea('attachment_description','',['size' => '30x3', 'class' => 'form-control',
                    'placeholder' => 'Description', 'tabindex' => '2']) !!}
                </div>
                <div class="form-group">
                    {!! Form::input('file','file','') !!}
                </div>
            </div>
            <div class="modal-footer">
                <div class="form-group">
                    {!!  Form::submit('Add',['class' => 'btn btn-primary'])  !!}
                </div>
            </div>
            {!!  Form::close() !!}
        </div>
    </div>
</div>
<div class="modal fade" id="add_task" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 class="modal-title">Add Briefcase</h4>
            </div>
            {!!  Form::open(['method' => 'POST','route' => ['task.store'],'class' => 'task-form'])  !!}
            <div class="modal-body">
                {!!  Form::hidden('belongs_to','project')  !!}
                {!!  Form::hidden('unique_id', $project->project_id)  !!}
                {!!  Form::hidden('project_id', $project->project_id)  !!}
                <div class="form-group">
                    {!!  Form::input('text','task_title','',['class' => 'form-control', 'placeholder' => 'Title', 'tabindex' => '1']) !!}
                </div>
                <div class="form-group">
                    {!!  Form::textarea('task_description','',['size' => '30x3', 'class' => 'form-control',
                    'placeholder' => 'Description', 'tabindex' => '2']) !!}
                </div>
                <div class="form-group">
                    {!!  Form::input('text','due_date','',['class' => 'form-control form-control-inline
                    input-medium date-picker', 'placeholder' => 'Due Date', 'tabindex' => '3', 'data-inputmask' => "'alias': 'dd-mm-yyyy'", 'data-mask' => 'true'])  !!}
                </div>
            </div>
            <div class="modal-footer">
                <div class="form-group">
                    {!!  Form::submit('Add',['class' => 'btn btn-shadow btn-edit', 'tabindex' => '5'])  !!}
                </div>
            </div>
            {!!  Form::close() !!}
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="modal fade edit-modal" id="ajax" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="assign-task-container"></div>
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <?php
                $ref = 1;
                ?>
                @if(count($tasks) > 0)
                @foreach($tasks as $val)
                @if($task_permissions->contains('task_id',$val->task_id) || $project->user_id === Auth::user('user')->user_id )
                <div class="modal fade" id="edit_task_{{ $val->task_id }}" role="basic" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        @include('task/edit', ['task'=> $val] )
                        </div>
                    </div>
                </div>
                <div id="collapse-container-{{ $val->task_id }}" class="panel task-list">
                    <div class="panel-heading task-header" data-target="#collapse-{{ $val->task_id }}" role="tab" id="headingOne" data-toggle="collapse" aria-expanded="true" aria-controls="collapseOne">
                        <div class="row">
                            <div class="col-xs-6">
                                <h4 class="panel-title task-list-header">{{ $val->task_title }}</h4>
                            </div>
                            <div class="col-xs-6">
                                <div class="btn-group pull-right">
                                    <!--a href="#" data-toggle='modal' data-target='#edit_task_{{ $val->task_id }}' class="edit-tasklist"><i class="fa fa-pencil"></i></a-->&nbsp;&nbsp;&nbsp;&nbsp;
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
                <?php $ref++; ?>
                @endif
                @endforeach
                @endif
                <br/>
            </div>
            <div class="row">
                @if($module_permissions->where('slug','create.briefcases')->count() === 1)
                <button class="btn btn-shadow btn-default" data-toggle="modal" data-target="#add_task"><i class="fa fa-plus"></i> <strong>New Briefcase</strong></button>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel-group" id="accordion_" role="tablist" aria-multiselectable="true">
                @include('common.note',['note' => $note, 'belongs_to' => 'project', 'unique_id' => $project->project_id])
                <div class="panel panel-default">
                    <div class="panel-container">
                        <div class="panel-heading collapsed" data-target="#collapseTwo" role="tab" id="headingTwo" data-toggle="collapse" data-parent="#accordion_" aria-expanded="false" aria-controls="collapseTwo">
                            <h4 class="panel-title">Project Details<span class="pull-right">{{ $project->ref_no }}</span></h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                        <div class="panel-body">
                            <div class="panel-content">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td><strong>Title:</strong></td>
                                            <td>{{ $project->project_title }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Description:</strong></td>
                                            <td>{{ $project->project_description }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Account:</strong></td>
                                            <td>{{ $project->account }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Company:</strong></td>
                                            <td>{{ $companies->where('id',$project->company_id)->first()->name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Project Type:</strong></td>
                                            <td>{{ $project->project_type }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Start & Deadline:</strong></td>
                                            <td>
                                                {{ date("d M Y, h:ia", strtotime($project->start_date)) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Deadline:</strong></td>
                                            <td>
                                                {{ date("d M Y, h:ia", strtotime($project->deadline)) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Currency & Rate:</strong></td>
                                            <td>
                                                {{ $project->currency }}
                                                {{ $project->rate_value }}
                                                {{ $project->rate_type }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <a href="{{ route('project.destroy',$project->project_id) }}" class="alert_delete"><i class='fa-2x fa fa-trash-o'></i></a>&nbsp;&nbsp;&nbsp;
                                        <a href="{{ route('project.edit',$project->project_id) }}" class="show_edit_form" data-toggle='modal' data-target='#ajax'><i class='fa-2x fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div><!--End Project Details-->
                @include('common.employeeList')
            </div>
        </div>
    </div>
</div>
@stop


