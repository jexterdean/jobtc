@extends('layouts.default')
@section('content')
<div class="modal fade" id="ajax" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Add Task</h4>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
{!! Form::open(['url' => ['taskTimer/' . $task->task_id],'class' => 'task-form'])  !!}
{!! Form::hidden('task_id',$task->task_id) !!}
{!! Form::hidden('user_id',$task->user_id) !!}
<div class="col-md-12">
    <div class="row">
        <div class="col-sm-8">
            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">
                    {{ $task->task_title }}
                    </h3><br/>
                    <div class="text-right">
                        <div class="col-sm-7 col-sm-offset-3">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                                60%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <label class="control-label">Description:</label>
                            <p>{{ $task->task_description }}</p>
                        </div>
                        <div class="col-sm-8">
                            <a href="#" class="btn btn-success btn-shadow btn-sm"><i class="glyphicon glyphicon-plus"></i> Checklist</a><br/><br/>
                            <div class="check-list-container">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <label>
                                            <input type="checkbox" class="checkbox"> Item 1
                                        </label>
                                        <div class="pull-right">
                                            <a href="#"><i class="glyphicon glyphicon-pencil"></i></a>&nbsp;
                                            <a href="#"><i class="glyphicon glyphicon-trash"></i></a>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <label>
                                            <input type="checkbox" class="checkbox"> Item 2
                                        </label>
                                        <div class="pull-right">
                                            <a href="#"><i class="glyphicon glyphicon-pencil"></i></a>&nbsp;
                                            <a href="#"><i class="glyphicon glyphicon-trash"></i></a>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <label>
                                            <input type="checkbox" class="checkbox"> Item 3
                                        </label>
                                        <div class="pull-right">
                                            <a href="#"><i class="glyphicon glyphicon-pencil"></i></a>&nbsp;
                                            <a href="#"><i class="glyphicon glyphicon-trash"></i></a>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <label>
                                            <input type="checkbox" class="checkbox"> Item 4
                                        </label>
                                        <div class="pull-right">
                                            <a href="#"><i class="glyphicon glyphicon-pencil"></i></a>&nbsp;
                                            <a href="#"><i class="glyphicon glyphicon-trash"></i></a>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <label>
                                            <input type="checkbox" class="checkbox"> Item 5
                                        </label>
                                        <div class="pull-right">
                                            <a href="#"><i class="glyphicon glyphicon-pencil"></i></a>&nbsp;
                                            <a href="#"><i class="glyphicon glyphicon-trash"></i></a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <a class="btn btn-shadow btn-info">Assign</a>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a class="btn btn-shadow btn-priority">Priority</a>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a class="btn btn-shadow btn-success">Comment</a>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a class="btn btn-shadow btn-warning">Finish</a>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="{{ url('task/' . $task->task_id .'/edit') }}" data-toggle='modal' data-target='#ajax1' class="btn btn-shadow btn-primary show_edit_form">Edit</a>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="{{ route('task.destroy', $task->task_id) }}" class="alert_delete btn btn-shadow btn-danger">Delete</a>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="{{ url('task') }}" class="btn btn-shadow btn-default"><i class='fa-1x fa fa-arrow-left'></i> Back</a>
                            <a class="pull-right btn btn-shadow btn-stop start_time">Start Time</a>
                            <div class="pull-right col-sm-offset-1" style="margin-right: 10px;">
                                 <h4 class="text-center" id="timer" style="font-size: 20px!important;">
                                    00:00:00
                                 </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title">Comments</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <textarea class="form-control" rows="5" placeholder="Comment"></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-shadow">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-responsive table-bordered">
                                <thead>
                                    <tr>
                                        <th>Account</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Option</th>
                                    </tr>
                                </thead>
                                <tbody class="task-table-body">
                                    @if(count($task_timer) > 0)
                                        @foreach($task_timer as $val)
                                        <tr>
                                            <td>{{ $val->name }}</td>
                                            <td class="text-center">{{ $val->start_time != '0000-00-00 00:00:00' ? date('d/m/Y g:i:s A', strtotime($val->start_time)) : '&nbsp;'}}</td>
                                            <td class="text-center">{{ $val->end_time != '0000-00-00 00:00:00' ? date('d/m/Y g:i:s A', strtotime($val->end_time)) : '&nbsp;'}}</td>
                                            <td class="text-center" style="width: 5%;"><a href=' {{ url('deleteTaskTimer/' . $val->id) }}' class='alert_delete '> <i class='fa fa-trash-o fa-2x'></i> </a></td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4">No data was found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">

        </div>
    </div>
</div>
{!! Form::close() !!}
@stop

