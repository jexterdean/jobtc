@extends('layouts.default')
@section('content')
<div class="modal fade" id="add_attachment" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
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
<div class="col-md-12">
    <div class="modal fade" id="ajax" role="basic" aria-hidden="true">
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
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion" data-target="#collapse-{{ $val->task_id }}" aria-expanded="true" aria-controls="collapseOne">
                          <h4 class="panel-title">
                              {{ $val->task_title }}
                          </h4>
                        </div>
                        <div id="collapse-{{ $val->task_id }}" class="panel-collapse collapse {{ $ref != 1 ? '' : 'in' }}" role="tabpanel" aria-labelledby="headingOne">
                          <div class="panel-body">
                            <div class="load-task-assign" data-url="{{ url('task/' . $val->task_id ) }}" style="margin-top: -10px;"></div>
                          </div>
                        </div>
                    </div>
                    <?php $ref++;?>
                    @endforeach
                @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel-group" id="accordion_" role="tablist" aria-multiselectable="true">
                  @include('common.task',['tasks' => $tasks, 'belongs_to' => 'project', 'unique_id' => $project->project_id,'project_id' => $project->project_id])
                  <div class="panel panel-default">
                    <div class="panel-heading collapsed" role="tab" id="headingTwo" data-toggle="collapse" data-parent="#accordion_" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                      <h4 class="panel-title">
                          Details <span class="pull-right">Project # {{ $project->ref_no }}</span>
                      </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                      <div class="panel-body">
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
                                    <td>{{ $clients[$project->client_id] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Project Type:</strong></td>
                                    <td>{{ $project->project_type }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Start & Deadline:</strong></td>
                                    <td>
                                        {{ date("d M Y, h:ia", strtotime($project->start_date)) }}
                                        <strong>To</strong>
                                        {{ date("d M Y, h:ia", strtotime($project->deadline)) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Currency & Rate:</strong></td>
                                    <td>
                                        {{ $project->currency }}
                                        {{ $project->rate_value }}
                                    </td>
                                </tr>
                                @role('admin')
                                <tr>
                                    <td><strong>Rate Type:</strong></td>
                                    <td>
                                        {{ $project->rate_type }}
                                    </td>
                                </tr>
                                @endrole
                            </tbody>
                        </table>
                      </div>
                      <div class="panel-footer">
                          <a href="{{ route('project.destroy',$project->project_id) }}" class="alert_delete"><i class='fa-2x fa fa-trash-o'></i></a>&nbsp;&nbsp;&nbsp;
                          <a href="{{ route('project.edit',$project->project_id) }}" class="show_edit_form" data-toggle='modal' data-target='#ajax'><i class='fa-2x fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;
                          <a role="menuitem" tabindex="-1" href="{{ url('project') }}" class="pull-right"><i class='fa-2x fa fa-arrow-left'></i></a>
                      </div>
                    </div>
                  </div>
                  @include('common.attachment',['attachments' => $attachments])
                  @include('common.note',['note' => $note, 'belongs_to' => 'project', 'unique_id' => $project->project_id])
                  @include('common.comment',['comments' => $comments, 'belongs_to' => 'project', 'unique_id' => $project->project_id])
                </div>
            </div>
        </div>
    </div>
@stop


